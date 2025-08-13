<script setup lang="ts">
import { computed, ref, watch, onMounted } from 'vue'
import TallyMarks from '@/components/TallyMarks.vue'

/** ---------------- Types ---------------- */
interface CandidateData { code: string; name?: string; alias?: string }
interface VoteData { position_code?: string; position?: { code: string }; candidate_codes?: CandidateData[]; candidates?: CandidateData[] }
interface BallotData { id: string; code: string; votes: VoteData[] }
interface TallyData { position_code: string; candidate_code: string; candidate_name: string; count: number }
export interface ElectionReturnData {
    id: string
    code: string
    precinct: { id: string; code: string }
    tallies: TallyData[]
    ballots?: BallotData[]
    last_ballot?: BallotData
}

interface QrChunkLike {
    index: number
    png?: string
    text?: string
    png_error?: string
}

/** ---------------- Props ---------------- */
const props = defineProps<{
    er: ElectionReturnData
    /** optional QR thumbnails / metadata to show (already generated elsewhere) */
    qrChunks?: QrChunkLike[]
    /** heading override */
    title?: string
    /** ms to keep highlight flash for last_ballot changes */
    flashMs?: number
}>()

/** ---------------- Local state ---------------- */
const highlights = ref<Set<string>>(new Set())
const flashing = ref<Set<string>>(new Set())

/** ---------------- Helpers ---------------- */
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

function triggerFlash(newSet: Set<string>, ms = 1200) {
    if (!newSet.size) return
    flashing.value = new Set(newSet)
    setTimeout(() => { flashing.value = new Set() }, ms)
}

function copyTextChunk(txt?: string) {
    if (!txt) return
    navigator.clipboard?.writeText(txt).catch(() => {})
}

/** ---------------- Derived ---------------- */
const totalChunks = computed(() => props.qrChunks?.length ?? 0)
const anyPngError = computed(() => (props.qrChunks ?? []).some(c => !!c.png_error))

/** ---------------- Lifecycle / watchers ---------------- */
onMounted(() => {
    const set = computeHighlights(props.er)
    highlights.value = set
    triggerFlash(set, props.flashMs ?? 1200)
})

watch(() => props.er?.last_ballot, () => {
    const set = computeHighlights(props.er)
    highlights.value = set
    triggerFlash(set, props.flashMs ?? 1200)
})
</script>

<template>
    <div class="space-y-6">
        <header class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">
                    {{ title ?? 'Tally for Precinct ' + (er?.precinct.code ?? '—') }}
                </h1>
                <p class="text-sm text-gray-600">
                    ER Code: <span class="font-mono">{{ er?.code }}</span>
                </p>
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

        <!-- Optional QR thumbnails (if caller passes qrChunks) -->
        <section v-if="totalChunks" class="space-y-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">QR Tally</h2>
                <div v-if="anyPngError" class="text-xs text-amber-700">
                    Some PNGs could not be generated; you can still copy the chunk text.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="c in qrChunks" :key="c.index" class="p-3 border rounded">
                    <div class="text-xs mb-2 font-mono">Chunk {{ c.index }} / {{ totalChunks }}</div>

                    <img v-if="c.png" :src="c.png" alt="QR chunk" class="w-full h-auto" />

                    <div v-else class="text-xs text-amber-700 bg-amber-50 border border-amber-200 p-2 rounded">
                        <div class="font-semibold mb-1">PNG not available for this chunk.</div>
                        <div v-if="c.png_error" class="mb-2 break-words">Reason: {{ c.png_error }}</div>
                        <button
                            class="px-2 py-1 text-xs rounded bg-gray-800 text-white hover:bg-black"
                            @click="copyTextChunk(c.text)"
                            title="Copy full chunk text"
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
