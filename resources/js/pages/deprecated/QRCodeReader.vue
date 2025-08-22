<script setup lang="ts">
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue'
import { BrowserMultiFormatReader } from '@zxing/browser'
import { Inflate } from 'pako'

// ---- helpers ----
function b64urlDecode(input: string) {
    input = input.replace(/-/g, '+').replace(/_/g, '/')
    const pad = input.length % 4
    if (pad) input += '='.repeat(4 - pad)
    return Uint8Array.from(atob(input), c => c.charCodeAt(0))
}

const chunks = reactive<Map<number, string>>(new Map())
const totalChunks = ref<number | null>(null)
const decodedJson = ref<any>(null)
const scanning = ref(false)
let codeReader: BrowserMultiFormatReader | null = null

function resetScan() {
    chunks.clear()
    totalChunks.value = null
    decodedJson.value = null
}

function tryAssemble() {
    if (totalChunks.value && chunks.size === totalChunks.value) {
        const payload = Array.from({ length: totalChunks.value }, (_, i) => chunks.get(i + 1)).join('')
        const inflated = Inflate(b64urlDecode(payload), { to: 'string' })
        decodedJson.value = JSON.parse(inflated)
    }
}

onMounted(() => {
    codeReader = new BrowserMultiFormatReader()
    scanning.value = true
    codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
        if (result) {
            const text = result.getText()
            // Expect format: ER|v1|<code>|<index>/<total>|<payload_chunk>
            const parts = text.split('|', 5)
            if (parts.length >= 5 && parts[0] === 'ER' && parts[1] === 'v1') {
                const [idxStr, totalStr] = parts[3].split('/')
                const idx = parseInt(idxStr, 10)
                const total = parseInt(totalStr, 10)
                totalChunks.value = total
                if (!chunks.has(idx)) {
                    chunks.set(idx, parts[4])
                    tryAssemble()
                }
            }
        }
    })
})

onBeforeUnmount(() => {
    if (codeReader) {
        codeReader.reset()
        scanning.value = false
    }
})
</script>

<template>
    <div class="max-w-3xl mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">Multi-QR Scanner</h1>
        <video id="video" style="width: 100%; border: 1px solid #ccc;"></video>

        <div v-if="totalChunks" class="mt-4">
            <p>Scanned {{ chunks.size }} / {{ totalChunks }}</p>
        </div>

        <div v-if="decodedJson" class="mt-6 bg-gray-100 p-4 rounded">
            <h2 class="font-bold">Decoded JSON</h2>
            <pre>{{ JSON.stringify(decodedJson, null, 2) }}</pre>
        </div>

        <button @click="resetScan" class="mt-4 px-3 py-1 bg-blue-600 text-white rounded">
            Reset Scan
        </button>
    </div>
</template>
