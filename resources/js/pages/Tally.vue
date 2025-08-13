<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { v4 as uuidv4 } from 'uuid'
import TallyMarks from '@/components/TallyMarks.vue'

/* ---------------- Types ---------------- */
interface CandidateData { code: string; name?: string; alias?: string }
interface VoteData { position_code?: string; position?: { code: string }; candidate_codes?: CandidateData[]; candidates?: CandidateData[] }
interface BallotData { id: string; code: string; votes: VoteData[] }
interface TallyData { position_code: string; candidate_code: string; candidate_name: string; count: number }
interface ElectionReturnData {
    id: string
    code: string
    precinct: { id: string; code: string }
    tallies: TallyData[]
    ballots?: BallotData[]
    last_ballot?: BallotData
}

interface QrChunk { index: number; text: string; png?: string; png_error?: string }
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
const er = ref<ElectionReturnData | null>(null)

const highlights = ref<Set<string>>(new Set())
const flashing = ref<Set<string>>(new Set())

// QR generation settings (operator-tweakable)
const payloadMode = ref<'minimal' | 'full'>('minimal')
const desiredChunks = ref<number>(5)    // aim for ~4 by default
const ecc = ref<'low' | 'medium' | 'quartile' | 'high'>('medium')
const size = ref<number>(640)
const margin = ref<number>(12)
const makeImages = ref<boolean>(true)

const qrChunks = ref<QrChunk[]>([])
const qrLoading = ref(false)
const qrError = ref<string | null>(null)
const qrMeta = ref<QrResponse['params'] | null>(null)

/* ---------------- Helpers ---------------- */
function keyOf(pos: string, cand: string) {
    return `${pos}::${cand}`
}

function computeHighlights(erData: ElectionReturnData | null): Set<string> {
    const set = new Set<string>()
    if (!erData?.last_ballot?.votes) return set

    for (const v of erData.last_ballot.votes as any[]) {
        const pos =
            v.position_code ??
            v.position?.code ??
            (typeof v.position === 'object' ? v.position?.code : undefined)

        const cands: any[] = Array.isArray(v.candidate_codes)
            ? v.candidate_codes
            : Array.isArray(v.candidates)
                ? v.candidates
                : []

        for (const c of cands) {
            const code = typeof c === 'string' ? c : c?.code
            if (pos && code) set.add(`${pos}::${code}`)
        }
    }
    return set
}

function triggerFlash(newSet: Set<string>) {
    if (!newSet.size) return
    flashing.value = new Set(newSet)
    setTimeout(() => { flashing.value = new Set() }, 1200)
}

function copyTextChunk(txt: string) {
    navigator.clipboard?.writeText(txt).catch(() => {})
}

/* ---------------- Fetchers ---------------- */
async function fetchEr() {
    const { data } = await axios.get<ElectionReturnData>(route('precinct-tally'))
    er.value = data
    const newHL = computeHighlights(er.value)
    highlights.value = newHL
    triggerFlash(newHL)
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
// Lightweight parser for full-line chunks: ER|v1|CODE|i/N|<payload>
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
    raw: string            // what the operator pasted (full line or payload)
    fullText: string       // resolved full line if we can, otherwise raw
    parsed?: ParsedHeader  // header parse (only if fullText looked like a line)
}

const pasteInput = ref('')
const pasted: PastedItem[] = ref([])
const persistAudit = ref(false)

function addPasted() {
    const raw = pasteInput.value.trim()
    if (!raw) return

    let item: PastedItem
    if (raw.includes('|')) {
        // Treat as full line
        item = { id: uuidv4(), raw, fullText: raw, parsed: parseHeader(raw) }
    } else {
        // Looks like payload only — keep raw, and still send raw to the server
        item = { id: uuidv4(), raw, fullText: raw }
    }
    pasted.value.push(item)
    pasteInput.value = ''
}

function removePasted(id: string) {
    pasted.value = pasted.value.filter(p => p.id !== id)
}

function clearPasted() {
    pasted.value = []
}

// Derived: code and total (when we have at least one valid header)
const detectedCode = computed(() => {
    const good = pasted.value.find(p => p.parsed && p.parsed.ok)
    return good?.parsed && (good.parsed as any).code || null
})

const detectedTotal = computed<number | null>(() => {
    const good = pasted.value.find(p => p.parsed && p.parsed.ok)
    return good?.parsed && (good.parsed as any).total || null
})

// Validation: duplicates, range, mismatches
const issues = computed(() => {
    const problems: string[] = []
    const headers = pasted.value.filter(p => p.parsed?.ok) as Array<PastedItem & { parsed: Extract<ParsedHeader, {ok:true}> }>
    const totals = new Set(headers.map(h => h.parsed.total))
    if (totals.size > 1) problems.push('Chunks disagree on TOTAL (i/N).')
    const codes = new Set(headers.map(h => h.parsed.code))
    if (codes.size > 1) problems.push('Chunks disagree on ER code.')
    const seen = new Set<number>()
    for (const h of headers) {
        if (seen.has(h.parsed.idx)) problems.push(`Duplicate chunk index: ${h.parsed.idx}`)
        seen.add(h.parsed.idx)
    }
    // If we know total, look for holes
    const N = detectedTotal.value
    if (N && seen.size && seen.size < N) {
        const missing: number[] = []
        for (let i = 1; i <= N; i++) if (!seen.has(i)) missing.push(i)
        problems.push(`Missing chunks: ${missing.join(', ')}`)
    }
    // Note raw payloads without headers
    const payloadOnly = pasted.value.filter(p => !p.parsed).length
    if (payloadOnly > 0) {
        problems.push(`${payloadOnly} item(s) look like payload only — still acceptable, but header checks are skipped.`)
    }
    // Provide parsing errors
    pasted.value.forEach(p => {
        if (p.parsed && p.parsed.ok === false) problems.push(`Unparseable line: ${p.parsed.reason}`)
    })
    return problems
})

const progressMsg = computed(() => {
    const N = detectedTotal.value
    const have = (pasted.value.filter(p => p.parsed?.ok) as any[]).length
    if (!N) return `Collected ${pasted.value.length} item(s).`
    return `Collected ${have}/${N} chunk(s).`
})

const canSubmit = computed(() => pasted.value.length > 0)

// Server round‑trip
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
        // Send exactly what operator pasted (mixed header lines & payloads are fine if backend accepts them)
        const lines = pasted.value.map(p => p.raw)
        const { data } = await axios.post(route('qr.decode'), {
            chunks: lines,
            persist: persistAudit.value,
            persist_dir: 'manual_ui',
        })
        // Expect: { ok: bool, json?: {...}, message?: string, details?: {...} }
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

// Optional: replace the page’s ER with the decoded JSON
function adoptDecodedJson() {
    if (!decodedJson.value) return
    er.value = decodedJson.value as ElectionReturnData
    const newHL = computeHighlights(er.value)
    highlights.value = newHL
    triggerFlash(newHL)
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

        <!-- Tallies Table -->
        <section>
            <table class="table-auto w-full border text-sm">
                <thead class="bg-gray-200 text-left uppercase text-xs">
                <tr>
                    <th class="px-3 py-2">Position</th>
                    <th class="px-3 py-2">Candidate</th>
                    <th class="px-3 py-2 text-center">Votes</th>
                    <th class="px-3 py-2">Tally</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(tally, index) in er?.tallies"
                    :key="index"
                    class="border-t transition-colors duration-700 relative"
                    :class="{
              'bg-red-50': highlights.has(`${tally.position_code}::${tally.candidate_code}`),
              'flash-ring': flashing.has(`${tally.position_code}::${tally.candidate_code}`)
            }"
                >
                    <td class="px-3 py-2 font-mono">{{ tally.position_code }}</td>
                    <td
                        class="px-3 py-2 transition-colors duration-700"
                        :class="{ 'text-red-600 font-bold': highlights.has(`${tally.position_code}::${tally.candidate_code}`) }"
                    >
                        {{ tally.candidate_name }}
                    </td>
                    <td class="px-3 py-2 text-center font-semibold">{{ tally.count }}</td>
                    <td class="px-3 py-2">
                        <TallyMarks
                            :count="tally.count"
                            :highlight-color="highlights.has(`${tally.position_code}::${tally.candidate_code}`) ? 'red' : undefined"
                        />
                    </td>
                </tr>
                </tbody>
            </table>
        </section>

        <!-- QR Tally (Generator) -->
        <section class="space-y-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">QR Tally</h2>
                <div v-if="qrMeta" class="text-xs text-gray-600">
                    <span class="mr-3">payload: <b>{{ qrMeta.payload }}</b></span>
                    <span class="mr-3">desired: <b>{{ qrMeta.desired_chunks ?? '–' }}</b></span>
                    <span>eff.max/chunk: <b>{{ qrMeta.effective_max_chars_per_qr }}</b></span>
                </div>
            </div>

            <div v-if="qrError" class="text-sm text-red-600">{{ qrError }}</div>
            <div v-else-if="qrLoading" class="text-sm text-gray-600">Generating QR chunks…</div>

            <div v-if="totalChunks" class="text-xs text-gray-600">
                Produced {{ totalChunks }} chunk(s).
                <span v-if="anyPngError" class="text-amber-700">
          Some PNGs could not be generated (density). You can still copy full chunk text.
        </span>
            </div>

            <div v-if="totalChunks" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="c in qrChunks" :key="c.index" class="p-3 border rounded">
                    <div class="text-xs mb-2 font-mono">Chunk {{ c.index }} / {{ totalChunks }}</div>

                    <img v-if="c.png" :src="c.png" alt="QR chunk" class="w-full h-auto" />

                    <div v-else class="text-xs text-amber-700 bg-amber-50 border border-amber-200 p-2 rounded">
                        <div class="font-semibold mb-1">PNG not available for this chunk.</div>
                        <div v-if="c.png_error" class="mb-2 break-words">Reason: {{ c.png_error }}</div>
                        <button
                            class="px-2 py-1 text-xs rounded bg-gray-800 text-white hover:bg-black"
                            @click="copyTextChunk(c.text)"
                            title="Copy full chunk text (ER|v1|CODE|i/N|<payload>)"
                        >
                            Copy chunk text
                        </button>
                    </div>
                </div>
            </div>
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

            <!-- Progress / Guidance -->
            <div class="text-xs text-gray-700">
                <div class="mb-1">{{ progressMsg }}</div>
                <ul v-if="issues.length" class="list-disc pl-5 space-y-0.5 text-amber-700">
                    <li v-for="(m, i) in issues" :key="i">{{ m }}</li>
                </ul>
            </div>

            <!-- Collected items -->
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

            <!-- Live preview of decoded JSON -->
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
