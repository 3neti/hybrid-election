<script setup lang="ts">
import { ref, computed } from 'vue'
import useScannerSession, { type GetDecodeArgs } from '../composables/useScannerSession'
import { parseIndexTotal } from '../composables/MultiPartTools'

// Parent passes a function that returns current decode args (keeps this component decoupled)
const props = defineProps<{ getDecodeArgs: GetDecodeArgs }>()
const emit = defineEmits<{
    (e: 'decoded', payload: any): void
}>()

const paste = ref('')
const sess = useScannerSession(props.getDecodeArgs)

const sortedLines = computed(() => {
    // If i/n available, sort by i; else retain insertion order
    return [...sess.lines.value].sort((a, b) => {
        const pa = parseIndexTotal(a)
        const pb = parseIndexTotal(b)
        if (pa && pb) return pa.i - pb.i
        return 0
    })
})

async function onDecode() {
    const res = await sess.decodeNow()
    if (res?.complete && res?.payload) {
        emit('decoded', res.payload)
    }
}

function short(line: string) {
    if (line.length <= 64) return line
    return `${line.slice(0, 28)}…${line.slice(-28)}`
}
</script>

<template>
    <div class="border rounded p-3 space-y-3">
        <div class="flex items-center justify-between">
            <div class="font-semibold">Scanner (manual paste)</div>
            <div class="text-xs text-gray-500">Status: {{ sess.loading ? 'Decoding…' : 'Idle' }}</div>
        </div>

        <div class="grid gap-2 md:grid-cols-2">
      <textarea
          v-model="paste"
          class="w-full border rounded p-2 h-24 font-mono text-xs"
          placeholder="Paste one or more TRUTH lines or URLs (newline-separated)…"
      />
            <div class="flex items-start gap-2">
                <button
                    class="px-3 py-2 rounded bg-gray-800 text-white"
                    @click="sess.addMany(paste); paste=''"
                >
                    Add
                </button>
                <button class="px-3 py-2 rounded bg-gray-200" @click="sess.clear">Reset</button>
                <button class="px-3 py-2 rounded bg-gray-200" @click="sess.simulateMissing">Simulate Missing</button>
                <button
                    class="px-3 py-2 rounded bg-black text-white ml-auto"
                    :disabled="!sess.lines.value.length || sess.loading"
                    @click="onDecode"
                >
                    Decode
                </button>
            </div>
        </div>

        <div v-if="sess.error" class="p-2 text-sm bg-red-50 border border-red-200 text-red-700 rounded">
            {{ sess.error }}
        </div>

        <!-- Progress HUD -->
        <div class="text-xs text-gray-700">
            <div class="flex flex-wrap items-center gap-3">
                <span>Code: <span class="font-mono">{{ sess.status.value.code || '—' }}</span></span>
                <span>Total: {{ sess.status.value.total }}</span>
                <span>Received: {{ sess.status.value.received }}</span>
                <span>Missing:
          <span v-if="sess.status.value.missing.length" class="font-mono">
            {{ sess.status.value.missing.join(', ') }}
          </span>
          <span v-else>—</span>
        </span>
                <span>Complete: <strong>{{ sess.status.value.complete ? 'Yes' : 'No' }}</strong></span>
            </div>
        </div>

        <!-- Collected lines -->
        <div v-if="sortedLines.length" class="space-y-2">
            <div class="text-sm font-semibold">Captured Chunks ({{ sortedLines.length }})</div>
            <ul class="space-y-1">
                <li
                    v-for="line in sortedLines"
                    :key="line"
                    class="flex items-center gap-2 text-xs"
                >
          <span class="inline-flex px-2 py-0.5 rounded bg-gray-200 text-gray-800">
            {{ parseIndexTotal(line)?.i ?? '?' }}/{{ parseIndexTotal(line)?.n ?? '?' }}
          </span>
                    <span class="font-mono truncate flex-1">{{ short(line) }}</span>
                    <button class="px-2 py-0.5 rounded bg-gray-100" @click="sess.remove(line)">Remove</button>
                </li>
            </ul>
        </div>
    </div>
</template>
