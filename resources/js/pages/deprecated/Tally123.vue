<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { v4 as uuidv4 } from 'uuid'
import ErTallyView, { type ElectionReturnData } from '@/components/ErTallyView.vue'

/* ---------------- Types ---------------- */
interface CandidateData { code: string; name?: string; alias?: string }
interface VoteData { position_code?: string; position?: { code: string }; candidate_codes?: CandidateData[]; candidates?: CandidateData[] }
interface BallotData { id: string; code: string; votes: VoteData[] }
interface TallyData { position_code: string; candidate_code: string; candidate_name: string; count: number }

/* Match ErTallyView's richer ER shape */
type ER = ElectionReturnData

interface QrChunk { index: number; text?: string; png?: string; png_error?: string }
interface QrResponse {
    code: string
    version: string
    total: number
    chunks: QrChunk[]
    params?: {
        payload?: string
        desired_chunks?: number | null
        effective_max_chars_per_qr?: number
        ecc?: string
        size?: number
        margin?: number
    }
}

/* ---------------- State ---------------- */
const er = ref<ER | null>(null)

// QR generation settings (operator-tweakable)
const payloadMode = ref<'minimal' | 'full'>('minimal')
const desiredChunks = ref<number>(5)
const ecc = ref<'low' | 'medium' | 'quartile' | 'high'>('medium')
const size = ref<number>(640)
const margin = ref<number>(12)
const makeImages = ref<boolean>(true)

const qrChunks = ref<QrChunk[]>([])
const qrLoading = ref(false)
const qrError = ref<string | null>(null)
const qrMeta = ref<QrResponse['params'] | null>(null)

/* ---------------- Helpers ---------------- */
function copyTextChunk(txt?: string) {
    if (!txt) return
    navigator.clipboard?.writeText(txt).catch(() => {})
}

/* ---------------- Fetchers ---------------- */
async function fetchEr() {
    // Make sure your /election-return returns the DTO with precinct extras + signatures
    const { data } = await axios.get<ER>(route('precinct-tally'))
    er.value = data
}

async function fetchQr() {
    if (!er.value) return
    qrLoading.value = true
    qrError.value = null
    qrChunks.value = []
    qrMeta.value = null

    try {
        const url = route('qr.er', { code: er.value.code })
        const { data } = await axios.get<QrResponse>(url, {
            params: {
                payload: payloadMode.value,
                desired_chunks: desiredChunks.value || undefined,
                make_images: makeImages.value ? 1 : 0,
                ecc: ecc.value,
                size: size.value,
                margin: margin.value
            }
        })
        qrChunks.value = (data.chunks ?? []).sort((a, b) => a.index - b.index)
        qrMeta.value = data.params ?? null

        if (!qrChunks.value.length) {
            qrError.value = 'No QR chunks were returned.'
        }
    } catch (e: any) {
        qrError.value = e?.response?.data?.message || String(e)
    } finally {
        qrLoading.value = false
    }
}

/* ---------------- Decoder (Scan & Paste) ---------------- */
type ParsedHeader = { ok: true; code: string; idx: number; total: number } | { ok: false; reason: string }
function parseHeader(line: string): ParsedHeader {
    const parts = line.split('|')
    if (parts.length < 5) return { ok: false, reason: 'Expected 5 segments separated by "|"' }
    const [tag, ver, code, frac] = parts
    if (tag !== 'ER') return { ok: false, reason: 'Missing "ER" prefix' }
    if (!/^v1$/i.test(ver)) return { ok: false, reason: 'Unsupported version' }
    const m = /^(\d+)\s*\/\s*(\d+)$/.exec(frac || '')
    if (!m) return { ok: false, reason: 'Index/total (i/N) not found' }
    const idx = Number(m[1]), total = Number(m[2])
    if (!Number.isInteger(idx) || !Number.isInteger(total) || idx < 1 || total < 1) {
        return { ok: false, reason: 'Bad i/N numbers' }
    }
    if (!code) return { ok: false, reason: 'Missing ER code' }
    return { ok: true, code, idx, total }
}

interface PastedItem {
    id: string
    raw: string
    fullText: string
    parsed?: ParsedHeader
}

const pasteInput = ref('')
const pasted = ref<PastedItem[]>([])
const persistAudit = ref(false)

function addPasted() {
    const raw = pasteInput.value.trim()
    if (!raw) return

    let item: PastedItem
    if (raw.includes('|')) {
        item = { id: uuidv4(), raw, fullText: raw, parsed: parseHeader(raw) }
    } else {
        item = { id: uuidv4(), raw, fullText: raw }
    }
    pasted.value.push(item)
    pasteInput.value = ''
}
function removePasted(id: string) { pasted.value = pasted.value.filter(p => p.id !== id) }
function clearPasted() { pasted.value = [] }

const detectedCode = computed(() => pasted.value.find(p => p.parsed && p.parsed.ok)?.parsed && (pasted.value.find(p => p.parsed && p.parsed.ok)!.parsed as any).code || null)
const detectedTotal = computed<number | null>(() => pasted.value.find(p => p.parsed && p.parsed.ok)?.parsed && (pasted.value.find(p => p.parsed && p.parsed.ok)!.parsed as any).total || null)

const issues = computed(() => {
    const problems: string[] = []
    const headers = pasted.value.filter(p => p.parsed?.ok) as Array<PastedItem & { parsed: Extract<ParsedHeader, { ok: true }> }>
    const totals = new Set(headers.map(h => h.parsed.total))
    if (totals.size > 1) problems.push('Chunks disagree on TOTAL (i/N).')
    const codes = new Set(headers.map(h => h.parsed.code))
    if (codes.size > 1) problems.push('Chunks disagree on ER code.')
    const seen = new Set<number>()
    for (const h of headers) {
        if (seen.has(h.parsed.idx)) problems.push(`Duplicate chunk index: ${h.parsed.idx}`)
        seen.add(h.parsed.idx)
    }
    const N = detectedTotal.value
    if (N && seen.size && seen.size < N) {
        const missing: number[] = []
        for (let i = 1; i <= N; i++) if (!seen.has(i)) missing.push(i)
        problems.push(`Missing chunks: ${missing.join(', ')}`)
    }
    const payloadOnly = pasted.value.filter(p => !p.parsed).length
    if (payloadOnly > 0) problems.push(`${payloadOnly} item(s) look like payload only — still acceptable, but header checks are skipped.`)
    pasted.value.forEach(p => { if (p.parsed && p.parsed.ok === false) problems.push(`Unparseable line: ${p.parsed.reason}`) })
    return problems
})
const progressMsg = computed(() => {
    const N = detectedTotal.value
    const have = (pasted.value.filter(p => p.parsed?.ok) as any[]).length
    if (!N) return `Collected ${pasted.value.length} item(s).`
    return `Collected ${have}/${N} chunk(s).`
})
const canSubmit = computed(() => pasted.value.length > 0)

const decodeLoading = ref(false)
const decodeError = ref<string | null>(null)
const decodedJson = ref<any | null>(null)
const decodedFrom = ref<'server' | 'client' | null>(null)

async function submitToServer() {
    if (!canSubmit.value) return
    decodeLoading.value = true
    decodeError.value = null
    decodedJson.value = null
    decodedFrom.value = null
    try {
        const lines = pasted.value.map(p => p.raw)
        const { data } = await axios.post(route('qr.decode'), {
            chunks: lines,
            persist: persistAudit.value,
            persist_dir: 'manual_ui',
        })
        if (data?.json) {
            decodedJson.value = data.json
            decodedFrom.value = 'server'
        } else {
            decodeError.value = data?.message || 'Server did not return JSON'
        }
    } catch (e: any) {
        decodeError.value = e?.response?.data?.message || String(e)
    } finally {
        decodeLoading.value = false
    }
}

function adoptDecodedJson() {
    if (!decodedJson.value) return
    er.value = decodedJson.value as ER
}

/* ---------------- Derived (QR section) ---------------- */
const totalChunks = computed(() => qrChunks.value.length)
const anyPngError = computed(() => qrChunks.value.some(c => c.png_error))

/* ---------------- Lifecycle ---------------- */
onMounted(async () => {
    await fetchEr()
    await fetchQr()
})
</script>

<template>
    <div class="max-w-6xl p-6 mx-auto space-y-10">
        <header class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Tally for Precinct {{ er?.precinct.code }}</h1>
                <p class="text-sm text-gray-600">
                    ER Code: <span class="font-mono">{{ er?.code }}</span>
                </p>
            </div>

            <!-- Controls (QR generator) -->
            <div class="flex flex-wrap items-end gap-3">
                <label class="text-xs uppercase tracking-wide text-gray-600">
                    Payload
                    <select v-model="payloadMode" class="mt-1 px-2 py-1 border rounded">
                        <option value="minimal">minimal</option>
                        <option value="full">full</option>
                    </select>
                </label>

                <label class="text-xs uppercase tracking-wide text-gray-600">
                    Desired Chunks
                    <input v-model.number="desiredChunks" type="number" min="1" class="mt-1 w-20 px-2 py-1 border rounded" />
                </label>

                <label class="text-xs uppercase tracking-wide text-gray-600">
                    ECC
                    <select v-model="ecc" class="mt-1 px-2 py-1 border rounded">
                        <option value="low">low</option>
                        <option value="medium">medium</option>
                        <option value="quartile">quartile</option>
                        <option value="high">high</option>
                    </select>
                </label>

                <label class="text-xs uppercase tracking-wide text-gray-600">
                    Size
                    <input v-model.number="size" type="number" min="256" step="32" class="mt-1 w-24 px-2 py-1 border rounded" />
                </label>

                <label class="text-xs uppercase tracking-wide text-gray-600">
                    Margin
                    <input v-model.number="margin" type="number" min="0" step="2" class="mt-1 w-20 px-2 py-1 border rounded" />
                </label>

                <label class="flex items-center gap-2 text-xs uppercase tracking-wide text-gray-600">
                    <input v-model="makeImages" type="checkbox" class="rounded" />
                    PNG
                </label>

                <button
                    class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                    :disabled="qrLoading || !er"
                    @click="fetchQr"
                    title="Regenerate QR using the settings above"
                >
                    {{ qrLoading ? 'Generating…' : 'Regenerate QR' }}
                </button>
            </div>
        </header>

        <!-- Reuse ErTallyView (includes precinct info, officials/signatures, tallies, and QR thumbnails) -->
        <section v-if="er" class="border rounded p-4">
            <ErTallyView
                :er="er"
                :qrChunks="qrChunks"
                :title="`Tally for Precinct ${er.precinct.code}`"
                :flashMs="1200"
            />
        </section>

        <!-- Meta for QR generation -->
        <section v-if="qrMeta" class="text-xs text-gray-600">
            <div>payload: <b>{{ qrMeta.payload }}</b> · desired: <b>{{ qrMeta.desired_chunks ?? '–' }}</b> · eff.max/chunk: <b>{{ qrMeta.effective_max_chars_per_qr }}</b></div>
        </section>

        <!-- Decoder (Scan & Paste) -->
        <section class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Scan & Paste Decoder</h2>
                <div class="flex items-center gap-3 text-xs text-gray-600">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" v-model="persistAudit" />
                        Persist on server (audit)
                    </label>
                </div>
            </div>

            <div class="text-sm text-gray-600">
                Scan each QR and paste the full line (preferred) like:
                <code class="px-1 py-0.5 bg-gray-100 rounded">ER|v1|CODE|1/4|…</code>
                — or paste the raw payload piece. Click <em>Add</em> after each paste.
            </div>

            <div class="flex gap-2">
                <input
                    v-model="pasteInput"
                    type="text"
                    placeholder="Paste an ER|v1|... line or just the payload, then click Add"
                    class="flex-1 px-3 py-2 border rounded"
                    @keydown.enter.prevent="addPasted"
                />
                <button class="px-3 py-2 rounded bg-gray-700 text-white hover:bg-black" @click="addPasted">Add</button>
                <button
                    class="px-3 py-2 rounded border hover:bg-gray-50"
                    :disabled="!pasted.length"
                    @click="clearPasted"
                    title="Clear all pasted items"
                >
                    Clear
                </button>
            </div>

            <div class="text-xs text-gray-700">
                <div class="mb-1">{{ progressMsg }}</div>
                <ul v-if="issues.length" class="list-disc pl-5 space-y-0.5 text-amber-700">
                    <li v-for="(m, i) in issues" :key="i">{{ m }}</li>
                </ul>
            </div>

            <div v-if="pasted.length" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div v-for="p in pasted" :key="p.id" class="p-3 border rounded text-xs">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-mono">
                            <template v-if="p.parsed?.ok">
                                #{{ (p.parsed as any).idx }}/{{ (p.parsed as any).total }} · <b>{{ (p.parsed as any).code }}</b>
                            </template>
                            <template v-else-if="p.parsed && !p.parsed.ok">
                                <span class="text-amber-700">Unparseable</span>
                            </template>
                            <template v-else>
                                <span class="text-gray-500">Payload only</span>
                            </template>
                        </div>
                        <button class="px-2 py-1 border rounded hover:bg-gray-50" @click="removePasted(p.id)">Remove</button>
                    </div>
                    <div class="font-mono break-words text-[11px] leading-snug">
                        {{ p.raw }}
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    class="px-3 py-2 rounded bg-green-600 text-white hover:bg-green-700 disabled:opacity-50"
                    :disabled="!canSubmit || decodeLoading"
                    @click="submitToServer"
                >
                    {{ decodeLoading ? 'Decoding…' : 'Submit to server' }}
                </button>
                <div v-if="decodeError" class="text-sm text-red-600">{{ decodeError }}</div>
            </div>

            <div v-if="decodedJson" class="border rounded overflow-hidden">
                <div class="px-3 py-2 bg-gray-100 text-xs flex items-center justify-between">
                    <div>Decoded JSON ({{ decodedFrom }})</div>
                    <button class="px-2 py-1 text-xs rounded bg-blue-600 text-white hover:bg-blue-700" @click="adoptDecodedJson">
                        Replace table with this ER
                    </button>
                </div>
                <pre class="p-3 text-xs overflow-auto bg-white"><code>{{ JSON.stringify(decodedJson, null, 2) }}</code></pre>
            </div>
        </section>
    </div>
</template>

<style scoped>
@keyframes ringPulse {
    0%   { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0.25); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
.flash-ring { animation: ringPulse 0.9s ease-out 1; }
</style>
