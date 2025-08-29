<script setup lang="ts">
import { ref } from 'vue'
import useTruthQr from '../composables/useTruthQr'
import { downloadText, downloadDataUrl } from '../composables/download'
import { parseIndexTotal } from '../composables/MultiPartTools'

type AnyObject = Record<string, any>

const form = ref({
    payload: { type: 'demo', code: 'DEMO-001', data: { hello: 'world' } } as AnyObject,
    envelope: 'v1url',
    prefix: 'TRUTH',    // default visible
    version: 'v1',
    transport: 'base64url+deflate',
    serializer: 'json',
    by: 'size' as 'size' | 'count',
    size: 120,
    count: 3,

    // NEW: simple writer controls
    include_qr: false,
    writer: 'none' as 'none' | 'bacon' | 'endroid',
    writerFmt: 'svg' as 'svg' | 'png' | 'eps',
    writerSize: 256,
    writerMargin: 16,
})

const rawPayload = ref(JSON.stringify(form.value.payload, null, 2))
const payloadError = ref<string | null>(null)

function onPayloadInput(e: Event) {
    const txt = (e.target as HTMLTextAreaElement).value
    rawPayload.value = txt
    try {
        const parsed = JSON.parse(txt)
        form.value.payload = parsed
        payloadError.value = null
    } catch (err: any) {
        payloadError.value = 'Invalid JSON'
    }
}

const { encode, decode, encodeResult, decodeResult, loading, error } = useTruthQr()

// NEW: build writer spec string only when enabled
function buildWriterSpec(): string {
    if (!form.value.include_qr || form.value.writer === 'none') return ''
    const fmt    = form.value.writerFmt
    const size   = form.value.writerSize
    const margin = form.value.writerMargin
    return `${form.value.writer}(${fmt},size=${size},margin=${margin})`
}

const onEncode = async () => {
    if (payloadError.value) return
    await encode({
        payload: form.value.payload,
        code: form.value.payload?.code,
        envelope: form.value.envelope as any,
        prefix: form.value.prefix,
        version: form.value.version,
        transport: form.value.transport,
        serializer: form.value.serializer,
        by: form.value.by,
        size: form.value.size,
        count: form.value.count,

        // NEW: writer + include_qr
        include_qr: form.value.include_qr,
        writer: buildWriterSpec(),
    })
}

const onDecode = async () => {
    const lines: string[] =
        encodeResult.value?.urls ??
        encodeResult.value?.lines ??
        []
    if (!lines.length) return
    await decode({
        lines,
        envelope: form.value.envelope as any,
        prefix: form.value.prefix,
        version: form.value.version,
        transport: form.value.transport,
        serializer: form.value.serializer,
    })
}

function listItems(): string[] {
    if (!encodeResult.value) return []
    if (Array.isArray(encodeResult.value.urls)) return encodeResult.value.urls as string[]
    if (Array.isArray(encodeResult.value.lines)) return encodeResult.value.lines as string[]
    return []
}

function downloadAll() {
    const items = listItems()
    if (!items.length) return
    const content = items.join('\n')
    downloadText(`${form.value.payload?.code ?? 'truth'}-chunks.txt`, content)
}

function downloadQrSvgs() {
    const qr = encodeResult.value?.qr
    if (!qr || typeof qr !== 'object') return

    const items = Object.values(qr) as string[]
    items.forEach((data, idx) => {
        const baseName = `${form.value.payload?.code ?? 'truth'}-qr-${idx + 1}`
        if (typeof data === 'string') {
            const trimmed = data.trim()
            if (trimmed.startsWith('<?xml')) {
                downloadText(`${baseName}.svg`, data)
            } else if (trimmed.startsWith('data:image/png')) {
                downloadDataUrl(`${baseName}.png`, data)
            }
        }
    })
}
</script>

<template>
<div class="space-y-6">
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
<div>
    <label class="block text-sm font-medium">Envelope</label>
    <select v-model="form.envelope" class="w-full border rounded p-2">
<option value="v1url">v1url</option>
    <option value="v1line">v1line</option>
</select>
</div>

<div>
<label class="block text-sm font-medium">Prefix</label>
    <input v-model="form.prefix" class="w-full border rounded p-2" placeholder="TRUTH / ER / …" />
</div>

<div>
<label class="block text-sm font-medium">Version</label>
    <input v-model="form.version" class="w-full border rounded p-2" placeholder="v1 / v2 / …" />
</div>

<div>
<label class="block text-sm font-medium">Transport</label>
    <select v-model="form.transport" class="w-full border rounded p-2">
<option value="base64url+deflate">base64url+deflate</option>
    <option value="base64url+gzip">base64url+gzip</option>
    <option value="base64url">base64url</option>
</select>
</div>

<div>
<label class="block text-sm font-medium">Serializer</label>
    <select v-model="form.serializer" class="w-full border rounded p-2">
<option value="json">json</option>
    <option value="yaml">yaml</option>
    <option value="auto">auto</option>
</select>
</div>

<div>
<label class="block text-sm font-medium">Chunking</label>
    <div class="flex gap-2">
<select v-model="form.by" class="border rounded p-2">
<option value="size">by size</option>
<option value="count">by count</option>
</select>
<input v-if="form.by==='size'"
    v-model.number="form.size"
type="number"
min="1"
class="w-full border rounded p-2"
placeholder="size" />
    <input v-else
v-model.number="form.count"
type="number"
min="1"
class="w-full border rounded p-2"
placeholder="count" />
    </div>
    </div>
    </div>

    <!-- NEW: Writer controls -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
<div class="flex items-center gap-2">
<input id="incqr" type="checkbox" v-model="form.include_qr" />
    <label for="incqr" class="text-sm font-medium">Include QR images</label>
</div>

<div>
<label class="block text-sm font-medium">Writer</label>
    <select v-model="form.writer" class="w-full border rounded p-2" :disabled="!form.include_qr">
<option value="none">none</option>
    <option value="bacon">bacon</option>
    <option value="endroid">endroid</option>
</select>
</div>

<div>
<label class="block text-sm font-medium">Format</label>
    <select v-model="form.writerFmt" class="w-full border rounded p-2" :disabled="!form.include_qr || form.writer==='none'">
<option value="svg">svg</option>
    <option value="png">png</option>
    <option value="eps" :disabled="form.writer!=='bacon'">eps</option>
</select>
</div>

<div>
<label class="block text-sm font-medium">Size</label>
    <input v-model.number="form.writerSize"
type="number"
min="64"
class="w-full border rounded p-2"
:disabled="!form.include_qr || form.writer==='none'"/>
</div>

<div>
<label class="block text-sm font-medium">Margin</label>
    <input v-model.number="form.writerMargin"
type="number"
min="0"
class="w-full border rounded p-2"
:disabled="!form.include_qr || form.writer==='none'"/>
</div>
</div>

<div>
<label class="block text-sm font-medium">Payload (JSON)</label>
    <textarea
class="w-full border rounded p-2 h-40 font-mono text-sm"
:class="payloadError ? 'border-red-500' : ''"
:value="rawPayload"
@input="onPayloadInput"
    ></textarea>
    <p v-if="payloadError" class="text-xs text-red-600 mt-1">{{ payloadError }}</p>
</div>

<div class="flex gap-3">
<button @click="onEncode" :disabled="loading || !!payloadError" class="px-4 py-2 rounded bg-black text-white">
    {{ loading ? 'Encoding…' : 'Encode' }}
</button>
<button @click="onDecode" :disabled="loading" class="px-4 py-2 rounded bg-gray-200">
    {{ loading ? 'Decoding…' : 'Decode' }}
</button>

<button v-if="encodeResult" @click="downloadAll" class="px-4 py-2 rounded bg-gray-100">
    Download lines
</button>
<button v-if="encodeResult?.qr" @click="downloadQrSvgs" class="px-4 py-2 rounded bg-gray-100">
    Download QR SVGs
</button>
</div>

<div v-if="error" class="p-3 bg-red-50 text-red-700 border border-red-200 rounded">
    {{ error }}
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="p-3 bg-gray-50 border rounded overflow-auto">
<div class="font-semibold mb-2">ENCODE RESULT</div>
<pre class="text-xs">{{ encodeResult }}</pre>

<!-- rendered list -->
<div v-if="encodeResult" class="mt-3 space-y-1">
<div class="font-semibold text-sm">Parts</div>
    <ul class="text-xs space-y-1">
    <li v-for="(line, idx) in listItems()" :key="idx" class="flex items-center gap-2">
<span class="inline-flex px-2 py-0.5 rounded bg-gray-200 text-gray-800">
    {{ parseIndexTotal(line)?.i ?? '?' }}/{{ parseIndexTotal(line)?.n ?? '?' }}
</span>
<span class="truncate">{{ line }}</span>
</li>
</ul>
</div>

<!-- QR previews (SVG-only display for now) -->
<div v-if="encodeResult?.qr" class="mt-3 space-y-2">
<div class="font-semibold text-sm">QR Preview</div>
<div class="grid grid-cols-2 md:grid-cols-3 gap-3">
    <div
        v-for="(val, k) in encodeResult.qr"
    :key="k"
class="border rounded p-2 bg-white"
    >
    <!-- SVG string -->
<div v-if="typeof val === 'string' && val.trim().startsWith('<?xml')" v-html="val" />

    <!-- PNG (or other image) data URL -->
<img
    v-else-if="typeof val === 'string' && val.startsWith('data:image')"
    :src="val"
class="w-full h-auto"
    />

    <!-- Fallback -->
    <div v-else class="text-xs text-gray-500">Unsupported QR format</div>
</div>
</div>
</div>

</div>

<div class="p-3 bg-gray-50 border rounded overflow-auto">
<div class="font-semibold mb-2">DECODE RESULT</div>
<pre class="text-xs">{{ decodeResult }}</pre>
</div>
</div>
</div>
</template>
