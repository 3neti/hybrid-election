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
const flashing = ref<Set<string>>(new Set())
const qrCode = ref<string | null>(null) // base64 QR PNG

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
    flashing.value = new Set(newSet)
    setTimeout(() => { flashing.value = new Set() }, 1200)
}

// add a type for the QR response
interface QrChunk { index: number; text: string; png?: string }
interface QrResponse { code: string; version: string; total: number; chunks: QrChunk[] }

const qrCodes = ref<string[]>([]) // hold all chunk images

onMounted(async () => {
    // 1. Fetch ER data
    const response = await axios.get<ElectionReturnData>(route('precinct-tally'))
    data.value = response.data

    // 2. Compute highlights
    const newHighlights = computeHighlights(data.value)
    highlights.value = newHighlights
    triggerFlash(newHighlights)

    // 3. Fetch QR code for the ER JSON
    if (data.value) {
        try {
            const url = route('qr.er', { code: data.value.code }) // <-- use ER code
            const qrResp = await axios.get<QrResponse>(url, {
                params: { make_images: 1, max_chars_per_qr: 1200 }
            })

            // collect all PNG data URIs from chunks
            qrCodes.value = (qrResp.data.chunks ?? [])
                .map(c => c.png)
                .filter((png): png is string => typeof png === 'string')

            if (!qrCodes.value.length) {
                console.warn('[QR Fetch] No PNGs in chunks (did you set make_images=1 on the API?)')
            }
        } catch (e: any) {
            console.error('[QR Fetch] Error:', e?.response?.status, e?.response?.data || e)
        }
    }
})
</script>

<template>
    <div class="max-w-4xl p-6 mx-auto space-y-6">
        <h1 class="text-2xl font-bold">
            Tally for Precinct {{ data?.precinct.code }}
        </h1>

        <!-- Table -->
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
                        'bg-red-50': highlights.has(`${tally.position_code}::${tally.candidate_code}`),
                        'flash-ring': flashing.has(`${tally.position_code}::${tally.candidate_code}`)
                    }"
            >
                <td class="px-3 py-2 font-mono">{{ tally.position_code }}</td>
                <td
                    class="px-3 py-2 transition-colors duration-700"
                    :class="{
                            'text-red-600 font-bold': highlights.has(`${tally.position_code}::${tally.candidate_code}`)
                        }"
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

        <!-- QR Code -->
        <div v-if="qrCodes.length" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="(img, i) in qrCodes" :key="i" class="p-3 border rounded">
                <div class="text-xs mb-2 font-mono">Chunk {{ i + 1 }} / {{ qrCodes.length }}</div>
                <img :src="img" alt="QR chunk" class="w-full h-auto" />
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes ringPulse {
    0%   { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0.25); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
.flash-ring {
    animation: ringPulse 0.9s ease-out 1;
}
</style>
