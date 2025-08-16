<script setup lang="ts">
import { onMounted, watch, ref, computed, toRef } from 'vue'
import { usePrecinctPeople } from '@/composables/usePrecinctPeople'
import { Button } from '@/components/ui/button'
import { useQrCardLayout } from '@/composables/useQrCardLayout'
import { useQrProfiles } from '@/composables/useQrProfiles'
import { useQrApi } from '@/composables/useQrApi'  // ✅ new
import { useHandlePrint } from '@/composables/usePrintHelpers'
import ErOfficialsSignatures from '@/components/ErOfficialsSignatures.vue'
import ErQrChunks from '@/components/ErQrChunks.vue'
import ErPrecinctCard from '@/components/ErPrecinctCard.vue'

type ECC = 'low' | 'medium' | 'quartile' | 'high'
interface CandidateData { code: string; name?: string; alias?: string }
interface VoteData { position_code?: string; position?: { code: string }; candidate_codes?: CandidateData[]; candidates?: CandidateData[] }
interface BallotData { id: string; code: string; votes: VoteData[] }
interface TallyData { position_code: string; candidate_code: string; candidate_name: string; count: number }
export interface ElectionReturnData {
    id: string
    code: string
    precinct: {
        id: string
        code: string
        location_name?: string | null
        latitude?: number | null
        longitude?: number | null
        electoral_inspectors?: Array<{ id: string; name: string; role?: string | null }>
    }
    tallies: TallyData[]
    ballots?: BallotData[]
    last_ballot?: BallotData
    signatures?: Array<{ id?: string; name?: string; role?: string | null; signed_at?: string | null }>
}

interface QrChunk { index: number; text: string; png?: string; png_error?: string }

const props = withDefaults(defineProps<{
    er: ElectionReturnData
    qrChunks?: QrChunk[]
    paper?: 'legal' | 'a4'
    basePt?: number
    autoQr?: boolean
    payload?: 'minimal' | 'full'
    desiredChunks?: number | null
    ecc?: ECC
    size?: number
    margin?: number
    qrEndpoint?: string | null
    printIsolated?: boolean

    /** Preset profile for QR tuning */
    qrProfile?: 'small-clear' | 'normal' | 'high-capacity' | null

    /** NEW (preferred): printed square size in inches */
    qrPrintSizeIn?: number

    /** Deprecated: old rectangular props (coerced to square via min(w,h)) */
    qrPrintWidthIn?: number
    qrPrintHeightIn?: number

    /** Grid columns for QR cards (screen/print) */
    qrGridCols?: number
    /** Gap between QR cards (inches) */
    qrGridGapIn?: number
}>(), {
    paper: 'legal',
    basePt: 10,
    autoQr: true,
    payload: 'minimal',
    desiredChunks: 5,
    ecc: 'medium',
    size: 640,
    margin: 12,
    qrEndpoint: null,
    printIsolated: true,

    qrProfile: 'normal',
    qrPrintSizeIn: 2.5,       // single square control
    qrPrintWidthIn: undefined,
    qrPrintHeightIn: undefined,
    qrGridCols: 3,
    qrGridGapIn: 0.10
})

/* ---------------- Profile → defaults ---------------- */
const {
    effectiveDesiredChunks,
    effectiveEcc,
    effectiveSize,
    effectiveMargin,
} = useQrProfiles(props)

/* ---------------- Local state for UI-only bits ---------------- */
const scopeId = ref(
    `er-print-${(props.er?.code || 'ER').replace(/[^A-Za-z0-9_-]/g, '')}-${Math.random().toString(36).slice(2, 7)}`
)

/* People (inspectors + signatures) */
const { hasPeople } = usePrecinctPeople(toRef(props, 'er'))

/* ---------------- QR API (composable) ----------------
   We bind template directly to the composable’s refs. */
const {
    qr,               // Ref<QrChunk[]>
    qrLoading,        // Ref<boolean>
    qrError,          // Ref<string|null>
    generateQr,       // () => Promise<void>
    triggerGenerateQr // () => void  (debounced inside composable)
} = useQrApi({
    erJsonRef: computed(() => props.er),
    erCodeRef: computed(() => props.er?.code),
    autoQrRef: computed(() => props.autoQr),
    qrChunksPropRef: computed(() => props.qrChunks),
    payloadRef: computed(() => props.payload as ('minimal' | 'full') | undefined),
    desiredChunksRef: effectiveDesiredChunks,
    eccRef: effectiveEcc,
    sizeRef: effectiveSize,
    marginRef: effectiveMargin,
    endpointRef: computed(() => props.qrEndpoint),
    debounceMs: 250,
})

/* ---------------- Totals / guards for template ---------------- */
const totalChunks = computed(() => qr.value.length)
const anyPngError = computed(() => qr.value.some(c => c.png_error))
const showQrBlock = computed(() => totalChunks.value > 0)

/* ---------------- Print helpers (unchanged) ---------------- */
const { handlePrint } = useHandlePrint({
    paperRef: toRef(props, 'paper'),
    scopeIdRef: scopeId,
    autoQrRef: toRef(props, 'autoQr'),
    generateQr,
    printIsolatedRef: toRef(props, 'printIsolated'), // uses your existing prop
    imgSelector: '.qr-img', // same as before
})

/* ---------------- Style vars (grid + size) ---------------- */
const { qrStyleVars } = useQrCardLayout(props)

/* ---------------- Lifecycle & reactive re-fetch ---------------- */
onMounted(() => {
    // Ensure initial fetch happens even if composable watchers are conservative.
    if (props.autoQr && !props.qrChunks?.length) {
        generateQr()
    }
})

// When any effective QR params or endpoint change, debounced re-fetch.
watch(
    () => [
        props.er?.code,
        props.payload,
        effectiveDesiredChunks.value,
        effectiveEcc.value,
        effectiveSize.value,
        effectiveMargin.value,
        props.autoQr,
        props.qrEndpoint
    ],
    () => triggerGenerateQr()
)

// If parent hands us qrChunks later, reflect immediately (composable also handles this,
// but keeping this watch keeps parity with your known-good behavior).
watch(() => props.qrChunks, v => {
    if (v?.length) qr.value = v
})
</script>

<template>
    <div
        :id="scopeId"
        :class="['sheet', paper === 'legal' ? 'paper-legal' : 'paper-a4']"
        :style="[{ fontSize: basePt + 'pt' }, qrStyleVars]"
    >
        <!-- Toolbar (screen only) -->
        <div class="toolbar no-print">
            <div class="left">
                <Button class="px-3 py-2 rounded bg-blue-600 text-white" @click="handlePrint">Print</Button>
            </div>
            <div class="right">
                <span v-if="qrLoading">Generating QR…</span>
                <span v-else-if="qrError" class="text-red-600">{{ qrError }}</span>
            </div>
        </div>

        <div class="layout">
            <main class="content">
                <!-- Title + Precinct card -->
                <div class="mb-2">
                    <div class="title">Election Return — Precinct {{ er.precinct.code }}</div>
                    <div class="text-sm mt-1"><b>ER Code:</b> <span class="mono">{{ er.code }}</span></div>
                </div>
                <ErPrecinctCard :er="er" class="mb-3" />

                <!-- Tallies: 2 columns (Position • Candidate • Votes) -->
                <section class="tallies two-col">
                    <div v-for="(t, i) in er.tallies" :key="i" class="trow">
                        <div class="pos mono" :title="t.position_code">{{ t.position_code }}</div>
                        <div class="cand" :title="t.candidate_name">{{ t.candidate_name }}</div>
                        <div class="votes mono">{{ t.count }}</div>
                    </div>
                </section>

                <!-- Officials & Signatures -->
                <section v-if="hasPeople" class="signers">
                    <ErOfficialsSignatures
                        :er="er"
                        title="Officials & Signatures"
                        :show-signature-lines="true"
                        class="md:col-span-2"
                    />
                </section>
                <!-- QR Block at bottom (square sizing via CSS vars) -->
                <ErQrChunks
                    v-if="showQrBlock"
                    :chunks="qr"
                    title="QR Tally"
                />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Screen layout */
.sheet { background:#fff; color:#111; margin:0 auto; border:1px solid #e5e7eb; }
.toolbar { display:flex; justify-content:space-between; align-items:center; padding:8px 10px; border-bottom:1px solid #e5e7eb; background:#fafafa; }
.btn { padding:6px 10px; border:1px solid #d1d5db; border-radius:6px; background:#fff; }
.btn.tiny { padding:2px 6px; font-size:11px; }
.mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace; }

.layout { padding: 10px; }
.content { min-width:0; }

/* Header */
.header .title { font-size: 1.4em; font-weight: 700; margin-bottom: 6px; }
.header .meta { display:grid; grid-auto-rows:minmax(18px,auto); gap:2px; font-size:.95em; }
.header .coords .map-link { margin-left:6px; text-decoration:underline; color:#2563eb; }

/* Tallies: two balanced columns */
.tallies.two-col {
    columns: 2;
    column-gap: 14px;
    column-fill: balance;
}
.trow {
    break-inside: avoid;
    display: grid;
    grid-template-columns: 150px 1fr 56px;
    gap: 6px;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f1f5f9;
}
.pos { font-weight:600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cand { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.votes { text-align:right; font-weight:700; }

/* QR block (square sizing via CSS vars) */
.qr-block { margin-top: 16px; }
.qr-title { font-weight:700; margin:2px 0 8px; }
.qr-grid {
    display: grid;
    grid-template-columns: repeat(var(--qrcols, 3), var(--qrsize, 2.5in));
    gap: var(--qrgap, 0.10in);
    justify-content: start;
}
.qr-card {
    width: var(--qrsize, 2.5in);
    /* card height = square image + caption space (~0.35in) */
    height: calc(var(--qrsize, 2.5in) + 0.35in);
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: flex-start;
    page-break-inside: avoid;
}
.qr-caption {
    flex: 0 0 auto;
    text-align: center;
    font-size: 10px;
    line-height: 1.2;
    padding: 2px 4px;
    color: #4b5563;
    border-bottom: 1px solid #eef2f7;
}
.qr-img {
    flex: 1 1 auto;
    width: 100%;
    height: 100%;
    object-fit: contain;
    image-rendering: pixelated;
}
.qr-fallback { padding:6px; font-size:12px; }
.qr-fallback .warn { color:#92400e; margin-bottom:4px; }
.qr-fallback .err { color:#991b1b; font-size:11px; }
.qr-note { margin-top:6px; font-size:12px; color:#92400e; }

/* Paper width shells (screen preview) */
.paper-legal { width: 8.5in; box-sizing: border-box; }
.paper-a4    { width: 210mm; box-sizing: border-box; }
</style>

<!-- Unscoped: print fixes -->
<style>
@media print {
    .no-print, .toolbar { display:none !important; }
    html, body { padding:0 !important; margin:0 !important; height:auto !important; }
    .paper-legal, .paper-a4 { min-height: auto !important; height: auto !important; }
    .sheet { border:none !important; }
    .layout { padding:0.35in !important; }
    .tallies.two-col { column-gap:0.22in !important; }
    .trow, .signers, .qr-card { page-break-inside: avoid !important; break-inside: avoid !important; }
}
</style>
