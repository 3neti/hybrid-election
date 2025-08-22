<script setup lang="ts">
import { ref } from 'vue'
import { useQrDecoder } from '@/composables/useQrDecoder'

const {
    code, version, total,
    receivedIndices, missingIndices, receivedCount,
    addChunk, addMany, submitToServer,
    reset, error, busy, lastResponse,
} = useQrDecoder()

const pasteText = ref('')
function onPasteOne() {
    addChunk(pasteText.value)
    pasteText.value = ''
}
async function onSubmit() {
    await submitToServer(true, 'tally_ui')  // persist = true
}
</script>

<template>
    <div class="space-y-4">
        <div class="text-sm text-gray-600">
            Code: {{ code || '—' }} · Version: {{ version || '—' }} · Total: {{ total || '—' }}
        </div>

        <textarea v-model="pasteText" rows="3" class="w-full border p-2 font-mono"
                  placeholder="Paste one QR text (ER|v1|CODE|i/N|...)"></textarea>
        <div class="flex gap-2">
            <button class="px-3 py-1 bg-blue-600 text-white rounded" @click="onPasteOne">Add chunk</button>
            <button class="px-3 py-1 bg-gray-600 text-white rounded" @click="() => addMany(pasteText)">Add many (lines)</button>
            <button class="px-3 py-1 bg-emerald-600 text-white rounded" :disabled="busy" @click="onSubmit">
                {{ busy ? 'Submitting…' : 'Submit to server' }}
            </button>
            <button class="px-3 py-1 bg-slate-400 text-white rounded" @click="reset">Reset</button>
        </div>

        <div v-if="error" class="text-red-600 text-sm">{{ error }}</div>

        <div class="text-sm">
            Received ({{ receivedCount }}): {{ receivedIndices.join(', ') || '—' }}
            <br>
            Missing: {{ (missingIndices || []).join(', ') || 'none (or unknown)' }}
        </div>

        <pre v-if="lastResponse?.json" class="bg-slate-100 p-3 text-xs overflow-auto">
{{ JSON.stringify(lastResponse.json, null, 2) }}
    </pre>
    </div>
</template>
