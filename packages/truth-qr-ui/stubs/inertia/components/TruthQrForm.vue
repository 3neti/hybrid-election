<script setup lang="ts">
import { ref } from 'vue'
import useTruthQr from '../composables/useTruthQr'

const form = ref({
    payload: { type: 'demo', code: 'DEMO-001', data: { hello: 'world' } },
    envelope: 'v1url',
    transport: 'base64url+deflate',
    serializer: 'json',
    by: 'size',
    size: 120,
})

const { encode, decode, encodeResult, decodeResult, routes } = useTruthQr()

const onEncode = async () => {
    await encode({
        payload: form.value.payload,
        code: form.value.payload.code,
        envelope: form.value.envelope,
        transport: form.value.transport,
        serializer: form.value.serializer,
        by: form.value.by,
        size: form.value.size,
    })
}

const onDecode = async () => {
    const lines = encodeResult.value?.urls ?? encodeResult.value?.lines ?? []
    await decode({
        lines,
        envelope: form.value.envelope,
        transport: form.value.transport,
        serializer: form.value.serializer,
    })
}
</script>

<template>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Envelope</label>
                <select v-model="form.envelope" class="w-full border rounded p-2">
                    <option value="v1url">v1url</option>
                    <option value="v1line">v1line</option>
                </select>
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
                <label class="block text-sm font-medium">Chunk by size</label>
                <input v-model.number="form.size" type="number" class="w-full border rounded p-2" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Payload (JSON)</label>
            <textarea class="w-full border rounded p-2 h-40"
                      :value="JSON.stringify(form.payload, null, 2)"
                      @input="form.payload = JSON.parse(($event.target as HTMLTextAreaElement).value || '{}')">
      </textarea>
        </div>

        <div class="flex gap-3">
            <button @click="onEncode" class="px-4 py-2 rounded bg-black text-white">Encode</button>
            <button @click="onDecode" class="px-4 py-2 rounded bg-gray-200">Decode</button>
        </div>

        <div class="grid grid-cols-2 gap-6">
      <pre class="p-3 bg-gray-50 border rounded overflow-auto">ENCODE RESULT:
{{ encodeResult }}</pre>
            <pre class="p-3 bg-gray-50 border rounded overflow-auto">DECODE RESULT:
{{ decodeResult }}</pre>
        </div>
    </div>
</template>
