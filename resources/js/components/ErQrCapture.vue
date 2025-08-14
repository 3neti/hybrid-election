<!-- ErQrCapture.vue -->
<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'
import { BrowserMultiFormatReader } from '@zxing/browser' // fallback
import { DecodeHintType, BarcodeFormat } from '@zxing/library' // ✅ keep only these

/* ───────────────── Types ───────────────── */
type ParsedHeader = { ok:true; code:string; idx:number; total:number } | { ok:false; reason:string }

/* ───────────────── Props / Emits ───────────────── */
const props = withDefaults(defineProps<{
    autoStart?: boolean
    decodeEndpoint?: string | null
    allowManual?: boolean
}>(), {
    autoStart: true,
    decodeEndpoint: null,
    allowManual: true
})

const emit = defineEmits<{
    (e:'update:chunks', lines: string[]): void
    (e:'resolved-er', er: any, meta: { code:string; total:number; lines:string[] }): void
    (e:'cancel'): void
}>()

/* ───────────────── State ───────────────── */
const lines = ref<string[]>([])
const seen = new Set<number>()
const code = ref<string | null>(null)
const total = ref<number | null>(null)
const problems = ref<string[]>([])
const scanning = ref(false)

let frameTimer: number | null = null

/* ───────────────── Header parser ───────────────── */
function parseHeader(line: string): ParsedHeader {
    const parts = line.split('|')
    if (parts.length < 5) return { ok:false, reason:'Expected 5 segments' }
    const [tag, ver, _code, frac] = parts
    if (tag !== 'ER') return { ok:false, reason:'Missing ER tag' }
    if (!/^v1$/i.test(ver)) return { ok:false, reason:'Unsupported version' }
    const m = /^(\d+)\s*\/\s*(\d+)$/.exec(frac || '')
    if (!m) return { ok:false, reason:'Bad i/N' }
    const idx = +m[1], tot = +m[2]
    if (!Number.isInteger(idx) || !Number.isInteger(tot) || idx < 1 || tot < 1) return { ok:false, reason:'Bad numbers' }
    if (!_code) return { ok:false, reason:'Missing code' }
    return { ok:true, code:_code, idx, total:tot }
}

/* ───────────────── Camera / Detection ───────────────── */
const videoEl = ref<HTMLVideoElement | null>(null)
let stream: MediaStream | null = null

// Option A: BarcodeDetector + snapshot bitmap
const supportsBD = typeof window !== 'undefined' && 'BarcodeDetector' in window
let bd: any /* BarcodeDetector | null */ = null
if (supportsBD) {
    // @ts-expect-error
    bd = new BarcodeDetector({ formats: ['qr_code'] })
    console.log('[ER QR] BarcodeDetector supported')
} else {
    console.log('[ER QR] BarcodeDetector NOT supported; will rely on ZXing')
}

// Option B: ZXing fallback
let zxing: BrowserMultiFormatReader | null = null
let zxingRunning = false
let zxingPlainRunning = false // “no-hints” fallback

// NEW: camera list/selection
const devices = ref<MediaDeviceInfo[]>([])
const selectedDeviceId = ref<string | null>(null)

// debug counters
let bdHits = 0
let frameCount = 0
let lastLogAt = 0

function dbg(...args:any[]) {
    const now = performance.now()
    if (now - lastLogAt > 400) {
        console.log('[ER QR]', ...args)
        lastLogAt = now
    }
}

async function listCameras() {
    try {
        const devs = await navigator.mediaDevices.enumerateDevices()
        devices.value = devs.filter(d => d.kind === 'videoinput')
        if (!selectedDeviceId.value && devices.value.length) {
            const iphone = devices.value.find(d => /iphone|continuity/i.test(d.label))
            selectedDeviceId.value = (iphone?.deviceId ?? devices.value[0]?.deviceId) || null
        }
        console.log('[ER QR] cameras:', devices.value.map(d => d.label || '(camera)'))
    } catch (e) {
        console.warn('[ER QR] enumerateDevices failed:', e)
    }
}

async function start() {
    if (scanning.value) return
    scanning.value = true
    problems.value = []
    bdHits = 0
    frameCount = 0
    lastLogAt = 0

    try {
        console.log('[ER QR] Requesting camera…')
        const constraints: MediaStreamConstraints = selectedDeviceId.value
            ? { video: { deviceId: { exact: selectedDeviceId.value } as any }, audio: false }
            : { video: true, audio: false }

        stream = await navigator.mediaDevices.getUserMedia(constraints)
        console.log('[ER QR] Stream settings:', stream.getVideoTracks()[0]?.getSettings?.())

        await listCameras()

        if (!selectedDeviceId.value && devices.value.length) {
            const iphone = devices.value.find(d => /iphone|continuity/i.test(d.label))
            selectedDeviceId.value = (iphone?.deviceId ?? devices.value[0]?.deviceId) || null
        }
        if (selectedDeviceId.value && constraints.video === true) {
            console.log('[ER QR] Switching to selected camera…')
            stopTracksOnly()
            stream = await navigator.mediaDevices.getUserMedia({ video: { deviceId: { exact: selectedDeviceId.value } as any }, audio: false })
        }

        if (videoEl.value) {
            videoEl.value.srcObject = stream
            videoEl.value.playsInline = true
            try { videoEl.value.muted = true } catch {}
            videoEl.value.addEventListener('loadedmetadata', () => {
                console.log('[ER QR] video loadedmetadata w x h:', videoEl.value?.videoWidth, videoEl.value?.videoHeight)
            }, { once: true })
            videoEl.value.addEventListener('canplay', () => {
                console.log('[ER QR] video canplay')
            }, { once: true })
            videoEl.value.addEventListener('playing', () => {
                console.log('[ER QR] video playing')
            }, { once: true })

            await videoEl.value.play()
            console.log('[ER QR] video.play() readyState:', videoEl.value.readyState)
        }

        // Run BD & ZXing concurrently
        tick()
        tryStartZxing(true)                       // with hints
        setTimeout(() => {                        // start plain fallback soon after
            if (scanning.value && !zxingPlainRunning) tryStartZxing(false)
        }, 1500)
    } catch (e) {
        scanning.value = false
        const msg = 'Camera error: ' + String(e)
        console.error('[ER QR]', msg)
        problems.value = [msg]
    }
}

function stop() {
    console.log('[ER QR] Stopping scanner…')
    scanning.value = false
    if (frameTimer) { cancelAnimationFrame(frameTimer); frameTimer = null }
    stopZxingAll()
    stopTracksOnly()
}

function stopTracksOnly() {
    stream?.getTracks().forEach(t => t.stop())
    stream = null
}

/* Main BD loop */
async function tick() {
    if (!scanning.value || !videoEl.value) return
    frameTimer = requestAnimationFrame(tick)
    frameCount++
    if (frameCount % 30 === 0) dbg('tick frame:', frameCount, 'readyState:', videoEl.value.readyState)

    if (bd && videoEl.value.readyState >= 2) {
        try {
            const bmp = await createImageBitmap(videoEl.value)
            const codes = await bd.detect(bmp)
            // @ts-ignore
            bmp.close?.()
            if (codes && codes.length) {
                bdHits += codes.length
                console.log('[ER QR] BD detected', codes.length, 'code(s)')
                for (const c of codes) {
                    const text = (c as any).rawValue || (c as any).rawValueText || ''
                    console.log('[ER QR] BD text:', text)
                    handleCandidateText(text)
                }
            }
        } catch (err) {
            if (frameCount % 60 === 0) dbg('BD detect err (normal):', err)
        }
    }
}

/* ZXing controls */
function tryStartZxing(withHints: boolean) {
    if (!videoEl.value) return
    if (withHints && zxingRunning) return
    if (!withHints && zxingPlainRunning) return

    const tag = withHints ? 'ZXing+hints' : 'ZXing(plain)'
    console.log('[ER QR] starting', tag, '…')

    let reader: BrowserMultiFormatReader
    if (withHints) {
        // ✅ PASS A REAL Map OF HINTS (the prior error came from passing a reader)
        const hints = new Map()
        hints.set(DecodeHintType.POSSIBLE_FORMATS, [BarcodeFormat.QR_CODE])
        hints.set(DecodeHintType.TRY_HARDER, true)
        reader = new BrowserMultiFormatReader(hints, 200) // (hints, timeBetweenScansMs)
        zxing = reader
        zxingRunning = true
    } else {
        reader = new BrowserMultiFormatReader()
        zxingPlainRunning = true
    }

    reader.decodeFromVideoDevice(null, videoEl.value, (result, err) => {
        if (result) {
            const text = result.getText()
            console.log('[ER QR]', tag, 'detected:', text)
            handleCandidateText(text)
        } else if (err) {
            // common per-frame “NotFoundException”; keep quiet
        }
    })
}

function stopZxingAll() {
    if (zxing) {
        console.log('[ER QR] ZXing reset')
        zxing.reset()
        zxing = null
    }
    zxingRunning = false
    zxingPlainRunning = false
}

/* ───────────────── Collector ───────────────── */
function handleCandidateText(raw: string) {
    const line = raw.trim()
    if (!line) { console.log('[ER QR] candidate empty'); return }
    console.log('[ER QR] candidate:', line)

    if (!line.includes('|')) {
        console.log('[ER QR] Non-ER QR detected (detection OK).')
        return
    }

    const parsed = parseHeader(line)
    if (!parsed.ok) { console.log('[ER QR] parseHeader failed:', parsed); return }

    if (code.value && parsed.code !== code.value) { console.log('[ER QR] Different ER code; ignored'); return }
    if (total.value && parsed.total !== total.value) { console.log('[ER QR] Different TOTAL; ignored'); return }

    code.value = code.value ?? parsed.code
    total.value = total.value ?? parsed.total

    if (seen.has(parsed.idx)) { console.log('[ER QR] duplicate idx', parsed.idx); return }
    seen.add(parsed.idx)

    lines.value.push(line)
    lines.value.sort((a,b) => (parseHeader(a) as any).idx - (parseHeader(b) as any).idx)
    console.log('[ER QR] accepted idx', parsed.idx, 'progress', lines.value.length, '/', total.value ?? '?')

    emit('update:chunks', lines.value.slice())

    if (total.value && lines.value.length === total.value) {
        console.log('[ER QR] complete set → decodeNow()')
        decodeNow()
    } else {
        recomputeProblems()
    }
}

function recomputeProblems() {
    const msgs: string[] = []
    const parsed = lines.value.map(l => parseHeader(l)).filter(p => (p as any).ok) as Extract<ParsedHeader,{ok:true}>[]
    const totals = Array.from(new Set(parsed.map(p=>p.total)))
    const codes  = Array.from(new Set(parsed.map(p=>p.code)))
    if (totals.length > 1) msgs.push('Chunks disagree on TOTAL.')
    if (codes.length > 1)  msgs.push('Chunks disagree on ER code.')
    const N = total.value || parsed[0]?.total
    if (N) {
        const missing:number[] = []
        const have = new Set(parsed.map(p=>p.idx))
        for (let i=1;i<=N;i++) if (!have.has(i)) missing.push(i)
        if (missing.length) msgs.push(`Missing: ${missing.join(', ')}`)
    }
    problems.value = msgs
    if (msgs.length) console.log('[ER QR] problems:', msgs)
}

/* ───────────────── Server-side decode ───────────────── */
async function decodeNow() {
    stop()
    try {
        const url = props.decodeEndpoint || '/api/qr.decode'
        console.log('[ER QR] POST', url, 'with', lines.value.length, 'chunks')
        const { data } = await axios.post(url, { chunks: lines.value, persist: true, persist_dir: 'camera_ui' })
        if (data?.json) {
            console.log('[ER QR] server returned JSON; emitting resolved-er')
            emit('resolved-er', data.json, { code: code.value!, total: total.value!, lines: lines.value.slice() })
        } else {
            const msg = data?.message || 'Server did not return JSON'
            console.warn('[ER QR]', msg)
            problems.value = [msg]
        }
    } catch (e:any) {
        const msg = e?.response?.data?.message || String(e)
        console.error('[ER QR] decode error:', msg)
        problems.value = [msg]
    }
}

/* ───────────────── Manual fallback ───────────────── */
const manual = ref('')
function addManual() {
    const v = manual.value.trim()
    manual.value = ''
    if (!v) return
    console.log('[ER QR] manual add:', v)
    handleCandidateText(v)
}

/* ───────────────── Lifecycle ───────────────── */
onMounted(() => {
    navigator.mediaDevices?.enumerateDevices?.().then(() => listCameras())
    if (props.autoStart) start()
})
onBeforeUnmount(stop)
</script>

<template>
    <div class="capture">
        <div class="row1">
            <video
                ref="videoEl"
                autoplay
                playsinline
                muted
                class="video-preview"
                v-show="scanning"
            ></video>
            <div class="controls">
                <!-- camera chooser + refresh -->
                <select
                    v-if="devices.length"
                    v-model="selectedDeviceId"
                    class="camselect"
                    title="Choose camera"
                    @change="() => { if (scanning) { stop(); start(); } }"
                >
                    <option v-for="d in devices" :key="d.deviceId" :value="d.deviceId">
                        {{ d.label || 'Camera' }}
                    </option>
                </select>
                <button class="btn" @click="listCameras" v-if="devices.length">Refresh</button>

                <button class="btn" v-if="!scanning" @click="start">Start camera</button>
                <button class="btn" v-else @click="stop">Stop</button>
                <button class="btn ghost" @click="$emit('cancel')">Cancel</button>
            </div>
        </div>

        <div class="progress">
            <div class="label">
                <b>Collected:</b>
                <template v-if="total">{{ lines.length }}/{{ total }}</template>
                <template v-else>{{ lines.length }}</template>
                <span v-if="code">· <span class="mono">{{ code }}</span></span>
            </div>
            <ul v-if="problems.length" class="issues">
                <li v-for="(m,i) in problems" :key="i">{{ m }}</li>
            </ul>
        </div>

        <div v-if="props.allowManual" class="manual">
            <input
                v-model="manual"
                placeholder='Paste "ER|v1|CODE|i/N|…", then Add'
                @keydown.enter.prevent="addManual"
            />
            <button class="btn" @click="addManual">Add</button>
        </div>

        <ol class="lines mono" v-if="lines.length">
            <li v-for="l in lines" :key="l">{{ l }}</li>
        </ol>
    </div>
</template>

<style scoped>
.capture { display:flex; flex-direction:column; gap:10px; }
.row1 { display:flex; align-items:flex-start; gap:10px; }
.video-preview { width: 320px; height: auto; border:1px solid #ccc; border-radius:6px; background:#000; }
.controls { display:flex; gap:8px; flex-wrap:wrap; }
.camselect { padding:6px 8px; border:1px solid #d1d5db; border-radius:6px; background:#fff; max-width:280px; }
.btn { padding:6px 10px; border:1px solid #d1d5db; border-radius:6px; background:#fff; }
.btn.ghost { background:#f9fafb; }
.progress .label { font-size:13px; }
.issues { margin:4px 0 0; color:#92400e; font-size:12px; padding-left:18px; }
.manual { display:flex; gap:6px; }
.manual input { flex:1; padding:6px 8px; border:1px solid #d1d5db; border-radius:6px; }
.lines { font-size:11px; max-height:140px; overflow:auto; background:#fafafa; padding:8px; border-radius:6px; }
.mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
</style>
