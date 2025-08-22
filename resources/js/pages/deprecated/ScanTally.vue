<script setup lang="ts">
import { ref } from 'vue'

// ---- Types & helpers you already use ----
function b64urlDecode(txt: string): Uint8Array {
    let s = txt.replace(/-/g, '+').replace(/_/g, '/')
    while (s.length % 4) s += '='
    const bin = atob(s)
    const out = new Uint8Array(bin.length)
    for (let i = 0; i < bin.length; i++) out[i] = bin.charCodeAt(i)
    return out
}
async function inflateZlib(bytes: Uint8Array): Promise<string> {
    // Use pako or wasm inflate. With pako:
    // import { inflate } from 'pako'
    // return inflate(bytes, { to: 'string' })
    return '' // <-- replace with your inflate (pako) call
}

type Chunk = { version: string; code: string; idx: number; total: number; payload: string }
function parseChunkText(txt: string): Chunk | null {
    const parts = txt.split('|')
    if (parts.length < 5 || parts[0] !== 'ER') return null
    const [ , version, code, idxTotal, payload ] = parts
    const [idxStr, totalStr] = idxTotal.split('/')
    const idx = Number(idxStr); const total = Number(totalStr)
    if (!idx || !total) return null
    return { version, code, idx, total, payload }
}

// ---- State ----
const videoRef = ref<HTMLVideoElement | null>(null)
const canvasRef = ref<HTMLCanvasElement | null>(null)
const resultJson = ref<any | null>(null)
const progress = ref<{total:number, got:Set<number>} | null>(null)
const errorMsg = ref<string | null>(null)
const scanning = ref(false)

// ---- Start camera ----
async function startCamera() {
    errorMsg.value = null
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        if (videoRef.value) {
            videoRef.value.srcObject = stream
            await videoRef.value.play()
        }
    } catch (e: any) {
        errorMsg.value = 'Camera access failed'
    }
}

// ---- One-shot multi decode (zxing-cpp wasm) ----
async function scanOnceMulti() {
    errorMsg.value = null
    resultJson.value = null
    progress.value = null
    if (!videoRef.value || !canvasRef.value) return

    // Draw current frame to canvas
    const v = videoRef.value
    const c = canvasRef.value
    c.width = v.videoWidth
    c.height = v.videoHeight
    const ctx = c.getContext('2d')
    if (!ctx) return
    ctx.drawImage(v, 0, 0, c.width, c.height)
    const imageData = ctx.getImageData(0, 0, c.width, c.height)

    try {
        // PSEUDO: replace with actual zxing-cpp wasm multi-call
        // const results = await scanner.scanImageData(imageData) // returns array of texts
        const results: string[] = [] // <-- fill with real results from the lib

        const chunks = results
            .map(parseChunkText)
            .filter((x): x is Chunk => !!x)

        if (chunks.length === 0) {
            errorMsg.value = 'No ER chunks detected. Try burst scan or move camera closer.'
            return
        }

        // Group by version+code (if multiple ERs visible)
        const key = (c: Chunk) => `${c.version}__${c.code}`
        const groups = new Map<string, Chunk[]>()
        for (const ch of chunks) {
            const k = key(ch)
            if (!groups.has(k)) groups.set(k, [])
            groups.get(k)!.push(ch)
        }

        // Pick the group with most chunks (likely the intended ER)
        let best: Chunk[] = []
        for (const arr of groups.values()) if (arr.length > best.length) best = arr

        const total = best[0].total
        const slots: (string | null)[] = Array(total).fill(null)
        for (const ch of best) slots[ch.idx - 1] = ch.payload

        // If incomplete, show progress & stop here
        const got = new Set(best.map(b => b.idx))
        if (got.size < total) {
            progress.value = { total, got }
            errorMsg.value = `Found ${got.size}/${total} chunks. Try burst scan to complete.`
            return
        }

        // Assemble & decode
        const joined = slots.join('')
        const deflated = b64urlDecode(joined)
        const inflated = await inflateZlib(deflated)
        resultJson.value = JSON.parse(inflated)
    } catch (e: any) {
        errorMsg.value = 'Multi-scan failed'
    }
}

// ---- Burst (continuous) fallback using single-result decoder ----
// PSEUDO using a hypothetical single-result decode function per frame.
let burstTimer: number | null = null
const burstState = { code: '', version: 'v1', total: 0, parts: new Map<number,string>() }

async function scanBurstStart() {
    errorMsg.value = null
    resultJson.value = null
    progress.value = null
    scanning.value = true
    burstState.code = ''
    burstState.total = 0
    burstState.parts.clear()

    const tick = async () => {
        if (!scanning.value || !videoRef.value || !canvasRef.value) return
        const v = videoRef.value
        const c = canvasRef.value
        const ctx = c.getContext('2d'); if (!ctx) return
        c.width = v.videoWidth; c.height = v.videoHeight
        ctx.drawImage(v, 0, 0, c.width, c.height)
        const frame = ctx.getImageData(0, 0, c.width, c.height)

        // PSEUDO: decodeOne returns a single QR string or null
        // const txt = await decodeOne(frame)
        const txt: string | null = null // <-- replace with real decoder

        if (txt) {
            const parsed = parseChunkText(txt)
            if (parsed) {
                if (!burstState.code) {
                    burstState.code = parsed.code
                    burstState.total = parsed.total
                }
                if (parsed.code === burstState.code) {
                    burstState.parts.set(parsed.idx, parsed.payload)
                    progress.value = { total: burstState.total, got: new Set(burstState.parts.keys()) }
                    if (burstState.parts.size === burstState.total) {
                        scanning.value = false
                        const ordered = Array.from({ length: burstState.total }, (_, i) => burstState.parts.get(i + 1) || '')
                        const joined = ordered.join('')
                        const deflated = b64urlDecode(joined)
                        const inflated = await inflateZlib(deflated)
                        resultJson.value = JSON.parse(inflated)
                        return
                    }
                }
            }
        }

        burstTimer = window.setTimeout(tick, 150) // ~7 fps
    }

    tick()
}

function scanBurstStop() {
    scanning.value = false
    if (burstTimer) { clearTimeout(burstTimer); burstTimer = null }
}
</script>

<template>
    <div class="p-4 max-w-5xl mx-auto">
        <h2 class="text-xl font-bold mb-4">Scan ER QR Set</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
            <div>
                <video ref="videoRef" class="w-full rounded border" muted playsinline></video>
                <canvas ref="canvasRef" class="hidden"></canvas>

                <div class="flex gap-2 mt-3">
                    <button class="px-3 py-2 bg-blue-600 text-white rounded" @click="startCamera">Start Camera</button>
                    <button class="px-3 py-2 bg-emerald-600 text-white rounded" @click="scanOnceMulti">One‑Shot Multi</button>
                    <button class="px-3 py-2 bg-indigo-600 text-white rounded" @click="scanBurstStart" :disabled="scanning">Start Burst</button>
                    <button class="px-3 py-2 bg-gray-600 text-white rounded" @click="scanBurstStop" :disabled="!scanning">Stop</button>
                </div>

                <p v-if="progress" class="mt-2 text-sm">
                    Found: {{ progress.got.size }} / {{ progress.total }} chunks
                    <span class="block mt-1 font-mono">
            Missing:
            <span v-for="i in progress.total" :key="i">
              <span v-if="!progress.got.has(i)"> {{ i }} </span>
            </span>
          </span>
                </p>

                <p v-if="errorMsg" class="text-red-600 mt-2">{{ errorMsg }}</p>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Decoded JSON</h3>
                <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto max-h-[60vh]">
{{ resultJson ? JSON.stringify(resultJson, null, 2) : '— Scan to see JSON —' }}
        </pre>
            </div>
        </div>
    </div>
</template>
