<script setup lang="ts">
import { ref } from 'vue'

const active = ref<boolean>(false)
const lastScan = ref<string>('')

function start() { active.value = true /* start camera impl */ }
function stop()  { active.value = false /* stop camera impl */ }

defineExpose({ start, stop })
</script>

<template>
    <div class="border rounded p-3 space-y-2">
        <div class="flex items-center gap-2">
            <button class="px-3 py-1 rounded bg-gray-800 text-white" @click="start" :disabled="active">Start</button>
            <button class="px-3 py-1 rounded bg-gray-200" @click="stop" :disabled="!active">Stop</button>
            <span class="text-sm text-gray-600">Status: {{ active ? 'Scanning…' : 'Idle' }}</span>
        </div>
        <div class="bg-black/80 text-green-400 font-mono text-xs p-2 rounded h-24 overflow-auto">
            {{ lastScan || '—' }}
        </div>
        <!-- Mount your actual camera/decoder here -->
    </div>
</template>
