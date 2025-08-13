<script setup lang="ts">
import { ref, computed } from 'vue'
import ErTallyView, { type ElectionReturnData } from '@/components/ErTallyView.vue'

/** Raw JSON input (decoded from QR chunks elsewhere) */
const rawJson = ref<string>('')

/** Optional chunk artifacts to render below the table */
const chunkPngs = ref<string[]>([]) // if you have data URIs
const chunkTexts = ref<string[]>([]) // if you only have ER|v1|...|<payload> texts

const er = ref<ElectionReturnData | null>(null)
const parseError = ref<string | null>(null)

function parseAndPreview() {
    parseError.value = null
    try {
        const obj = JSON.parse(rawJson.value)
        // quick sanity
        if (!obj?.precinct?.code || !Array.isArray(obj?.tallies)) {
            throw new Error('JSON does not look like an Election Return payload.')
        }
        er.value = obj
    } catch (e: any) {
        parseError.value = e?.message || String(e)
        er.value = null
    }
}

const qrChunksForView = computed(() => {
    // merge pngs and texts by index 1..N (whichever arrays you filled)
    const n = Math.max(chunkPngs.value.length, chunkTexts.value.length)
    return Array.from({ length: n }, (_, i) => ({
        index: i + 1,
        png: chunkPngs.value[i],
        text: chunkTexts.value[i],
    }))
})

function clearAll() {
    rawJson.value = ''
    chunkPngs.value = []
    chunkTexts.value = []
    er.value = null
    parseError.value = null
}

/** Demo fill (optional) */
function loadSample() {
    const sample = {
        id: 'SAMPLE-ER-001',
        code: 'ERDEMO001',
        precinct: { id: 'P-1', code: 'CURRIMAO-001' },
        tallies: [
            { position_code: 'PRESIDENT', candidate_code: 'AA', candidate_name: 'Alice A.', count: 22 },
            { position_code: 'PRESIDENT', candidate_code: 'BB', candidate_name: 'Benedict B.', count: 15 },
            { position_code: 'VP',        candidate_code: 'CC', candidate_name: 'Carla C.', count: 19 },
        ],
        last_ballot: {
            id: 'BAL-999', code: 'BAL999',
            votes: [
                { position_code: 'PRESIDENT', candidate_codes: [{ code: 'AA' }] },
                { position_code: 'VP',        candidate_codes: [{ code: 'CC' }] },
            ]
        }
    }
    rawJson.value = JSON.stringify(sample, null, 2)
    parseAndPreview()
}
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 space-y-8">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">QR Tally – Stand‑alone Viewer</h1>
            <div class="flex gap-2">
                <button class="px-3 py-2 rounded bg-gray-200" @click="clearAll">Clear</button>
                <button class="px-3 py-2 rounded bg-emerald-600 text-white" @click="loadSample">Load Sample</button>
            </div>
        </header>

        <!-- Input -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Decoded ER JSON</label>
                <textarea
                    v-model="rawJson"
                    rows="14"
                    class="w-full border rounded p-3 font-mono text-xs"
                    placeholder='Paste the decoded JSON here (after QR chunk assembly)…'
                ></textarea>

                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded bg-blue-600 text-white" @click="parseAndPreview">
                        Preview
                    </button>
                </div>

                <p v-if="parseError" class="text-sm text-red-600 mt-2">Error: {{ parseError }}</p>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    (Optional) QR chunk assets
                    <span class="text-gray-500 font-normal">– pass QR PNG data URIs and/or full chunk texts</span>
                </label>
                <textarea
                    v-model="(chunkPngs as any).value"
                    rows="6"
                    class="w-full border rounded p-2 font-mono text-xs"
                    placeholder="One PNG data URI per line (optional)…"
                    @change="chunkPngs = (chunkPngs as any).value.split('\n').filter(Boolean)"
                ></textarea>
                <textarea
                    v-model="(chunkTexts as any).value"
                    rows="6"
                    class="w-full border rounded p-2 font-mono text-xs"
                    placeholder="One chunk text per line (optional)…"
                    @change="chunkTexts = (chunkTexts as any).value.split('\n').filter(Boolean)"
                ></textarea>
                <p class="text-xs text-gray-500">
                    If you include chunk texts but no PNGs, the viewer will still show copy‑buttons for each chunk.
                </p>
            </div>
        </section>

        <!-- Output -->
        <section v-if="er" class="border rounded p-4">
            <ErTallyView :er="er" :qrChunks="qrChunksForView" />
        </section>
        <section v-else class="text-sm text-gray-600">
            Paste the decoded JSON and click <b>Preview</b>.
        </section>
    </div>
</template>
