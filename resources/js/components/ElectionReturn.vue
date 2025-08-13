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
    /** If provided, bypass auto generation */
    qrChunks?: QrChunk[]
    /** 'legal' | 'a4' */
    paper?: 'legal' | 'a4'
    /** Base font size in pt */
    basePt?: number
    /** Auto-generate QR chunks on mount/prop changes */
    autoQr?: boolean
    /** QR generation knobs (used when autoQr=true) */
    payload?: 'minimal' | 'full'
    desiredChunks?: number | null
    ecc?: ECC
    size?: number
    margin?: number
    /** Optional: override endpoint. If omitted, uses window.route(...) or /api/qr/election-return/{code} */
    qrEndpoint?: string | null
    /** Print only this component (hide rest of page) */
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
const showQrRail = computed(() => totalChunks.value > 0)

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
    if (props.qrEndpoint) return props.qrEndpoint
    // Ziggy route() if available
    // @ts-ignore
    if (typeof window !== 'undefined' && typeof window.route === 'function') {
        // @ts-ignore
        return window.route('qr.er', { code: erCode })
    }
    return `/api/qr/election-return/${encodeURIComponent(erCode)}`
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
  body * { visibility: hidden !important; }
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

    // Give the browser a beat to apply @media print isolation
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
                        <div class="pos mono">{{ t.position_code }}</div>
                        <div class="cand">{{ t.candidate_name }}</div>
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
            </main>

            <!-- QR Rail -->
            <aside v-if="showQrRail" class="qr-rail">
                <div class="qr-title">QR Tally</div>
                <div class="qr-col">
                    <div v-for="c in qr" :key="c.index" class="qr-card">
                        <div class="qr-caption mono">Chunk {{ c.index }} / {{ totalChunks }}</div>
                        <img v-if="c.png" :src="c.png" class="qr-img" alt="QR chunk" />
                        <div v-else class="qr-fallback">
                            <div class="warn">PNG not available.</div>
                            <div v-if="c.png_error" class="err mono">{{ c.png_error }}</div>
                            <button class="btn tiny no-print" @click="copyTextChunk(c.text)">Copy text</button>
                        </div>
                    </div>
                </div>
            </aside>
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

.layout { display:grid; grid-template-columns: 1fr auto; gap:16px; padding:16px; }
.content { min-width:0; }
.header .title { font-size: 1.4em; font-weight: 700; margin-bottom: 6px; }
.header .meta { display:grid; grid-auto-rows:minmax(18px,auto); gap:2px; font-size:.95em; }
.header .coords .map-link { margin-left:6px; text-decoration:underline; color:#2563eb; }

.tallies.two-col { columns:2; column-gap:16px; }
.trow { break-inside:avoid; display:grid; grid-template-columns:110px 1fr 60px; gap:8px; align-items:center; padding:6px 0; border-bottom:1px solid #f1f5f9; }
.pos { font-weight:600; }
.cand { overflow-wrap:anywhere; }
.votes { text-align:right; font-weight:700; }

.signers { margin-top:16px; }
.section-title { font-weight:700; margin-bottom:6px; }
.signers-table { width:100%; border-collapse:collapse; font-size:.95em; }
.signers-table th, .signers-table td { border-top:1px solid #e5e7eb; padding:6px 8px; text-align:left; }

/* QR rail */
.qr-rail { width: 2.2in; border-left:1px solid #e5e7eb; padding-left:12px; }
.qr-title { font-weight:700; margin:2px 0 8px; }
.qr-col { display:flex; flex-direction:column; gap:12px; }
.qr-card { border:1px solid #e5e7eb; border-radius:8px; padding:8px; }
.qr-caption { font-size:11px; margin-bottom:6px; color:#4b5563; }
.qr-img { width:100%; height:auto; image-rendering:pixelated; }
.qr-fallback .warn { color:#92400e; font-size:12px; margin-bottom:4px; }
.qr-fallback .err { color:#991b1b; font-size:11px; }
</style>

<!-- Unscoped: preview width + print resets (keep @page injected at runtime) -->
<style>
.paper-legal { width: 8.5in; min-height: 14in; box-sizing: border-box; }
.paper-a4    { width: 210mm; min-height: 297mm; box-sizing: border-box; }

@media print {
    .no-print, .toolbar { display:none !important; }
    html, body { padding:0; margin:0; }
    .sheet { border:none; }
    .layout { padding:0.4in; gap:0.25in; }
    .tallies.two-col { column-gap:0.25in; }
    /* @page size is injected dynamically by JS for the chosen paper */
}
</style>
