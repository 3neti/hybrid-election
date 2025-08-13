<script setup lang="ts">
import { computed, ref, watch, onMounted } from 'vue'
import TallyMarks from '@/components/TallyMarks.vue'

/** ---------------- Types ---------------- */
interface CandidateData { code: string; name?: string; alias?: string }
interface VoteData {
    position_code?: string
    position?: { code: string }
    candidate_codes?: CandidateData[]
    candidates?: CandidateData[]
}
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
        electoral_inspectors?: Array<{
            id: string
            name: string
            role?: string | null
        }>
    }
    tallies: TallyData[]
    ballots?: BallotData[]
    last_ballot?: BallotData
    /** optional, lightweight metadata only (no blobs) */
    signatures?: Array<{
        id?: string
        name?: string
        role?: string | null
        signed_at?: string | null
    }>
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

function formatWhen(s?: string | null) {
    if (!s) return ''
    try { return new Date(s).toLocaleString() } catch { return s }
}

function mapsHref(lat?: number | null, lon?: number | null) {
    if (lat == null || lon == null) return null
    return `https://maps.google.com/?q=${lat},${lon}`
}

/** ---------------- Derived ---------------- */
const totalChunks = computed(() => props.qrChunks?.length ?? 0)
const anyPngError = computed(() => (props.qrChunks ?? []).some(c => !!c.png_error))

const hasPrecinctExtras = computed(() => {
    const p = props.er?.precinct
    return !!(p?.location_name || p?.latitude || p?.longitude)
})

/** Merge inspectors + signatures by (name, role); we don’t show “source” */
type MergedSigner = {
    key: string
    name: string
    role?: string | null
    signed_at?: string | null
}
const mergedPeople = computed<MergedSigner[]>(() => {
    const map = new Map<string, MergedSigner>()

    const inspectors = props.er?.precinct?.electoral_inspectors ?? []
    for (const ei of inspectors) {
        const key = `${(ei.name || '').trim().toLowerCase()}|${(ei.role || '').trim().toLowerCase()}`
        map.set(key, {
            key,
            name: ei.name,
            role: ei.role ?? null,
            signed_at: undefined,
        })
    }

    const sigs = props.er?.signatures ?? []
    for (const s of sigs) {
        const key = `${(s.name || '').trim().toLowerCase()}|${(s.role || '').trim().toLowerCase()}`
        const existing = map.get(key)
        if (existing) {
            existing.signed_at = existing.signed_at ?? s.signed_at
        } else {
            map.set(key, {
                key,
                name: s.name || '—',
                role: s.role ?? null,
                signed_at: s.signed_at ?? undefined,
            })
        }
    }

    // Stable, human-friendly sort: by role, then name
    return Array.from(map.values()).sort((a, b) => {
        const ra = (a.role || '').toLowerCase()
        const rb = (b.role || '').toLowerCase()
        if (ra !== rb) return ra < rb ? -1 : 1
        const na = a.name.toLowerCase()
        const nb = b.name.toLowerCase()
        return na < nb ? -1 : na > nb ? 1 : 0
    })
})

const hasPeople = computed(() => mergedPeople.value.length > 0)

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

        <!-- Precinct + Officials (merged with signatures) -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Precinct card -->
            <div v-if="hasPrecinctExtras" class="p-4 border rounded bg-gray-50">
                <h3 class="text-sm font-semibold mb-2">Precinct</h3>
                <dl class="text-sm space-y-1">
                    <div v-if="er.precinct.location_name" class="flex gap-2">
                        <dt class="text-gray-600 w-28">Location</dt>
                        <dd class="font-medium">{{ er.precinct.location_name }}</dd>
                    </div>
                    <div v-if="er.precinct.latitude != null || er.precinct.longitude != null" class="flex gap-2">
                        <dt class="text-gray-600 w-28">Coordinates</dt>
                        <dd class="font-mono">
                            <template v-if="er.precinct.latitude != null">{{ er.precinct.latitude }}</template>
                            <template v-if="er.precinct.latitude != null && er.precinct.longitude != null">, </template>
                            <template v-if="er.precinct.longitude != null">{{ er.precinct.longitude }}</template>
                            <a
                                v-if="mapsHref(er.precinct.latitude, er.precinct.longitude)"
                                :href="mapsHref(er.precinct.latitude, er.precinct.longitude)!"
                                target="_blank"
                                rel="noopener"
                                class="ml-2 underline text-blue-700"
                                title="Open in Google Maps"
                            >
                                Map
                            </a>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Combined Officials & Signatures card (no “Source” column) -->
            <div v-if="hasPeople" class="p-4 border rounded bg-gray-50 md:col-span-2">
                <h3 class="text-sm font-semibold mb-2">Officials & Signatures</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="text-left text-gray-600 uppercase text-xs">
                            <th class="py-2 pr-3">Name</th>
                            <th class="py-2 pr-3">Role</th>
                            <th class="py-2">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="p in mergedPeople" :key="p.key" class="border-t">
                            <td class="py-2 pr-3 font-medium">{{ p.name }}</td>
                            <td class="py-2 pr-3 text-xs uppercase tracking-wide text-gray-700">
                                {{ p.role || '—' }}
                            </td>
                            <td class="py-2">
                  <span v-if="p.signed_at" class="inline-block px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                    signed: {{ formatWhen(p.signed_at) }}
                  </span>
                                <span v-else class="inline-block px-2 py-0.5 rounded bg-amber-100 text-amber-800">
                    pending
                  </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- signature lines for printing (optional, purely visual) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 print:mt-6">
                    <div class="border rounded p-3 text-center">
                        <div class="h-12"></div>
                        <div class="border-t pt-1 mt-2 text-xs text-gray-600">
                            Signature over Printed Name
                        </div>
                    </div>
                    <div class="border rounded p-3 text-center">
                        <div class="h-12"></div>
                        <div class="border-t pt-1 mt-2 text-xs text-gray-600">
                            Signature over Printed Name
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
