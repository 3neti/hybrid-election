<template>
    <div class="tally-marks" :title="count.toString()">
        <div v-for="group in fullGroups" :key="'group-' + group" class="tally-group">
            <span class="mark">|</span>
            <span class="mark">|</span>
            <span class="mark">|</span>
            <span class="mark">|</span>
            <span class="diagonal" />
        </div>

        <div v-if="remainingMarks > 0" class="tally-group">
      <span
          v-for="mark in remainingMarks"
          :key="'mark-' + mark"
          class="mark"
      >|</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
    count: number
}>()

const fullGroups = computed(() => Math.floor(props.count / 5))
const remainingMarks = computed(() => props.count % 5)
</script>

<style scoped>
.tally-marks {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.tally-group {
    position: relative;
    display: flex;
    align-items: center;
    gap: 2px;
}

.mark {
    font-size: 1.2rem;
    font-weight: bold;
    font-family: monospace;
    line-height: 1;
}

.diagonal {
    position: absolute;
    top: 1px;
    left: 1px;
    width: 52px;
    height: 2px;
    background-color: black;
    transform: rotate(21deg);
    transform-origin: left center;
    pointer-events: none;
}
</style>
