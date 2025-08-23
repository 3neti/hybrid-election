<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import ErQrCapture from '@/components/ErQrCapture.vue'
import ElectionReturn from '@/components/ElectionReturn.vue'
import { useChunkAssembler } from '@/composables/useChunkAssembler'

// ───────────────────────────────
// 1) Single source of truth: QR chunk assembler
// ───────────────────────────────
const {
    chunks,              // reactive array of chunk items (with .status, .index, .total, .text)
    assembling,          // boolean while assembling JSON
    assembleError,       // string | null if something went wrong
    addMany,             // (lines: string[]) => void
    addChunkLine,        // (line: string) => void
    reset: resetAssembler,
    total,               // computed total chunks advertised by the stream (N)
    receivedIndices,     // computed list of received indices
    jsonObject,          // computed: parsed ER object when all chunks are present
} = useChunkAssembler()

// ───────────────────────────────
// 2) Local UI state
// ───────────────────────────────
const scanning = ref(false)       // whether the camera modal is open
const started  = ref(false)       // whether user clicked "Start"
const erReady  = computed(() => !!jsonObject.value)

// UX label: "Collected X / N" (or "X chunk(s)" until N is known)
const progressLabel = computed(() => {
    const have = receivedIndices.value.length
    const tot  = total.value
    return tot ? `Collected ${have} / ${tot} chunk(s)` : `Collected ${have} chunk(s)`
})

// When the scanner emits lines (full “ER|v1|…|i/N|payload” strings), feed them
function onScannerLines(lines: string[]): void {
    if (!Array.isArray(lines)) return
    addMany(lines)
}

// Once assembly is complete, close the scanner automatically
watch(jsonObject, (val) => {
    if (val) scanning.value = false
})

// Controls
function onStart() {
    started.value = true
    scanning.value = true
}
function onStop() {
    scanning.value = false
}
function onReset() {
    started.value = false
    scanning.value = false
    resetAssembler()
}
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 space-y-8">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">TRUTH — QR Reconstruction</h1>

            <div class="flex gap-2">
                <button
                    v-if="!started"
                    class="px-3 py-2 rounded bg-emerald-600 text-white"
                    @click="onStart"
                >
                    Start
                </button>
                <button
                    v-else
                    class="px-3 py-2 rounded bg-slate-600 text-white"
                    @click="onStop"
                >
                    Pause Scanner
                </button>
                <button
                    class="px-3 py-2 rounded bg-gray-400 text-white"
                    @click="onReset"
                >
                    Reset
                </button>
            </div>
        </header>

        <!-- Scanner area -->
        <section
            v-if="started && !erReady"
            class="border rounded p-4 space-y-3"
        >
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    <strong>Status:</strong>
                    <span v-if="assembling">assembling…</span>
                    <span v-else>waiting for chunks…</span>
                </div>
                <div class="text-sm font-medium" :class="{'text-emerald-700': total, 'text-gray-600': !total}">
                    {{ progressLabel }}
                </div>
            </div>

            <ErQrCapture
                v-if="scanning"
                @update:chunks="onScannerLines"
                @resolved-er="() => (scanning = false)"
                @cancel="onStop"
            />

            <p v-if="assembleError" class="text-sm text-red-600">
                Assemble error: {{ assembleError }}
            </p>

            <!-- Live chunk roll (compact) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                <div
                    v-for="c in chunks"
                    :key="c.id"
                    class="p-2 border rounded text-xs space-y-1"
                    :class="{
            'border-emerald-300 bg-emerald-50': c.status==='parsed',
            'border-amber-300 bg-amber-50': c.status==='pending',
            'border-red-300 bg-red-50': c.status==='invalid'
          }"
                >
                    <div class="flex items-center justify-between">
                        <div class="font-mono">
                            <span v-if="c.index">#{{ c.index }}</span>
                            <span v-if="c.total"> / {{ c.total }}</span>
                            <span v-if="!c.index || !c.total" class="text-gray-500">unparsed</span>
                        </div>
                        <div class="text-[11px]">
                            <span v-if="c.status==='parsed'" class="text-emerald-700">parsed</span>
                            <span v-else-if="c.status==='pending'" class="text-amber-700">pending</span>
                            <span v-else class="text-red-700">invalid</span>
                        </div>
                    </div>
                    <div class="font-mono break-all line-clamp-2">{{ c.text || '(no text)' }}</div>
                    <div v-if="c.error" class="text-red-700">{{ c.error }}</div>
                </div>
            </div>
        </section>

        <!-- Election Return (shown once complete) -->
        <section v-if="erReady" class="border rounded p-4">
            <!-- Uses your existing, printable ER component -->
            <ElectionReturn
                :er="jsonObject as any"
                paper="legal"
                :base-pt="10"
                :desired-chunks="8"
                ecc="medium"
                :size="640"
                :margin="12"
                qr-profile="normal"
                :qr-print-size-in="2.5"
                :qr-grid-cols="3"
                :qr-grid-gap-in="0.10"
                :qr-endpoint="(window as any).route ? (window as any).route('qr.er.from_json') : '/api/qr/from-json'"
            />
        </section>

        <section v-else class="text-sm text-gray-600">
            Click <b>Start</b>, then scan the ER QR cards. As chunks are ingested, this page will assemble
            the JSON and render the full Election Return automatically.
        </section>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
