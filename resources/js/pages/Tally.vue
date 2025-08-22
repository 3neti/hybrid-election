<script setup lang="ts">
import { useElectionReturn } from '@/composables/useElectionReturn'
import ErTallyView from '@/components/ErTallyView.vue'
import { onMounted, onBeforeUnmount } from 'vue'
import { useEchoPublic } from '@laravel/echo-vue'

const PRECINCT_CODE = 'CURRIMAO-001'

// Election Return state (loads immediately from backend)
const { er, loading: erLoading, error: erError, loadFromBackend } = useElectionReturn({
    routeName: 'election-return', // make sure your Ziggy route name matches
    immediate: true,
})

// Debounced refresh
let refreshTimer: number | null = null
function refreshSoon(ms = 250) {
    if (refreshTimer) clearTimeout(refreshTimer)
    refreshTimer = window.setTimeout(() => {
        console.info('[Tally] refreshing ER…')
        loadFromBackend().catch(err => console.error('[Tally] refresh error', err))
        refreshTimer = null
    }, ms) as unknown as number
}

interface BallotSubmittedEvent {
    ballot?: { precinct?: { code?: string } }
}

// Public channel subscription: precinct.{code}
const { listen, stopListening, leaveChannel } = useEchoPublic<BallotSubmittedEvent>(
    `precinct.${PRECINCT_CODE}`,
    '.ballot.submitted',
    (payload) => {
        console.groupCollapsed('[Tally] BallotSubmitted (public)')
        console.log('payload:', payload)

        const pcode = payload?.ballot?.precinct?.code
        if (pcode && pcode !== PRECINCT_CODE) {
            console.warn('[Tally] ignoring event for other precinct:', pcode)
            console.groupEnd()
            return
        }

        refreshSoon(250)
        console.groupEnd()
    }
)

onMounted(() => {
    listen()
})

onBeforeUnmount(() => {
    stopListening()
    leaveChannel(true)
})
</script>

<template>
    <div class="max-w-5xl mx-auto p-6 space-y-6">
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Precinct Tally — {{ PRECINCT_CODE }}</h1>
            <button class="px-3 py-1 rounded bg-slate-700 text-white" :disabled="erLoading" @click="loadFromBackend">
                {{ erLoading ? 'Loading…' : 'Refresh' }}
            </button>
        </header>

        <p v-if="erError" class="text-sm text-red-600">Error: {{ erError }}</p>

        <section v-if="er" class="border rounded p-4">
            <ErTallyView :er="er" />
        </section>
        <p v-else class="text-sm text-gray-600">Loading election return…</p>
    </div>
</template>
