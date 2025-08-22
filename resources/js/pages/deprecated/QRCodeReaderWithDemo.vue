<script setup lang="ts">
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'
import { BrowserMultiFormatReader } from '@zxing/browser'
import { inflateRaw } from 'pako'

/** ---- helpers ---- */
function b64urlDecodeBytes(input: string): Uint8Array {
    input = input.replace(/-/g, '+').replace(/_/g, '/')
    const pad = input.length % 4
    if (pad) input += '='.repeat(4 - pad)
    const binStr = atob(input)
    const out = new Uint8Array(binStr.length)
    for (let i = 0; i < binStr.length; i++) out[i] = binStr.charCodeAt(i)
    return out
}

function parseChunkLine(line: string) {
    // Format: ER|v1|<CODE>|<index>/<total>|<payload>
    const parts = line.split('|', 5)
    if (parts.length < 5 || parts[0] !== 'ER' || parts[1] !== 'v1') return null
    const code = parts[2]
    const [idxStr, totalStr] = parts[3].split('/')
    const index = parseInt(idxStr, 10)
    const total = parseInt(totalStr, 10)
    const payload = parts[4]
    if (!index || !total || !payload) return null
    return { code, index, total, payload }
}

/** ---- state ---- */
const mode = ref<'camera' | 'demo'>('demo')
const erCode = ref<string>('DJOFE5FEQJ9Z')
const chunks = reactive<Map<number, string>>(new Map())  // index -> payload
const totalChunks = ref<number | null>(null)
const decodedJson = ref<any>(null)
const assembling = ref(false)
const apiChunks = ref<Array<{ index: number; text: string; png?: string }>>([])
const scanning = ref(false)
let codeReader: BrowserMultiFormatReader | null = null

// --- NEW: header validation tracking ---
const seenCodes = ref<Set<string>>(new Set())
const seenVersions = ref<Set<string>>(new Set())
const declaredTotals = ref<Set<number>>(new Set())

function resetValidation() {
    seenCodes.value = new Set()
    seenVersions.value = new Set()
    declaredTotals.value = new Set()
}

function validationStatus() {
    if (chunks.size === 0) return { label: 'Unknown', class: 'bg-gray-300 text-gray-800' }

    const codes = Array.from(seenCodes.value)
    const versions = Array.from(seenVersions.value)
    const totals = Array.from(declaredTotals.value)

    const codeMatch = codes.length === 1 && codes[0] === erCode.value
    const vOk = versions.length === 1 && versions[0] === 'v1'
    const totalConsistent = totals.length === 1 && (!!totalChunks.value && totals[0] === totalChunks.value)

    if (codeMatch && vOk && totalConsistent) return { label: 'Match', class: 'bg-emerald-600 text-white' }
    return { label: 'Mismatch', class: 'bg-amber-600 text-white' }
}

function resetAll() {
    chunks.clear()
    totalChunks.value = null
    decodedJson.value = null
    assembling.value = false
    resetValidation() // <-- NEW
}

/** Assemble when all chunks are present */
async function tryAssemble() {
    if (!totalChunks.value || chunks.size !== totalChunks.value) return
    assembling.value = true
    try {
        const joined = Array.from({ length: totalChunks.value }, (_, i) => chunks.get(i + 1)).join('')
        const inflated = inflateRaw(b64urlDecodeBytes(joined), { to: 'string' })
        decodedJson.value = JSON.parse(inflated)
    } catch (e) {
        console.error('[Assemble] Failed:', e)
    } finally {
        assembling.value = false
    }
}

/** Add one chunk line (from camera or demo text) */
function ingestChunkText(line: string) {
    const parsed = parseChunkLine(line)
    if (!parsed) return false

    // --- NEW: record header fields for validation ---
    seenCodes.value.add(parsed.code)
    seenVersions.value.add('v1') // parseChunkLine enforces v1
    declaredTotals.value.add(parsed.total)

    if (totalChunks.value && totalChunks.value !== parsed.total) {
        console.warn('[Ingest] Different total detected; resetting set.')
        resetAll()
        // re‑ingest after reset so this chunk is not lost
        return ingestChunkText(line)
    }

    totalChunks.value = parsed.total
    if (!chunks.has(parsed.index)) {
        chunks.set(parsed.index, parsed.payload)
        tryAssemble()
    }
    return true
}

/** ---- CAMERA MODE ---- */
async function startCamera() {
    if (codeReader) return
    resetAll()
    codeReader = new BrowserMultiFormatReader()
    scanning.value = true
    codeReader.decodeFromVideoDevice(null, 'video', (result) => {
        if (result?.getText()) ingestChunkText(result.getText())
    })
}

function stopCamera() {
    if (codeReader) {
        codeReader.reset()
        codeReader = null
    }
    scanning.value = false
}

/** ---- DEMO MODE ---- */
async function fetchDemo() {
    if (!erCode.value) return
    resetAll()
    const res = await axios.get(route('qr.er', { code: erCode.value }), {
        params: { make_images: 1, max_chars_per_qr: 1200 },
    })
    apiChunks.value = res.data?.chunks ?? []
}

/** Simulate scanning all fetched chunks (in order or shuffled) */
function demoScanAll(shuffle = false) {
    if (!apiChunks.value.length) return
    const arr = [...apiChunks.value]
    if (shuffle) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1))
            ;[arr[i], arr[j]] = [arr[j], arr[i]]
        }
    }
    arr.forEach(c => ingestChunkText(c.text))
}

/** lifecycle */
onMounted(() => {
    if (mode.value === 'camera') startCamera()
    if (mode.value === 'demo' && erCode.value) fetchDemo()
})

onBeforeUnmount(() => {
    stopCamera()
})
</script>

<template>
    <div class="max-w-4xl mx-auto p-4 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Multi‑QR Scanner (Camera + Demo)</h1>

            <!-- NEW: Validator badge -->
            <div class="inline-flex items-center gap-2">
                <span class="text-xs text-gray-600">Validation</span>
                <span
                    class="px-2 py-1 rounded text-xs font-semibold"
                    :class="validationStatus().class"
                    title="Checks: ER code match, version=v1, and consistent totals"
                >
          {{ validationStatus().label }}
        </span>
            </div>
        </div>

        <!-- Mode Switch -->
        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2">
                <input type="radio" value="camera" v-model="mode" @change="() => { stopCamera(); startCamera(); }" />
                <span>Camera</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" value="demo" v-model="mode" @change="() => { stopCamera(); fetchDemo(); }" />
                <span>Demo</span>
            </label>
        </div>

        <!-- CAMERA VIEW -->
        <div v-if="mode === 'camera'">
            <video id="video" style="width: 100%; border: 1px solid #ccc; border-radius: 8px;"></video>
            <div class="text-sm text-gray-600 mt-2">
                Status: <strong>{{ scanning ? 'Scanning…' : 'Idle' }}</strong>
            </div>
        </div>

        <!-- DEMO VIEW -->
        <div v-else class="space-y-4">
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Election Return Code</label>
                    <input
                        v-model="erCode"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        placeholder="e.g. ERTEST001 or DJOFE5FEQJ9Z"
                    />
                </div>
                <button class="px-3 py-2 rounded bg-blue-600 text-white" @click="fetchDemo">
                    Fetch QR Chunks
                </button>
            </div>

            <div v-if="apiChunks.length" class="space-y-3">
                <div class="flex items-center gap-3">
                    <button class="px-3 py-2 rounded bg-emerald-600 text-white" @click="demoScanAll(false)">
                        Simulate Scan (in order)
                    </button>
                    <button class="px-3 py-2 rounded bg-amber-600 text-white" @click="demoScanAll(true)">
                        Simulate Scan (shuffled)
                    </button>
                </div>

                <!-- Thumbnails of QR codes -->
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                    <div
                        v-for="c in apiChunks"
                        :key="c.index"
                        class="border rounded p-2 text-center"
                        :class="chunks.has(c.index) ? 'ring-2 ring-emerald-500' : ''"
                    >
                        <img :src="c.png" alt="QR" class="w-full h-auto" />
                        <div class="text-xs mt-1">
                            {{ c.index }} / {{ (apiChunks[0] && parseInt(c.text.split('|',5)[3].split('/')[1])) || '?' }}
                        </div>
                        <button
                            class="mt-1 w-full text-xs px-2 py-1 rounded bg-gray-800 text-white"
                            @click="ingestChunkText(c.text)"
                        >
                            “Scan” this
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="text-sm text-gray-500">
                No chunks yet. Enter a code above and click <em>Fetch QR Chunks</em>.
            </div>
        </div>

        <!-- Progress -->
        <div v-if="totalChunks" class="text-sm">
            Collected <strong>{{ chunks.size }}</strong> / <strong>{{ totalChunks }}</strong> chunks
            <span v-if="assembling" class="ml-2 text-gray-500">(assembling…)</span>
        </div>

        <!-- Decoded JSON -->
        <div v-if="decodedJson" class="bg-gray-100 rounded p-4">
            <h2 class="font-semibold mb-2">Decoded JSON</h2>
            <pre class="text-xs overflow-auto">{{ JSON.stringify(decodedJson, null, 2) }}</pre>
        </div>

        <!-- Reset -->
        <div>
            <button class="px-3 py-2 rounded bg-gray-200" @click="resetAll">Reset</button>
        </div>
    </div>
</template>
