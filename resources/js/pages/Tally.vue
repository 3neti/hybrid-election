<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import TallyMarks from '@/components/TallyMarks.vue'

interface TallyData {
    position_code: string
    candidate_code: string
    candidate_name: string
    count: number
}

interface CandidateData { code: string; name: string; alias: string }
interface PositionData { code: string; name: string; level: string; count: number }
interface VoteData { position: PositionData; candidates: CandidateData[] }
interface BallotData { id: string; code: string; votes: VoteData[] }

interface ElectionReturnData {
    id: string
    precinct: { id: string; code: string }
    tallies: TallyData[]
    ballots?: BallotData[]
    last_ballot?: BallotData | null
}

const data = ref<ElectionReturnData | null>(null)

const highlights = computed<Set<string>>(() => {
    const set = new Set<string>()
    const lb = data.value?.last_ballot
    if (!lb) return set

    for (const vote of lb.votes ?? []) {
        const posCode = vote.position?.code
        if (!posCode) continue
        for (const cand of vote.candidates ?? []) {
            if (cand?.code) set.add(`${posCode}::${cand.code}`)
        }
    }
    return set
})

onMounted(async () => {
    const response = await axios.get<ElectionReturnData>(route('precinct-tally'))
    data.value = response.data
})
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
                class="border-t"
                :class="{
      'bg-red-50':
        highlights.has(`${tally.position_code}::${tally.candidate_code}`)
    }"
            >
                <td class="px-3 py-2 font-mono">{{ tally.position_code }}</td>
                <td
                    class="px-3 py-2"
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
