<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { inflateRaw } from 'pako'
import ErTallyView, { type ElectionReturnData } from '@/components/ErTallyView.vue'
import ElectionReturn from '@/components/ElectionReturn.vue';
import ErQrCapture from '@/components/ErQrCapture.vue' // NEW: scanner

/* ───────────────── Types ───────────────── */
interface QrChunkItem {
    id: string
    index?: number | null
    total?: number | null
    text: string
    png?: string
    status: 'pending' | 'parsed' | 'invalid'
    error?: string | null
}

/* ───────────────── Utilities ───────────────── */
function b64urlDecodeBytes(input: string): Uint8Array {
    // Base64URL → Base64
    input = input.replace(/-/g, '+').replace(/_/g, '/')
    const pad = input.length % 4
    if (pad) input += '='.repeat(4 - pad)
    // atob to bytes
    const binStr = atob(input)
    const out = new Uint8Array(binStr.length)
    for (let i = 0; i < binStr.length; i++) out[i] = binStr.charCodeAt(i)
    return out
}

function parseChunkLine(line: string) {
    // Format: ER|v1|<CODE>|<index>/<total>|<payload>
    const parts = line.split('|', 5)
    if (parts.length < 5 || parts[0] !== 'ER' || parts[1] !== 'v1') {
        return null
    }
    const code = parts[2]
    const [idxStr, totalStr] = parts[3].split('/')
    const index = parseInt(idxStr, 10)
    const total = parseInt(totalStr, 10)
    const payload = parts[4]
    if (!index || !total || !payload) return null
    return { code, index, total, payload }
}

/* ───────────────── State: JSON path ───────────────── */
const rawJson = ref<string>('')                // user-pasted or auto-filled from chunks
const parseError = ref<string | null>(null)
const er = ref<ElectionReturnData | null>(null)

function parseAndPreview() {
    parseError.value = null
    try {
        const obj = JSON.parse(rawJson.value)
        if (!obj?.precinct?.code || !Array.isArray(obj?.tallies)) {
            throw new Error('JSON does not look like an Election Return payload.')
        }
        er.value = obj
    } catch (e: any) {
        parseError.value = e?.message || String(e)
        er.value = null
    }
}

/* ───────────────── State: chunk helper path ───────────────── */
const nextId = () => Math.random().toString(36).slice(2) // simple, no crypto.randomUUID

// list UI
const chunks = reactive<QrChunkItem[]>([])
// assembly buffers
const chunkMap = reactive<Map<number, string>>(new Map())
const totalChunks = ref<number | null>(null)
const assembling = ref(false)
const assembleError = ref<string | null>(null)

// optional PNG bulk helpers (thumbnails area)
const pngBulkInput = ref<string>('')      // one data URI per line
const textBulkInput = ref<string>('')     // one ER|v1|... line per row

/* ───────────── NEW: camera-capture integration (minimal) ───────────── */
const showScanner = ref(false)
const capturedLines = ref<string[]>([])
function onResolvedEr(json: any, meta?: { lines?: string[] }) {
    // Fill JSON + preview, close scanner; keep existing chunk UI untouched
    rawJson.value = JSON.stringify(json, null, 2)
    parseAndPreview()
    showScanner.value = false
    capturedLines.value = meta?.lines ?? []
}

/* ───────────────── Chunk ingestion / assembly ───────────────── */
function addChunkText(line: string) {
    const item: QrChunkItem = {
        id: nextId(),
        text: line.trim(),
        status: 'pending',
        error: null
    }
    chunks.push(item)

    const parsed = parseChunkLine(item.text)
    if (!parsed) {
        item.status = 'invalid'
        item.error = 'Invalid chunk format.'
        return
    }

    // total mismatch reset (start a fresh set)
    if (totalChunks.value && totalChunks.value !== parsed.total) {
        // wipe internal state but keep visual history (mark old entries)
        chunkMap.clear()
        totalChunks.value = null
    }

    totalChunks.value = parsed.total
    if (!chunkMap.has(parsed.index)) {
        chunkMap.set(parsed.index, parsed.payload)
    }
    item.status = 'parsed'
    item.index = parsed.index
    item.total = parsed.total

    tryAssemble()
}

function tryAssemble() {
    assembleError.value = null
    if (!totalChunks.value) return
    if (chunkMap.size !== totalChunks.value) return

    assembling.value = true
    try {
        const joined = Array.from({ length: totalChunks.value }, (_, i) => chunkMap.get(i + 1)).join('')
        const inflated = inflateRaw(b64urlDecodeBytes(joined), { to: 'string' })
        // Fill JSON textarea and parse preview
        rawJson.value = JSON.stringify(JSON.parse(inflated), null, 2)
        parseAndPreview()
    } catch (e: any) {
        assembleError.value = e?.message || String(e)
    } finally {
        assembling.value = false
    }
}

function resetChunks() {
    chunks.splice(0, chunks.length)
    chunkMap.clear()
    totalChunks.value = null
    assembling.value = false
    assembleError.value = null
    pngBulkInput.value = ''
    textBulkInput.value = ''
}

function pasteBulkTexts() {
    // Accept one chunk text per line
    const lines = textBulkInput.value.split('\n').map(s => s.trim()).filter(Boolean)
    for (const line of lines) addChunkText(line)
}

function pasteBulkPngs() {
    // For now, only display thumbnails; decoding PNG to text is not done client-side here.
    // If you later wire a client QR decoder, you can auto-extract chunk text.
    const lines = pngBulkInput.value.split('\n').map(s => s.trim()).filter(Boolean)
    for (const uri of lines) {
        const it: QrChunkItem = { id: nextId(), text: '', png: uri, status: 'pending' }
        chunks.push(it)
    }
}

/* Show progress */
const progressLabel = computed(() => {
    if (!totalChunks.value) {
        return `Collected ${chunkMap.size} chunk(s)`
    }
    return `Collected ${chunkMap.size} / ${totalChunks.value} chunk(s)`
})

/* Keep preview in sync if user edits JSON manually after assembly */
watch(rawJson, (v, old) => {
    // Don’t loop needlessly; parsing already happens on paste and assembly
})
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 space-y-8">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">QR Tally — Stand-alone Viewer</h1>
            <div class="flex gap-2">
                <!-- NEW: open scanner -->
                <button class="px-3 py-2 rounded bg-emerald-600 text-white" @click="showScanner = true">Scan QR Codes</button>
                <button class="px-3 py-2 rounded bg-gray-200" @click="() => { rawJson=''; er=null; parseError=null }">Clear JSON</button>
                <button class="px-3 py-2 rounded bg-gray-200" @click="resetChunks">Reset Chunks</button>
            </div>
        </header>

        <!-- NEW: capture component (kept simple; style/overlay later as you like) -->
        <section v-if="showScanner" class="border rounded p-4">
            <ErQrCapture
                @resolved-er="onResolvedEr"
                @update:chunks="(ls:any) => { capturedLines = ls }"
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

                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded bg-blue-600 text-white" @click="parseAndPreview">
                        Preview
                    </button>
                </div>

                <p v-if="parseError" class="text-sm text-red-600 mt-2">Error: {{ parseError }}</p>
            </div>

            <!-- Chunk helper (one-by-one OR bulk) -->
            <div class="space-y-3">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700">QR Chunk Helper</h2>
                    <p class="text-xs text-gray-500">
                        Enter each QR chunk’s full text (the entire <code>ER|v1|CODE|i/N|&lt;payload&gt;</code> line).
                        When all chunks are present, the JSON box will auto-fill and preview will update.
                    </p>
                </div>

                <div class="flex items-start gap-2">
                    <input
                        type="text"
                        class="flex-1 border rounded px-3 py-2 font-mono text-xs"
                        placeholder="Paste one chunk text here…"
                        @keyup.enter="(e:any) => { const v=e.target.value?.trim(); if(v){ addChunkText(v); e.target.value='' } }"
                    />
                    <button
                        class="px-3 py-2 rounded bg-emerald-600 text-white"
                        @click="(e:any) => { const el=e?.target?.previousElementSibling as HTMLInputElement; const v = el?.value?.trim(); if(v){ addChunkText(v); el.value='' } }"
                    >
                        Add chunk
                    </button>
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

                        <div v-if="c.png">
                            <img :src="c.png" alt="QR" class="w-full h-auto rounded" />
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
                :basePt="10"
                :desired-chunks="16"
            />
        </section>
    </div>
</template>

<style scoped>
/* keep it minimal and readable */
</style>
