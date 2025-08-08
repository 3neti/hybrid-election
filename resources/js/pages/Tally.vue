<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import TallyMarks from '@/components/TallyMarks.vue'

interface TallyData {
    position_code: string
    candidate_code: string
    candidate_name: string
    count: number
}
interface CandidateData { code: string; name: string; alias: string }
interface VoteData { position_code: string; candidate_codes: CandidateData[] }
interface BallotData { id: string; code: string; votes: VoteData[] }
interface ElectionReturnData {
    id: string
    precinct: { id: string; code: string }
    tallies: TallyData[]
    ballots?: BallotData[]
    last_ballot?: BallotData
}

const data = ref<ElectionReturnData | null>(null)
const highlights = ref<Set<string>>(new Set())
// rows that should briefly “flash” when last_ballot changes
const flashing = ref<Set<string>>(new Set())

function keyOf(pos: string, cand: string) {
    return `${pos}::${cand}`
}

function computeHighlights(er: ElectionReturnData | null): Set<string> {
    const set = new Set<string>()
    if (!er?.last_ballot?.votes) return set

    for (const v of er.last_ballot.votes as any[]) {
        // Position code may be flat or nested
        const pos =
            v.position_code ??
            v.position?.code ??
            (typeof v.position === 'object' ? v.position?.code : undefined)

        // Candidates may be under candidate_codes OR candidates
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
    flashing.value = new Set(newSet)  // start flash on these
    // clear after a moment
    setTimeout(() => { flashing.value = new Set() }, 1200)
}

onMounted(async () => {
    const response = await axios.get<ElectionReturnData>(route('precinct-tally'))
    data.value = response.data
    const newHighlights = computeHighlights(data.value)
    highlights.value = newHighlights
    triggerFlash(newHighlights)
})

// If you later re-fetch/poll, call the two lines below
// const newHighlights = computeHighlights(data.value)
// highlights.value = newHighlights; triggerFlash(newHighlights)
</script>

<template>
    <div class="max-w-4xl p-6 mx-auto">
        <h1 class="text-2xl font-bold mb-6">
            Tally for Precinct {{ data?.precinct.code }}
        </h1>

        <table class="table-auto w-full border text-sm">
            <thead class="bg-gray-200 text-left uppercase text-xs">
            <tr>
                <th class="px-3 py-2">Position</th>
                <th class="px-3 py-2">Candidate</th>
                <th class="px-3 py-2">Votes</th>
                <th class="px-3 py-2">Tally</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(tally, index) in data?.tallies"
                :key="index"
                class="border-t transition-colors duration-700 relative"
                :class="{
            'bg-red-50':
              highlights.has(`${tally.position_code}::${tally.candidate_code}`),
            'flash-ring':
              flashing.has(`${tally.position_code}::${tally.candidate_code}`)
          }"
            >
                <td class="px-3 py-2 font-mono">{{ tally.position_code }}</td>
                <td
                    class="px-3 py-2 transition-colors duration-700"
                    :class="{
              'text-red-600 font-bold':
                highlights.has(`${tally.position_code}::${tally.candidate_code}`)
            }"
                >
                    {{ tally.candidate_name }}
                </td>
                <td class="px-3 py-2 text-center font-semibold">{{ tally.count }}</td>
                <td class="px-3 py-2">
                    <TallyMarks
                        :count="tally.count"
                        :highlight-color="
                highlights.has(`${tally.position_code}::${tally.candidate_code}`)
                  ? 'red'
                  : undefined
              "
                    />
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
/* subtle pulse highlight around a newly updated row */
@keyframes ringPulse {
    0%   { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0.25); }
    100% { box-shadow: 0 0 0 0  rgba(239, 68, 68, 0); }
}
.flash-ring {
    animation: ringPulse 0.9s ease-out 1;
}
</style>
