<script setup lang="ts">
import { ref, computed, watch, onBeforeUnmount } from 'vue'
import ErTallyView, { type ElectionReturnData } from '@/components/ErTallyView.vue'
import ElectionReturn from '@/components/ElectionReturn.vue'
import ErQrCapture from '@/components/ErQrCapture.vue'
import axios from 'axios'
import { Button } from '@/components/ui/button'
import { route as ziggyRoute } from 'ziggy-js'
import { useChunkAssembler, type QrChunkItem } from '@/composables/useChunkAssembler'

// Prefer global Ziggy route() if present, otherwise ziggy-js route()
const route: any = (typeof (window as any).route === 'function')
    ? (window as any).route
    : ziggyRoute

/* ───────────────── State: JSON & Preview ───────────────── */
const rawJson = ref<string>('')               // user-pasted or assembled (pretty-printed)
const parseError = ref<string | null>(null)
const er = ref<ElectionReturnData | null>(null)

/** Preview handler:
 * - Prefer composable's parsed jsonObject (already validated)
 * - Fallback to parsing rawJson
 */
function parseAndPreview(): void {
    parseError.value = null
    try {
        const obj: any = jsonObject.value ?? JSON.parse(rawJson.value)

        if (!obj?.precinct?.code || !Array.isArray(obj?.tallies)) {
            throw new Error('JSON does not look like an Election Return payload.')
        }

        er.value = obj
        // ensure QR regenerates with current UI value
        debouncedDesiredChunks.value = Math.max(5, Math.min(16, desiredChunksUi.value))
    } catch (e: any) {
        parseError.value = e?.message || String(e)
        er.value = null
    }
}

/* ───────────── Load sample ER JSON from storage ───────────── */
const sampleUrl = ref('/storage/sample-er.json') // fallback when no Ziggy route is available
const loadingSample = ref(false)
const sampleError = ref<string | null>(null)

async function loadSampleFromStorage(): Promise<void> {
    loadingSample.value = true
    sampleError.value = null
    try {
        const url = (typeof route === 'function')
            ? route('er.sample', { name: 'demo' })
            : sampleUrl.value

        const { data } = await axios.get(url)
        rawJson.value = typeof data === 'string' ? data : JSON.stringify(data, null, 2)

        // After loading, prefer parsing into er immediately
        parseAndPreview()
    } catch (err: any) {
        sampleError.value = err?.response?.data?.message || String(err)
    } finally {
        loadingSample.value = false
    }
}

/* ───────────── Scanner → Single ingestion path (composable) ───────────── */
const showScanner = ref(false)
const capturedLines = ref<string[]>([])

// Use composable for ALL chunk ingestion and assembly
const assembler = useChunkAssembler()
const {
    chunks,
    assembling,
    assembleError,
    addChunkLine,
    addMany,
    reset: resetChunksCore,
    total: totalChunksComputed,
    receivedIndices,
    jsonText,       // not directly used here, but available if needed
    jsonObject,     // ✅ prefer this for preview/ER
} = assembler

function onScannerLines(lines: string[]): void {
    if (!Array.isArray(lines)) return
    capturedLines.value = lines
    addMany(lines) // scanner only produces lines; composable does the parsing/assembly
}

// We do not use scanner-side decoded JSON to avoid double logic.
function onScannerResolvedEr(): void {
    showScanner.value = false
}

/* Mirror assembled jsonObject to textarea + view */
function validateErShape(obj: any) {
    if (!obj || !obj.precinct || !obj.precinct.code || !Array.isArray(obj.tallies)) {
        throw new Error('JSON does not look like an Election Return payload.')
    }
}

watch(jsonObject, (val) => {
    if (!val) return
    try {
        validateErShape(val)
        rawJson.value = JSON.stringify(val, null, 2)
        parseError.value = null
        er.value = val as ElectionReturnData
    } catch (e: any) {
        parseError.value = e?.message || String(e)
        er.value = null
    }
})

/* ───────────────── Chunk helper UI-only bits ───────────────── */
const pngBulkInput = ref<string>('') // one data URI per line (for thumbnails only)
const textBulkInput = ref<string>('') // one ER|v1|... line per row

function pasteBulkTexts(): void {
    const lines = textBulkInput.value.split('\n').map(s => s.trim()).filter(Boolean)
    addMany(lines)
}

function pasteBulkPngs(): void {
    const lines = pngBulkInput.value.split('\n').map(s => s.trim()).filter(Boolean)
    for (const uri of lines) {
        // purely for previewing thumbnails; does not affect assembly
        const it: QrChunkItem = { id: Math.random().toString(36).slice(2), text: '', status: 'pending' }
        // @ts-ignore – `png` exists in the UI card shape; harmless for display
        it.png = uri
        // push into visual list the same way as assembled chunks list is displayed
        // (this doesn’t touch the internal assembly map)
        chunks.push(it as any)
    }
}

function resetChunks(): void {
    resetChunksCore()
    pngBulkInput.value = ''
    textBulkInput.value = ''
}

/* Progress label */
const progressLabel = computed(() => {
    const have = receivedIndices.value.length
    const tot = totalChunksComputed.value
    if (!tot) return `Collected ${have} chunk(s)`
    return `Collected ${have} / ${tot} chunk(s)`
})

/* ───────── desired-chunks control (debounced) ───────── */
const desiredChunksUi = ref<number>(5)         // user-facing control
const debouncedDesiredChunks = ref<number>(5)  // prop passed down

let dcTimer: ReturnType<typeof setTimeout> | undefined
watch(desiredChunksUi, (v) => {
    // clamp 5..16
    const n = Math.max(5, Math.min(16, Number(v) || 5))
    if (n !== v) desiredChunksUi.value = n

    // only trigger regeneration when we actually have an ER loaded
    if (!er.value) return

    if (dcTimer) clearTimeout(dcTimer)
    dcTimer = setTimeout(() => {
        debouncedDesiredChunks.value = n
    }, 400) // debounce
})

/* ───────── QR appearance controls (pass-through to ElectionReturn) ─────────
   No debounce needed here: ElectionReturn already debounces regen internally.
*/
type ECC = 'low' | 'medium' | 'quartile' | 'high'
const qrSizeUi   = ref<number>(640)   // px
const qrMarginUi = ref<number>(12)    // modules
const qrEccUi    = ref<ECC>('medium') // ECC level

/* NEW: Preset profile + printed size/grid controls (square print size) */
const qrProfileUi     = ref<'small-clear' | 'normal' | 'high-capacity'>('normal')
const qrPrintSizeInUi = ref<number>(2.5)  // single square control (inches)
const qrGridColsUi    = ref<number>(3)
const qrGridGapInUi   = ref<number>(0.10)

onBeforeUnmount(() => {
    if (dcTimer) clearTimeout(dcTimer)
})
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 space-y-8">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">QR Tally — Stand-alone Viewer</h1>
            <div class="flex gap-2 items-center flex-wrap">
                <!-- desired chunks control -->
                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>Chunks</span>
                    <input
                        type="number"
                        min="5"
                        max="16"
                        v-model.number="desiredChunksUi"
                        class="w-16 px-2 py-1 border rounded text-sm"
                        title="Desired number of QR chunks (5–16)"
                    />
                </label>

                <!-- QR preset profile -->
                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>Profile</span>
                    <select
                        v-model="qrProfileUi"
                        class="px-2 py-1 border rounded text-sm"
                        title="QR data/robustness preset"
                    >
                        <option value="small-clear">small-clear</option>
                        <option value="normal">normal</option>
                        <option value="high-capacity">high-capacity</option>
                    </select>
                </label>

                <!-- QR appearance controls (image generation) -->
                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>Size</span>
                    <input
                        type="number"
                        min="128"
                        step="16"
                        v-model.number="qrSizeUi"
                        class="w-20 px-2 py-1 border rounded text-sm"
                        title="PNG size in pixels"
                    />
                </label>

                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>Margin</span>
                    <input
                        type="number"
                        min="0"
                        max="64"
                        v-model.number="qrMarginUi"
                        class="w-20 px-2 py-1 border rounded text-sm"
                        title="Quiet-zone margin (modules)"
                    />
                </label>

                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>ECC</span>
                    <select
                        v-model="qrEccUi"
                        class="px-2 py-1 border rounded text-sm"
                        title="Error correction level"
                    >
                        <option value="low">low</option>
                        <option value="medium">medium</option>
                        <option value="quartile">quartile</option>
                        <option value="high">high</option>
                    </select>
                </label>

                <!-- Printed layout controls (square size) -->
                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>Print Size (in)</span>
                    <input
                        type="number"
                        min="1"
                        step="0.05"
                        v-model.number="qrPrintSizeInUi"
                        class="w-24 px-2 py-1 border rounded text-sm"
                        title="Printed QR square size (inches)"
                    />
                </label>

                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>Cols</span>
                    <input
                        type="number"
                        min="1"
                        max="6"
                        v-model.number="qrGridColsUi"
                        class="w-16 px-2 py-1 border rounded text-sm"
                        title="Number of QR cards per row"
                    />
                </label>
                <label class="text-sm text-gray-700 flex items-center gap-1">
                    <span>Gap (in)</span>
                    <input
                        type="number"
                        min="0"
                        step="0.05"
                        v-model.number="qrGridGapInUi"
                        class="w-20 px-2 py-1 border rounded text-sm"
                        title="Gap between QR cards (inches)"
                    />
                </label>

                <Button class="px-3 py-2 rounded bg-emerald-600 text-white" @click="showScanner = true">Scan QR Codes</Button>
                <Button class="px-3 py-2 rounded bg-gray-400" @click="() => { rawJson=''; er=null; parseError=null }">Clear JSON</Button>
                <Button class="px-3 py-2 rounded bg-gray-400" @click="resetChunks">Reset Chunks</Button>
            </div>
        </header>

        <!-- Optional error display for sample loading -->
        <p v-if="sampleError" class="text-sm text-red-600 -mt-4">{{ sampleError }}</p>

        <!-- Capture component -->
        <section v-if="showScanner" class="border rounded p-4">
            <ErQrCapture
                @update:chunks="onScannerLines"
                @resolved-er="onScannerResolvedEr"
                @cancel="showScanner = false"
            />
        </section>

        <!-- Row: JSON path (left) + Chunk helper (right) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Direct JSON paste / preview -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Decoded ER JSON</label>
                <textarea
                    v-model="rawJson"
                    rows="16"
                    class="w-full border rounded p-3 font-mono text-xs"
                    placeholder='Paste the decoded JSON here (after QR chunk assembly OR from backend)…'
                ></textarea>

                <!-- Preview + Load sample aligned side-by-side -->
                <div class="flex gap-2">
                    <Button class="px-3 py-2 rounded bg-blue-600 text-white" @click="parseAndPreview">
                        Preview
                    </Button>
                    <Button
                        class="px-3 py-2 rounded bg-indigo-600 text-white"
                        :disabled="loadingSample"
                        @click="loadSampleFromStorage"
                    >
                        {{ loadingSample ? 'Loading…' : 'Load sample' }}
                    </Button>
                </div>

                <p v-if="parseError" class="text-sm text-red-600 mt-2">Error: {{ parseError }}</p>
            </div>

            <!-- Chunk helper (one-by-one OR bulk) -->
            <div class="space-y-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700">QR Chunk Helper</h2>
                    <p class="text-xs text-gray-500">
                        Enter each QR chunk’s full text (the entire
                        <code>ER|v1|CODE|i/N|&lt;payload&gt;</code> line).
                        When all chunks are present, the JSON box will auto-fill and preview will update.
                    </p>
                </div>

                <div class="flex items-start gap-2">
                    <input
                        type="text"
                        class="flex-1 border rounded px-3 py-2 font-mono text-xs"
                        placeholder="Paste one chunk text here…"
                        @keyup.enter="(e:any) => { const v=e.target.value?.trim(); if(v){ addChunkLine(v); e.target.value='' } }"
                    />
                    <Button
                        class="px-3 py-2 rounded bg-emerald-600 text-white"
                        @click="(e:any) => { const el=e?.target?.previousElementSibling as HTMLInputElement; const v = el?.value?.trim(); if(v){ addChunkLine(v); el.value='' } }"
                    >
                        Add chunk
                    </Button>
                </div>

                <div class="text-xs text-gray-600">
                    <strong>{{ progressLabel }}</strong>
                    <span v-if="assembling" class="ml-2 text-gray-500">(assembling…)</span>
                    <span v-if="assembleError" class="ml-2 text-red-600">Assemble error: {{ assembleError }}</span>
                </div>

                <!-- Bulk areas (optional) -->
                <details class="rounded border p-3">
                    <summary class="cursor-pointer text-sm font-semibold">Bulk paste (optional)</summary>
                    <div class="mt-3 space-y-2">
                        <label class="block text-xs font-semibold text-gray-600">One chunk text per line</label>
                        <textarea
                            v-model="textBulkInput"
                            rows="5"
                            class="w-full border rounded p-2 font-mono text-xs"
                            placeholder="ER|v1|...|1/N|<payload>\nER|v1|...|2/N|<payload>\n…"
                        ></textarea>
                        <div class="flex gap-2">
                            <button class="px-3 py-2 rounded bg-gray-800 text-white" @click="pasteBulkTexts">Add all chunk texts</button>
                            <button class="px-3 py-2 rounded bg-gray-200" @click="textBulkInput = ''">Clear</button>
                        </div>

                        <label class="block text-xs font-semibold text-gray-600 mt-4">One PNG Data URI per line (optional)</label>
                        <textarea
                            v-model="pngBulkInput"
                            rows="4"
                            class="w-full border rounded p-2 font-mono text-xs"
                            placeholder="data:image/png;base64,iVBORw0KGgoAAA...\n..."
                        ></textarea>
                        <div class="flex gap-2">
                            <button class="px-3 py-2 rounded bg-gray-800 text-white" @click="pasteBulkPngs">Add all PNGs</button>
                            <button class="px-3 py-2 rounded bg-gray-200" @click="pngBulkInput = ''">Clear</button>
                        </div>
                        <p class="text-[11px] text-gray-500">
                            PNGs are shown as thumbnails here. Client-side decoding from PNG → text isn’t wired
                            (use your backend or camera scanner for that). You can still paste the chunk texts above.
                        </p>
                    </div>
                </details>

                <!-- Current chunk list -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div
                        v-for="c in chunks"
                        :key="c.id"
                        class="p-2 border rounded text-xs space-y-2"
                        :class="{
              'border-emerald-300 bg-emerald-50': c.status==='parsed',
              'border-amber-300 bg-amber-50': c.status==='pending',
              'border-red-300 bg-red-50': c.status==='invalid'
            }"
                    >
                        <div class="flex items-center justify-between">
                            <div class="font-mono">
                                <span v-if="c.index">#{{ c.index }}</span>
                                <span v-if="c.total">/ {{ c.total }}</span>
                                <span v-if="!c.index || !c.total" class="text-gray-500">unparsed</span>
                            </div>
                            <div class="text-[11px]">
                                <span v-if="c.status==='parsed'" class="text-emerald-700">parsed</span>
                                <span v-else-if="c.status==='pending'" class="text-amber-700">pending</span>
                                <span v-else class="text-red-700">invalid</span>
                            </div>
                        </div>

                        <div v-if="(c as any).png">
                            <img :src="(c as any).png" alt="QR" class="w-full h-auto rounded" />
                        </div>

                        <div class="font-mono break-all">{{ c.text || '(no text)' }}</div>
                        <div v-if="c.error" class="text-red-700">{{ c.error }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Output -->
        <section v-if="er" class="border rounded p-4">
            <ErTallyView :er="er" />
        </section>
        <section v-else class="text-sm text-gray-600">
            Paste the decoded JSON and click <b>Preview</b>, or enter all QR chunk texts (the viewer will auto-assemble into JSON).
        </section>

        <section v-if="er" class="border rounded p-4">
            <ElectionReturn
                :er="er"
                paper="legal"
                :base-pt="10"
                :desired-chunks="debouncedDesiredChunks"
                :ecc="qrEccUi"
                :size="qrSizeUi"
                :margin="qrMarginUi"
                :qr-profile="qrProfileUi"
                :qr-print-size-in="qrPrintSizeInUi"
                :qr-grid-cols="qrGridColsUi"
                :qr-grid-gap-in="qrGridGapInUi"
                :qr-endpoint="route('qr.er.from_json')"
            />
        </section>
    </div>
</template>

<style scoped>
/* keep it minimal and readable */
</style>
