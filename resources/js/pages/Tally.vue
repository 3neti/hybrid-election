<script setup lang="ts">
import ErTallyView from '@/components/ErTallyView.vue'
import { useElectionReturn } from '@/composables/useElectionReturn'

/**
 * Configure where to fetch the ER:
 * - If you have a Ziggy route named 'election-return' that returns the latest/only ER, leave as-is.
 * - If your API requires a code param, you can later add a prop or read from the route and
 *   pass it into `routeParams`. For now this loads the default ER.
 * - Alternatively, set `endpoint: '/api/election-return'`.
 */
const {
    er,                 // Ref<ElectionReturnData | null>
    loading: erLoading,
    error: erError,
    loadFromBackend,    // () => Promise<void>
    ready,
    sourceMode,
    startPolling,
    stopPolling,
} = useElectionReturn({
    routeName: 'election-return', // change if your backend route differs
    // routeParams: { code: 'ER-XXXX' }, // optional; add when you want to target a specific ER
    // endpoint: '/api/election-return', // alternative to routeName/routeParams
    immediate: true,                // auto-fetch on mount
    pollMs: Number(import.meta.env.VITE_TALLY_POLL_MS ?? 0), // set >1000 to enable polling
})
</script>

<template>
    <div class="max-w-5xl mx-auto p-6 space-y-4">
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Precinct Tally</h1>
            <div class="flex gap-2">
                <button
                    class="px-3 py-2 rounded bg-slate-700 text-white disabled:opacity-60"
                    :disabled="erLoading"
                    @click="loadFromBackend"
                    title="Refresh from server"
                >
                    {{ erLoading ? 'Loading…' : 'Refresh' }}
                </button>
            </div>
        </header>

        <p v-if="erError" class="text-sm text-red-600">Error: {{ erError }}</p>

        <section v-if="er" class="border rounded p-4">
            <div class="text-sm text-gray-600 mb-2">
                <span>ER: {{ er.code }}</span>
                <span v-if="er.precinct?.code"> · Precinct: {{ er.precinct.code }}</span>
                <span v-if="er.precinct?.location_name"> · {{ er.precinct.location_name }}</span>
                <span v-if="sourceMode"> · source={{ sourceMode }}</span>
            </div>

            <ErTallyView :er="er" />
        </section>

        <section v-else class="text-sm text-gray-500">
            {{ erLoading ? 'Loading tally…' : 'No tally loaded yet.' }}
        </section>
    </div>
</template>

<style scoped>
@media print {
    header, button { display: none; }
    .border { border: none; }
    .p-4 { padding: 0; }
}
</style>
