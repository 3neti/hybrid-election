<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import ErQrCapture from '@/components/ErQrCapture.vue'
import ErTallyView from '@/components/ErTallyView.vue'
import { useChunkAssembler } from '@/composables/useChunkAssembler'
import { useElectionReturn } from '@/composables/useElectionReturn'

// ─────────────────────────────────────────────────────────────
// Central ER state (same path as Sandbox: setFromJson -> er)
const {
    er,
    error: erError,
    ready,
    sourceMode,
    setFromJson,
    reset: resetEr,
} = useElectionReturn({
    immediate: false, // we only set from assembled JSON
})

// ─────────────────────────────────────────────────────────────
// Chunk assembly (unchanged composable)
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
    version,
    jsonText,
    jsonObject,
} = useChunkAssembler()

// ─────────────────────────────────────────────────────────────
// Ingest: camera → lines → assembler
const showScanner = ref(false)
function onScannerLines(lines: string[]) {
    if (!Array.isArray(lines) || lines.length === 0) return
    addMany(lines)
}
function onScannerResolvedEr() {
    // close scanner, assembly will finish and watcher below will set ER
    showScanner.value = false
}

// Manual single-line helper
const oneLine = ref('')
function addOne() {
    const v = oneLine.value.trim()
    if (!v) return
    addChunkLine(v)
    oneLine.value = ''
}

// ─────────────────────────────────────────────────────────────
// Pipeline: when jsonObject becomes available, set ER
watch(jsonObject, (val) => {
    try {
        if (!val) return
        setFromJson(val) // ⬅️ same validation/flow as Sandbox.vue
        // Optional: you can log here to confirm handoff
        console.info('[Truth] setFromJson OK; keys:', Object.keys(val || {}))
    } catch (e) {
        console.error('[Truth] setFromJson failed:', e)
    }
})

// Derived status
const statusLabel = computed(() => {
    const t = total.value || 0
    const have = receivedIndices.value?.length || 0
    return `Status:${assembling.value ? 'assembling' : 'idle'} · Collected ${have} / ${t} chunk(s)`
})
const received = computed(() => (receivedIndices.value || []).join(', ') || '—')
const missing = computed(() => (missingIndices.value || []).join(', ') || 'none')

function hardReset() {
    resetChunks()
    resetEr()
    oneLine.value = ''
}
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 space-y-6">
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">TRUTH — QR Assembly &amp; ER Viewer</h1>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1 rounded bg-slate-700 text-white" @click="hardReset">Reset</button>
            </div>
        </header>

        <!-- STATUS / DEBUG -->
        <section class="text-sm space-y-1">
            <div>{{ statusLabel }}</div>
            <div>
                Total: <b>{{ total || 0 }}</b> ·
                Received indices: <b>{{ received }}</b> ·
                Missing: <b>{{ missing }}</b> ·
                ER ready: <b>{{ ready ? 'yes' : 'no' }}</b> ·
                sourceMode: <b>{{ sourceMode }}</b>
            </div>

            <details class="mt-2">
                <summary class="cursor-pointer text-slate-700">Debug</summary>
                <div class="mt-1 text-xs">
                    <div>has jsonObject: <b>{{ jsonObject ? 'yes' : 'no' }}</b></div>
                    <div>jsonObject typeof: <b>{{ typeof jsonObject }}</b></div>
                    <div>jsonObject keys: <b>{{ jsonObject ? Object.keys(jsonObject).join(', ') : '—' }}</b></div>
                    <div>jsonText length: <b>{{ (jsonText || '').length }}</b></div>
                    <div v-if="assembleError" class="text-red-600 mt-1">assembleError: {{ assembleError }}</div>
                    <div v-if="erError" class="text-red-600 mt-1">erError: {{ erError }}</div>
                </div>
            </details>
        </section>

        <!-- CAMERA / CAPTURE -->
        <section class="space-y-3">
            <div class="flex items-center gap-2">
                <button class="px-3 py-2 rounded bg-emerald-600 text-white" @click="showScanner = true">Start camera</button>
                <span v-if="code || version" class="text-sm text-slate-600">
          Collected: <b>{{ (receivedIndices || []).length }}/{{ total || '—' }}</b> ·
          <span v-if="code"> {{ code }}</span>
        </span>
            </div>

            <div v-if="showScanner" class="border rounded p-3">
                <ErQrCapture
                    @update:chunks="onScannerLines"
                    @resolved-er="onScannerResolvedEr"
                    @cancel="showScanner = false"
                />
            </div>
        </section>

        <!-- Manual paste (one-line) -->
        <section class="space-y-2">
            <label class="text-xs text-slate-600">Paste "ER|v1|CODE|i/N|…", then Add</label>
            <div class="flex gap-2">
                <input
                    v-model="oneLine"
                    type="text"
                    class="flex-1 border rounded px-3 py-2 font-mono text-xs"
                    @keyup.enter="addOne"
                />
                <button class="px-3 py-2 rounded bg-slate-700 text-white" @click="addOne">Add</button>
            </div>
        </section>

        <!-- ER VIEW -->
        <section v-if="er" class="border rounded p-4">
            <ErTallyView :er="er" title="Election Return" />
        </section>

        <p v-else class="text-sm text-gray-600">
            Click <b>Start</b>, then scan the ER QR cards. As chunks are ingested, this page will assemble the JSON and render the full Election Return automatically.
        </p>
    </div>
</template>

<style scoped>
/* minimal styles only */
</style>
