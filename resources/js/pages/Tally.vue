<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import TallyMarks from '@/components/TallyMarks.vue'

interface TallyData {
    position_code: string
    candidate_code: string
    candidate_name: string
    count: number
}

interface ElectionReturnData {
    id: string
    precinct: {
        id: string
        code: string
    }
    tallies: TallyData[]
}

const data = ref<ElectionReturnData | null>(null)

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
                <th class="px-3 py-2">Tally</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(tally, index) in data?.tallies"
                :key="index"
                class="border-t"
            >
                <td class="px-3 py-2 font-mono">{{ tally.position_code }}</td>
                <td class="px-3 py-2">{{ tally.candidate_name }}</td>
                <td class="px-3 py-2">
                    <TallyMarks :count="tally.count" />
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
