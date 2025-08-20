<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Illuminate\Console\Command;
use App\Models\ElectionReturn;

class PrintElectionReturnPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * - --er is optional: when omitted, the command will auto-use the only ER in DB (if exactly one exists).
     * - code normalization: users may pass "ER-XXXX" or just "XXXX"; DB stores the bare code ("XXXX").
     */
    protected $signature = 'er:print-pdf
        {--er= : Election return code (with or without ER- prefix). Optional if exactly one ER exists.}
        {--paper=legal : Paper size (legal|a4)}
        {--payload=minimal : Payload (minimal|full)}
        {--name=er.pdf : Output filename in the ER folder}
        {--wait=erReady : JS window flag to wait for (set window.erReady=true when the Vue app finishes)}
        {--timeout=30000 : Max wait in ms before printing}
    ';

    protected $description = 'Render the ER Vue page to PDF and persist under storage/app/election/ER-XXXX/';

    public function handle(): int
    {
        // --- Resolve target ER code (DB stores the bare code)
        $erOpt  = (string) ($this->option('er') ?? '');
        $erCode = $erOpt !== '' && str_starts_with($erOpt, 'ER-')
            ? substr($erOpt, 3)
            : $erOpt;

        if ($erCode === '') {
            // QoL behavior: auto-pick only ER if exactly one exists; else error non-interactively
            $count = ElectionReturn::query()->count();
            if ($count === 0) {
                $this->error('No Election Return exists yet. Prepare an ER before printing.');
                return self::FAILURE;
            }
            if ($count > 1) {
                $this->error('Multiple Election Returns found. Please specify --er=CODE (with or without ER- prefix).');
                return self::FAILURE;
            }
            $single = ElectionReturn::query()->firstOrFail();
            $erCode = $single->code; // stored bare in DB
        }

        /** @var ElectionReturn $er */
        $er = ElectionReturn::where('code', $erCode)->first();
        if (!$er) {
            $this->error("Election Return not found for code: {$erCode}");
            return self::FAILURE;
        }

        // --- Build the printable URL (ALWAYS go through your Laravel route if available)
        $paper   = strtolower((string) $this->option('paper')) === 'a4' ? 'a4' : 'legal';
        $payloadOpt = (string) ($this->option('payload') ?? 'minimal');
        $payload = in_array($payloadOpt, ['minimal', 'full'], true) ? $payloadOpt : 'minimal';

        // Prefer a named route if you registered one (e.g., Route::get('/print/er/{code}', ...)->name('print.er'))
        try {
            $url = route('print.er', ['code' => $er->code]) . "?paper={$paper}&payload={$payload}";
        } catch (\Throwable) {
            // Fallback to a hard path if the route name is unavailable
            $url = url("/print/er/{$er->code}?paper={$paper}&payload={$payload}");
        }

        // --- Target path: storage/app/election/ER-XXXX/er.pdf
        $folder = 'ER-' . $er->code; // keep ER- in the folder name for clarity
        $filename = (string) $this->option('name') ?: 'er.pdf';
        $outRel = "{$folder}/{$filename}";

        // Ensure the "election" disk exists and points to storage_path('app/election')
        Storage::disk('election')->makeDirectory($folder);
        $outAbs = Storage::disk('election')->path($outRel);

        // --- Rendering options
        $waitFlag = (string) ($this->option('wait') ?? 'erReady'); // window.erReady = true
        $timeout  = (int) ($this->option('timeout') ?? 30000);

        $this->info("Rendering {$url} → storage/app/election/{$outRel}");

        try {
            $shot = Browsershot::url($url)
                // ->setChromePath('/Applications/Google Chrome.app/Contents/MacOS/Google Chrome') // uncomment if needed
                ->waitUntilNetworkIdle()       // let assets load
                ->timeout($timeout)            // ms
                ->noSandbox()                  // often needed in CI/servers; remove if you don’t need it
                ->showBackground()             // preserve background/colors for print
                ->landscape(false)
                ->scale(1);

            // Paper sizing
            if ($paper === 'a4') {
                $shot->format('A4');
            } else {
                // Legal ≈ 8.5 x 14 inches; Browsershot accepts size in inches via paperSize
                $shot->paperSize(8.5, 14);
            }

            // Optional: wait for Vue to set window.erReady=true after it mounts (see ElectionReturn.vue onMounted)
            if ($waitFlag !== '') {
                $shot->waitForFunction("window.{$waitFlag} === true");
            }

            $shot->savePdf($outAbs);
        } catch (\Throwable $e) {
            $this->error("Failed to render PDF: {$e->getMessage()}");
            return self::FAILURE;
        }

        $this->info("✅ Saved: storage/app/election/{$outRel}");
        return self::SUCCESS;
    }
}
