<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SignatureFileStore
{
    /**
     * Persist a single signature as a file under:
     *   storage/app/signatures/ER-XXXX/{role}{n?}.sig
     *
     * @param  array  $signature  ['id','name','role','signature','signed_at', ...]
     * @param  string $erCode     ER code *with* or *without* "ER-" prefix; folder will always use "ER-"
     * @param  array  $roster     Precinct roster (array of ['id','name','role'])
     */
    public function persist(array $signature, string $erCode, array $roster = []): void
    {
        $disk = Storage::disk('signatures');

        // Ensure ER folder uses ER- prefix
        $folderEr = str_starts_with($erCode, 'ER-') ? $erCode : ('ER-' . $erCode);

        // Normalize role and compute ordinal if multiple people share the same role
        $roleRaw  = strtolower(trim((string)($signature['role'] ?? 'unknown')));
        $roleKey  = Str::slug($roleRaw ?: 'unknown', '-');

        $ordinal  = $this->ordinalForRole($roleRaw, (string)($signature['id'] ?? ''), $roster);

        $filename = $ordinal !== null
            ? "{$roleKey}{$ordinal}.sig"
            : "{$roleKey}.sig";

        $dir = $folderEr;
        $disk->makeDirectory($dir);

        $content = $this->toContent($signature, $folderEr);
        $disk->put("{$dir}/{$filename}", $content);
    }

    /**
     * Persist multiple signatures by delegating to persist() for each entry.
     * Returns a list of written files (relative to the signatures disk root) for convenience.
     *
     * @param  string $erCode      ER code *with* or *without* "ER-" prefix
     * @param  array  $signatures  list of signature arrays (same shape accepted by persist())
     * @param  array  $roster      precinct roster used to compute role ordinals consistently
     * @return array<string>       relative paths that were written
     */
    public function persistMany(string $erCode, array $signatures, array $roster = []): array
    {
        $written = [];
        $disk = Storage::disk('signatures');
        $folderEr = str_starts_with($erCode, 'ER-') ? $erCode : ('ER-' . $erCode);

        // Ensure folder exists before iterating
        $disk->makeDirectory($folderEr);

        foreach ($signatures as $sig) {
            // Delegate to single persist to keep filename logic (and roster ordinal) centralized
            $this->persist($sig, $erCode, $roster);

            // Mirror the filename logic to report what we likely wrote
            $roleRaw = strtolower(trim((string)($sig['role'] ?? 'unknown')));
            $roleKey = Str::slug($roleRaw ?: 'unknown', '-');
            $ordinal = $this->ordinalForRole($roleRaw, (string)($sig['id'] ?? ''), $roster);

            $filename = $ordinal !== null ? "{$roleKey}{$ordinal}.sig" : "{$roleKey}.sig";
            $written[] = "{$folderEr}/{$filename}";
        }

        return $written;
    }

    /**
     * Create the persisted file content (simple key=value lines).
     */
    private function toContent(array $sig, string $erWithPrefix): string
    {
        $lines = [
            'id'         => (string)($sig['id'] ?? ''),
            'name'       => (string)($sig['name'] ?? ''),
            'role'       => (string)($sig['role'] ?? ''),
            'signature'  => (string)($sig['signature'] ?? ''),
            'signed_at'  => (string)($sig['signed_at'] ?? ''),
            'er_code'    => $erWithPrefix,
        ];

        return collect($lines)
                ->map(fn($v, $k) => "{$k}={$v}")
                ->implode(PHP_EOL) . PHP_EOL;
    }

    /**
     * Compute a stable ordinal for the signer *within their role* based on the precinct roster order.
     * Returns:
     *  - 1..N if multiple persons share the role and we can find the signer by id
     *  - null if the role appears once (or signer not found / unknown role)
     */
    private function ordinalForRole(string $role, string $id, array $roster): ?int
    {
        $role = strtolower(trim($role));

        $roleGroup = collect($roster)
            ->map(function ($ei) {
                // normalize each item
                $row  = is_array($ei) ? $ei : (array) $ei;
                $id   = $row['id']   ?? (is_object($ei) && isset($ei->id)   ? $ei->id   : null);
                $name = $row['name'] ?? (is_object($ei) && isset($ei->name) ? $ei->name : null);
                $r    = $row['role'] ?? (is_object($ei) && isset($ei->role) ? $ei->role : null);
                if (is_object($r) && property_exists($r, 'value')) {
                    $r = $r->value;
                }
                return [
                    'id'   => (string) $id,
                    'name' => (string) $name,
                    'role' => is_string($r) ? strtolower(trim($r)) : '',
                ];
            })
            ->filter(fn ($row) => $row['role'] === $role)
            ->values();

        if ($roleGroup->count() <= 1) {
            return null;
        }

        $index = $roleGroup->pluck('id')->search($id, strict: true);
        return $index === false ? null : ($index + 1);
    }
//    private function ordinalForRole(string $role, string $id, array $roster): ?int
//    {
//        $role = strtolower(trim($role));
//
//        $roleGroup = collect($roster)
//            ->map(fn($ei) => is_array($ei) ? $ei : (array)$ei)
//            ->filter(fn($ei) => strtolower((string)($ei['role'] ?? '')) === $role)
//            ->values();
//
//        if ($roleGroup->count() <= 1) {
//            return null; // unique role or missing data → no ordinal suffix
//        }
//
//        $index = $roleGroup->pluck('id')->search($id, strict: true);
//        return $index === false ? null : ($index + 1); // 1-based
//    }
}
