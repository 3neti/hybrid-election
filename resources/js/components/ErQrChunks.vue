<script setup lang="ts">
import { computed } from 'vue'
import { copyTextChunk } from '@/composables/useBasicUtils'

interface QrChunk {
    index: number
    png?: string
    text?: string
    png_error?: string
}

const props = withDefaults(defineProps<{
    chunks?: QrChunk[] | null
    title?: string
    /** If true, renders the block even when chunks are empty (rarely needed). */
    showIfEmpty?: boolean
}>(), {
    chunks: () => [],
    title: 'QR Tally',
    showIfEmpty: false
})

const totalChunks = computed(() => props.chunks?.length ?? 0)
const anyPngError = computed(() => (props.chunks ?? []).some(c => !!c.png_error))
const showQrBlock = computed(() => props.showIfEmpty || totalChunks.value > 0)
</script>

<template>
    <section v-if="showQrBlock" class="qr-block">
        <div class="qr-title">{{ title }}</div>

        <div class="qr-grid">
            <div
                v-for="c in chunks"
                :key="c.index"
                class="qr-card"
                :class="{ 'qr-has-error': !c.png }"
            >
                <div class="qr-caption mono">Chunk {{ c.index }} / {{ totalChunks }}</div>

                <template v-if="c.png">
                    <img :src="c.png" class="qr-img" alt="QR chunk" />
                </template>

                <template v-else>
                    <div class="qr-fallback">
                        <div class="warn">PNG not available.</div>
                        <div v-if="c.png_error" class="err mono">{{ c.png_error }}</div>
                        <button class="btn tiny no-print" @click="copyTextChunk(c.text)">Copy text</button>
                    </div>
                </template>
            </div>
        </div>

        <div v-if="anyPngError" class="qr-note">
            Some PNGs could not be generated; you can still copy chunk text.
        </div>
    </section>
</template>

<style scoped>
/* Pick up CSS variables from parent if present; otherwise use sane defaults */
.qr-block { margin-top: 16px; }
.qr-title { font-weight:700; margin:2px 0 8px; }
.qr-grid {
    display: grid;
    grid-template-columns: repeat(var(--qrcols, 3), var(--qrsize, 2.5in));
    gap: var(--qrgap, 0.10in);
    justify-content: start;
}
.qr-card {
    width: var(--qrsize, 2.5in);
    height: calc(var(--qrsize, 2.5in) + 0.35in);
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: flex-start;
    page-break-inside: avoid;
}
.qr-caption {
    flex: 0 0 auto;
    text-align: center;
    font-size: 10px;
    line-height: 1.2;
    padding: 2px 4px;
    color: #4b5563;
    border-bottom: 1px solid #eef2f7;
}
.qr-img {
    flex: 1 1 auto;
    width: 100%;
    height: 100%;
    object-fit: contain;
    image-rendering: pixelated;
}
.qr-fallback { padding:6px; font-size:12px; }
.qr-fallback .warn { color:#92400e; margin-bottom:4px; }
.qr-fallback .err { color:#991b1b; font-size:11px; }
.qr-note { margin-top:6px; font-size:12px; color:#92400e; }

.mono {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
}
.btn { padding:6px 10px; border:1px solid #d1d5db; border-radius:6px; background:#fff; }
.btn.tiny { padding:2px 6px; font-size:11px; }
</style>

<!-- No print CSS here; inherit parent's print rules -->
