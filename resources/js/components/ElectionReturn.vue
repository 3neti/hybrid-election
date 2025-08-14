<script setup lang="ts">
import { onMounted, watch, ref, computed, nextTick } from 'vue'
import axios from 'axios'

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
interface QrResponse {
    code: string
    version: string
    total: number
    chunks: QrChunk[]
    params?: { payload?: string; desired_chunks?: number | null; effective_max_chars_per_qr?: number; ecc?: string; size?: number; margin?: number }
}

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
    printIsolated: true
})

/* ---------------- Local state ---------------- */
const qr = ref<QrChunk[]>(props.qrChunks ?? [])
const qrLoading = ref(false)
const qrError = ref<string | null>(null)

/* Unique scope id for print isolation */
const scopeId = ref(
    `er-print-${(props.er?.code || 'ER').replace(/[^A-Za-z0-9_-]/g, '')}-${Math.random().toString(36).slice(2, 7)}`
)

/* ---------------- Derived ---------------- */
const totalChunks = computed(() => qr.value.length)
const anyPngError = computed(() => qr.value.some(c => c.png_error))
const showQrBlock = computed(() => totalChunks.value > 0)

const hasPrecinctExtras = computed(() => {
    const p = props.er?.precinct
    return !!(p?.location_name || p?.latitude || p?.longitude)
})

/* ---------------- Utils ---------------- */
function formatWhen(s?: string | null) {
    if (!s) return ''
    try { return new Date(s).toLocaleString() } catch { return s }
}
function mapsHref(lat?: number | null, lon?: number | null) {
    if (lat == null || lon == null) return null
    return `https://maps.google.com/?q=${lat},${lon}`
}
function copyTextChunk(txt?: string) {
    if (!txt) return
    navigator.clipboard?.writeText(txt).catch(() => {})
}

/* Merge inspectors + signatures by (name, role) */
type MergedSigner = { key: string; name: string; role?: string | null; signed_at?: string | null }
const mergedPeople = computed<MergedSigner[]>(() => {
    const map = new Map<string, MergedSigner>()
    const insp = props.er?.precinct?.electoral_inspectors ?? []
    for (const i of insp) {
        const key = `${(i.name || '').trim().toLowerCase()}|${(i.role || '').trim().toLowerCase()}`
        map.set(key, { key, name: i.name, role: i.role ?? null, signed_at: undefined })
    }
    const sigs = props.er?.signatures ?? []
    for (const s of sigs) {
        const key = `${(s.name || '').trim().toLowerCase()}|${(s.role || '').trim().toLowerCase()}`
        const prev = map.get(key)
        if (prev) prev.signed_at = prev.signed_at ?? s.signed_at
        else map.set(key, { key, name: s.name || '—', role: s.role ?? null, signed_at: s.signed_at })
    }
    return Array.from(map.values()).sort((a, b) => {
        const ra = (a.role || '').toLowerCase(), rb = (b.role || '').toLowerCase()
        if (ra !== rb) return ra < rb ? -1 : 1
        const na = a.name.toLowerCase(), nb = b.name.toLowerCase()
        return na < nb ? -1 : na > nb ? 1 : 0
    })
})
const hasPeople = computed(() => mergedPeople.value.length > 0)

/* ---------------- QR generation ---------------- */
function resolveQrUrl(erCode: string): string {
    if (props.qrEndpoint) {
        console.log('[resolveQrUrl] Using props.qrEndpoint:', props.qrEndpoint)
        return props.qrEndpoint
    }

    // Prefer a global `route()` if @routes injected it
    if (typeof (window as any).route === 'function') {
        console.log('[resolveQrUrl] Global route() detected.')
        try {
            const url = (window as any).route('qr.er', { code: erCode })
            console.log('[resolveQrUrl] Successfully resolved via route("qr.er"):', url)
            return url
        } catch (err) {
            console.error('[resolveQrUrl] route("qr.er") threw an error:', err)
        }
    } else {
        console.warn('[resolveQrUrl] No global route() found.')
    }

    // Last-resort fallback
    const fallbackUrl = `/api/qr/election-return/${encodeURIComponent(erCode)}`
    console.log('[resolveQrUrl] Falling back to default API path:', fallbackUrl)
    return fallbackUrl
}

async function generateQr() {
    if (!props.autoQr) return
    if (props.qrChunks?.length) { qr.value = props.qrChunks; return }
    if (!props.er?.code) return

    qrLoading.value = true
    qrError.value = null
    qr.value = []
    try {
        const url = resolveQrUrl(props.er.code)
        const { data } = await axios.get<QrResponse>(url, {
            params: {
                payload: props.payload,
                desired_chunks: props.desiredChunks ?? undefined,
                make_images: 1,
                ecc: props.ecc,
                size: props.size,
                margin: props.margin
            }
        })
        qr.value = (data?.chunks ?? []).sort((a, b) => a.index - b.index)
        if (!qr.value.length) qrError.value = 'No QR chunks were returned.'
    } catch (e: any) {
        qrError.value = e?.response?.data?.message || String(e)
    } finally {
        qrLoading.value = false
    }
}

/* ---------------- Print helpers ---------------- */
function injectPageRule(paper: 'legal' | 'a4') {
    const id = 'er-print-page-size'
    let el = document.getElementById(id) as HTMLStyleElement | null
    const css = paper === 'legal' ? '@page{size:8.5in 14in; margin:0}' : '@page{size:A4; margin:0}'
    if (!el) {
        el = document.createElement('style')
        el.id = id
        el.media = 'print'
        document.head.appendChild(el)
    }
    el.textContent = css
}

function injectPrintIsolation(id: string) {
    if (!props.printIsolated) return
    const styleId = `er-print-isolation-${id}`
    let el = document.getElementById(styleId) as HTMLStyleElement | null
    if (!el) {
        el = document.createElement('style')
        el.id = styleId
        el.media = 'print'
        document.head.appendChild(el)
    }
    el.textContent = `
@media print {
  body, html { height: auto !important; }
  body * { visibility: hidden !important; margin: 0 !important; }
  #${id}, #${id} * { visibility: visible !important; }
  #${id} { position: absolute !important; left: 0 !important; top: 0 !important; width: auto !important; }
}`
}

async function waitForImagesWithin(rootSelector: string, imgSelector: string) {
    const root = document.getElementById(rootSelector)
    if (!root) return
    const imgs = Array.from(root.querySelectorAll<HTMLImageElement>(imgSelector))
    const notDone = imgs.filter(i => !i.complete || (i.naturalWidth === 0 && i.naturalHeight === 0))
    await Promise.all(
        notDone.map(
            i =>
                new Promise<void>(res => {
                    i.addEventListener('load', () => res(), { once: true })
                    i.addEventListener('error', () => res(), { once: true })
                })
        )
    )
}

async function handlePrint() {
    injectPageRule(props.paper!)
    injectPrintIsolation(scopeId.value)

    if (props.autoQr && !qr.value.length && !qrLoading.value) {
        await generateQr()
    }

    await nextTick()
    await waitForImagesWithin(scopeId.value, '.qr-img')

    setTimeout(() => window.print(), 60)
}

/* ---------------- Lifecycle ---------------- */
onMounted(generateQr)
watch(
    () => [props.er?.code, props.payload, props.desiredChunks, props.ecc, props.size, props.margin, props.autoQr, props.qrEndpoint],
    () => generateQr()
)
watch(() => props.qrChunks, v => { if (v?.length) qr.value = v })
</script>

<template>
    <div :id="scopeId" :class="['sheet', paper === 'legal' ? 'paper-legal' : 'paper-a4']" :style="{ fontSize: basePt + 'pt' }">
        <!-- Toolbar (screen only) -->
        <div class="toolbar no-print">
            <div class="left">
                <button class="btn" @click="handlePrint">Print</button>
            </div>
            <div class="right">
                <span v-if="qrLoading">Generating QR…</span>
                <span v-else-if="qrError" class="text-red-600">{{ qrError }}</span>
            </div>
        </div>

        <div class="layout">
            <main class="content">
                <!-- Header -->
                <header class="header">
                    <div class="title">Election Return — Precinct {{ er.precinct.code }}</div>
                    <div class="meta">
                        <div><b>ER Code:</b> <span class="mono">{{ er.code }}</span></div>
                        <div v-if="er.precinct.location_name"><b>Location:</b> {{ er.precinct.location_name }}</div>
                        <div v-if="hasPrecinctExtras" class="coords">
                            <b>Coords:</b>
                            <span class="mono">
                <template v-if="er.precinct.latitude != null">{{ er.precinct.latitude }}</template>
                <template v-if="er.precinct.latitude != null && er.precinct.longitude != null">, </template>
                <template v-if="er.precinct.longitude != null">{{ er.precinct.longitude }}</template>
              </span>
                            <a
                                v-if="mapsHref(er.precinct.latitude, er.precinct.longitude)"
                                :href="mapsHref(er.precinct.latitude, er.precinct.longitude)!"
                                target="_blank"
                                rel="noopener"
                                class="map-link no-print"
                            >
                                Map
                            </a>
                        </div>
                    </div>
                </header>

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
                    <div class="section-title">Officials & Signatures</div>
                    <table class="signers-table">
                        <thead>
                        <tr><th>Name</th><th>Role</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        <tr v-for="p in mergedPeople" :key="p.key">
                            <td class="name">{{ p.name }}</td>
                            <td class="role">{{ p.role || '—' }}</td>
                            <td class="status">
                                <span v-if="p.signed_at" class="pill ok">signed: {{ formatWhen(p.signed_at) }}</span>
                                <span v-else class="pill warn">pending</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>

                <!-- QR Block at bottom (3 per row, ~2"x2") -->
                <section v-if="showQrBlock" class="qr-block">
                    <div class="qr-title">QR Tally</div>
                    <div class="qr-grid">
                        <div v-for="c in qr" :key="c.index" class="qr-card" :class="{ 'qr-has-error': !c.png }">
                            <div class="qr-caption mono">Chunk {{ c.index }} / {{ totalChunks }}</div>

                            <template v-if="c.png">
                                <img :src="c.png" class="qr-img" alt="QR chunk" />
                            </template>

                            <template v-else>
                                <div class="qr-fallback">
                                    <div class="warn">PNG not available.</div>
                                    <div v-if="c.png_error" class="err mono">{{ c.png_error }}</div>
                                    <button class="btn tiny no-print" @click="copyTextChunk(c.text)">Copy text</button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div v-if="anyPngError" class="qr-note">
                        Some PNGs could not be generated; you can still copy chunk text.
                    </div>
                </section>
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

.layout { padding: 10px; } /* single column layout now */
.content { min-width:0; }

/* Header */
.header .title { font-size: 1.4em; font-weight: 700; margin-bottom: 6px; }
.header .meta { display:grid; grid-auto-rows:minmax(18px,auto); gap:2px; font-size:.95em; }
.header .coords .map-link { margin-left:6px; text-decoration:underline; color:#2563eb; }

/* Tallies: two balanced columns; keep rows whole */
.tallies.two-col {
    columns: 2;
    column-gap: 14px;
    /* better balance across columns; support varies but harmless */
    column-fill: balance;
}
.trow {
    break-inside: avoid;
    display: grid;
    grid-template-columns: 150px 1fr 56px; /* was 110px 1fr 60px */
    gap: 6px;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f1f5f9;
}
.pos {
    font-weight:600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cand {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.votes { text-align:right; font-weight:700; }

/* Officials */
.signers { margin-top:14px; page-break-inside: avoid; }
.section-title { font-weight:700; margin-bottom:6px; }
.signers-table { width:100%; border-collapse:collapse; font-size:.95em; }
.signers-table th, .signers-table td { border-top:1px solid #e5e7eb; padding:6px 8px; text-align:left; }

/* QR block at bottom */
.qr-block { margin-top: 16px; }
.qr-title { font-weight:700; margin:2px 0 8px; }
.qr-grid {
    display: grid;
    grid-template-columns: repeat(3, 2.5in); /* was 2in */
    gap: 0.10in;                              /* was 0.15in */
    justify-content: start;
}
.qr-card {
    width: 2.5in;           /* was 2in */
    height: 2.85in;         /* a bit taller to fit caption + QR */
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
    font-size: 10px;        /* slightly smaller to free space */
    line-height: 1.2;
    padding: 2px 4px;
    color: #4b5563;
    border-bottom: 1px solid #eef2f7;
}
.qr-img {
    flex: 1 1 auto;
    width: 100%;
    height: 100%;
    object-fit: contain;     /* fill card without cropping */
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
    /* Remove min-heights to avoid huge blank areas after content */
    .paper-legal, .paper-a4 { min-height: auto !important; height: auto !important; }
    .sheet { border:none !important; }
    /* Tighter page padding for print; prevents stray overflow = extra pages */
    .layout { padding:0.35in !important; }
    .tallies.two-col { column-gap:0.22in !important; }
    /* Avoid breaking inside logical blocks */
    .trow, .signers, .qr-card { page-break-inside: avoid !important; break-inside: avoid !important; }
}
</style>
