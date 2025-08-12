<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
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
const desiredChunks = ref<number>(4)    // aim for ~4 by default
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

function computeHighlights(er: ElectionReturnData | null): Set<string> {
    const set = new Set<string>()
    if (!er?.last_ballot?.votes) return set

    for (const v of er.last_ballot.votes as any[]) {
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

/* ---------------- Derived ---------------- */
const totalChunks = computed(() => qrChunks.value.length)
const anyPngError = computed(() => qrChunks.value.some(c => c.png_error))

/* ---------------- Lifecycle ---------------- */
onMounted(async () => {
    await fetchEr()
    await fetchQr()
})
</script>

<template>
    <div class="max-w-5xl p-6 mx-auto space-y-8">
        <header class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Tally for Precinct {{ er?.precinct.code }}</h1>
                <p class="text-sm text-gray-600">ER Code: <span class="font-mono">{{ er?.code }}</span></p>
            </div>

            <!-- Controls -->
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

        <!-- QR Tally -->
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
                Produced {{ totalChunks }} chunk(s). <span v-if="anyPngError" class="text-amber-700">Some PNGs could not be generated (density). You can still copy full chunk text.</span>
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
    </div>
</template>

<style scoped>
@keyframes ringPulse {
    0%   { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0.25); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
.flash-ring { animation: ringPulse 0.9s ease-out 1; }
</style>
