<script setup lang="ts">

import { useChunkAssembler } from '@/composables/useChunkAssembler'
import { useElectionReturn } from '@/composables/useElectionReturn'
import ErQrCapture from '@/components/ErQrCapture.vue'
import ErTallyView from '@/components/ErTallyView.vue'
import { computed, ref, watch } from 'vue'

const {
    er,
    loading: erLoading,
    error: erError,
    setFromJson,
    reset: resetEr,
    sourceMode,
} = useElectionReturn({ immediate: false })

const {
    chunks,
    assembling,
    assembleError,
    addChunkLine,
    addMany,
    reset: resetChunks,
    total,
    receivedIndices,
    missingIndices,
    code,
    jsonText,
    jsonObject,
} = useChunkAssembler()

// bridge assembler -> ER state
watch(jsonObject, (val) => {
    if (!val) return
    try { setFromJson(val) } catch { /* composable handles error */ }
})

// controls
const showScanner = ref(false)
const pasteOne = ref('')

// derived UI
const collectedCount = computed(() => receivedIndices.value.length)
const totalCount = computed(() => total.value || 0)
const erReady = computed(() => !!er.value)

// ✅ Hide “Missing” if ER is ready OR when we already have all chunks
const missingLabel = computed(() => {
    if (jsonObject.value) return 'none' // already assembled
    const tot = total.value || 0
    if (tot > 0 && receivedIndices.value.length === tot) return 'none' // all collected
    const miss = missingIndices.value
    return (!miss || miss.length === 0) ? 'none' : miss.join(', ')
})

const statusLabel = computed(() => {
    const s = assembling.value ? 'assembling' : 'idle'
    return `Status:${s} · Collected ${collectedCount.value} / ${totalCount.value || '—'}`
})

// helpers
function onPasteAdd() {
    const t = pasteOne.value?.trim()
    if (!t) return
    addChunkLine(t)
    pasteOne.value = ''
}
function onBulkLines(lines: string[]) {
    if (!Array.isArray(lines)) return
    addMany(lines)
}
function startCamera() { showScanner.value = true }
function stopCamera() { showScanner.value = false }
function hardReset() { resetChunks(); resetEr() }
</script>

<template>
    <div class="max-w-5xl mx-auto p-6 space-y-6">
        <header class="flex items-center justify-between gap-4">
            <h1 class="text-xl font-semibold">TRUTH — Reconstruct Election Return</h1>
            <div class="flex items-center gap-2 text-xs">
                <span class="px-2 py-1 rounded bg-slate-100 border">{{ statusLabel }}</span>
                <span v-if="code" class="px-2 py-1 rounded bg-slate-100 border">Code: {{ code }}</span>
                <span class="px-2 py-1 rounded bg-slate-100 border">ER ready: {{ erReady ? 'yes' : 'no' }}</span>
            </div>
        </header>

        <section v-if="er" class="border rounded p-4">
            <ErTallyView :er="er" title="Election Return" />
        </section>
        <p v-else class="text-sm text-gray-600">
            Click <b>Start camera</b>, then scan the ER QR cards. As chunks are ingested, this page will assemble the JSON and render the full Election Return automatically.
        </p>

        <section class="rounded border p-4 space-y-3">
            <div class="flex items-center gap-2">
                <button class="px-3 py-2 rounded bg-emerald-600 text-white" @click="startCamera">Start camera</button>
                <button class="px-3 py-2 rounded bg-slate-600 text-white" @click="stopCamera">Cancel</button>
                <button class="px-3 py-2 rounded bg-slate-700 text-white" :disabled="erLoading" @click="hardReset">
                    {{ erLoading ? 'Resetting…' : 'Reset' }}
                </button>
            </div>

            <ErQrCapture
                v-if="showScanner"
                @update:chunks="onBulkLines"
                @resolved-er="stopCamera"
                @cancel="stopCamera"
            />

            <!-- Quick paste (one line) -->
            <div class="flex items-start gap-2">
                <input
                    v-model="pasteOne"
                    type="text"
                    class="flex-1 border rounded px-3 py-2 font-mono text-xs"
                    placeholder='Paste "ER|v1|CODE|i/N|…", then Add'
                    @keyup.enter="onPasteAdd"
                />
                <button class="px-3 py-2 rounded bg-indigo-600 text-white" @click="onPasteAdd">Add</button>
            </div>

            <!-- Single, authoritative progress line -->
            <div class="text-sm text-gray-700">
                Collected: {{ collectedCount }}/{{ totalCount }} ·
                <span class="font-mono">{{ code || '—' }}</span>
                · Missing: {{ missingLabel }}
            </div>

            <p v-if="assembleError" class="text-sm text-red-600">Assemble error: {{ assembleError }}</p>
            <p v-if="erError" class="text-sm text-red-600">ER error: {{ erError }}</p>
        </section>
    </div>
</template>
