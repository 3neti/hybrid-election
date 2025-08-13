<script setup lang="ts">
import { ref, computed } from 'vue'
import { inflateRaw } from 'pako'
import ErTallyView, { type ElectionReturnData } from '@/components/ErTallyView.vue'

/* ---------------- Types ---------------- */
interface QrChunk { index: number; text: string; png?: string }
interface ParsedLine { code: string; index: number; total: number; payload: string }

/* ---------------- State ---------------- */
// A) Direct JSON path
const rawJson = ref<string>('')            // pretty JSON shown/edited by user
const er = ref<ElectionReturnData | null>(null)
const parseError = ref<string | null>(null)

// B) Manual QR-chunk path
const chunkTextInput = ref<string>('')     // paste one full chunk line here
const chunkPngInput  = ref<string>('')     // optional data URI for the same chunk

const chunkMap = ref<Map<number, QrChunk>>(new Map()) // gathered chunks by index
const erCode   = ref<string | null>(null)             // detected from first chunk
const total    = ref<number | null>(null)             // detected from first chunk
const addStatus = ref<string | null>(null)
const assembleStatus = ref<string | null>(null)

/* ---------------- Utils ---------------- */
function parseChunkLine(line: string): ParsedLine | null {
    // ER|v1|<CODE>|<i>/<N>|<payload>
    const m = line.match(/^ER\|v1\|([^|]+)\|(\d+)\/(\d+)\|(.+)$/)
    if (!m) return null
    return { code: m[1], index: +m[2], total: +m[3], payload: m[4] }
}
function b64urlToBytes(s: string): Uint8Array {
    s = s.replace(/-/g, '+').replace(/_/g, '/')
    const pad = s.length % 4
    if (pad) s += '='.repeat(4 - pad)
    const bin = atob(s)
    const out = new Uint8Array(bin.length)
    for (let i = 0; i < bin.length; i++) out[i] = bin.charCodeAt(i)
    return out
}
function pretty(obj: any) {
    return JSON.stringify(obj, null, 2)
}

/* ---------------- Direct JSON path ---------------- */
function parseAndPreview() {
    parseError.value = null
    try {
        const obj = JSON.parse(rawJson.value)
        if (!obj?.precinct?.code || !Array.isArray(obj?.tallies)) {
            throw new Error('JSON does not look like an Election Return payload.')
        }
        er.value = obj
    } catch (e: any) {
        parseError.value = e?.message || String(e)
        er.value = null
    }
}

/* ---------------- Manual chunks path ---------------- */
function addChunkFromInputs() {
    addStatus.value = null
    const raw = (chunkTextInput.value || '').trim()
    if (!raw) {
        addStatus.value = 'Paste a full chunk text first.'
        return
    }
    const parsed = parseChunkLine(raw)
    if (!parsed) {
        addStatus.value = 'Chunk not in expected format: ER|v1|CODE|i/N|<payload>'
        return
    }

    // lock set on first valid chunk
    if (!erCode.value && !total.value) {
        erCode.value = parsed.code
        total.value = parsed.total
    }

    // if a different set shows up, reset to that new set
    if (erCode.value !== parsed.code || total.value !== parsed.total) {
        const old = `${erCode.value ?? '?'} / ${total.value ?? '?'}`
        const neu = `${parsed.code} / ${parsed.total}`
        resetChunksOnly()
        erCode.value = parsed.code
        total.value = parsed.total
        addStatus.value = `Detected new set (${neu}). Cleared previous set (${old}).`
    }

    // store/replace
    chunkMap.value.set(parsed.index, {
        index: parsed.index,
        text: raw,
        ...(chunkPngInput.value.trim().startsWith('data:image/') ? { png: chunkPngInput.value.trim() } : {})
    })

    // clear inputs for next paste
    chunkTextInput.value = ''
    chunkPngInput.value = ''
    addStatus.value = `Added chunk ${parsed.index} of ${parsed.total}.`

    // auto-assemble if complete
    tryAssemble()
}

function removeChunk(i: number) {
    chunkMap.value.delete(i)
    er.value = null // require re-assembly if user wants a preview from chunks
    assembleStatus.value = null
}

function tryAssemble() {
    assembleStatus.value = null
    parseError.value = null
    if (!total.value) return

    // full set present?
    for (let i = 1; i <= total.value; i++) {
        if (!chunkMap.value.has(i)) {
            assembleStatus.value = `Waiting for ${total.value - chunkMap.value.size} chunk(s)…`
            return
        }
    }

    try {
        const joined = Array.from({ length: total.value }, (_, idx) => {
            const c = chunkMap.value.get(idx + 1)!
            return c.text.split('|', 5)[4] ?? ''
        }).join('')

        const inflated = inflateRaw(b64urlToBytes(joined), { to: 'string' })
        const obj = JSON.parse(inflated)
        if (!obj?.precinct?.code || !Array.isArray(obj?.tallies)) {
            throw new Error('Decoded data does not look like an Election Return.')
        }

        // success: update both preview AND the JSON textarea
        er.value = obj
        rawJson.value = pretty(obj)
        assembleStatus.value = `OK — assembled ${total.value} chunk(s).`
    } catch (e: any) {
        er.value = null
        assembleStatus.value = 'Assembly failed.'
        parseError.value = e?.message || String(e)
    }
}

/* ---------------- Shared ---------------- */
function resetAll() {
    // reset JSON side
    rawJson.value = ''
    er.value = null
    parseError.value = null
    // reset chunk side
    resetChunksOnly()
}
function resetChunksOnly() {
    chunkMap.value = new Map()
    chunkTextInput.value = ''
    chunkPngInput.value = ''
    erCode.value = null
    total.value = null
    addStatus.value = null
    assembleStatus.value = null
}

/* ---------------- Derived ---------------- */
const collected = computed(() => chunkMap.value.size)
const totalKnown = computed(() => !!total.value)
const completionPct = computed(() =>
    total.value ? Math.round((collected.value / total.value) * 100) : 0
)
const orderedChunks = computed(() => {
    if (!total.value) return Array.from(chunkMap.value.values()).sort((a,b) => a.index - b.index)
    return Array.from({ length: total.value }, (_, i) => chunkMap.value.get(i + 1) || null)
})
</script>

<template>
    <div class="max-w-6xl mx-auto p-6 space-y-8">
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">QR Tally — Stand‑alone Viewer</h1>
            <div class="flex gap-2">
                <button class="px-3 py-2 rounded bg-gray-200" @click="resetAll">Reset All</button>
            </div>
        </header>

        <!-- A) Direct JSON input -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Decoded ER JSON</label>
                <textarea
                    v-model="rawJson"
                    rows="14"
                    class="w-full border rounded p-3 font-mono text-xs"
                    placeholder='Paste the decoded JSON here (after QR chunk assembly)…'
                ></textarea>

                <div class="flex gap-2">
                    <button class="px-3 py-2 rounded bg-blue-600 text-white" @click="parseAndPreview">
                        Preview
                    </button>
                    <button
                        class="px-3 py-2 rounded bg-gray-200"
                        @click="
              rawJson = pretty({
                id:'SAMPLE-ER-001',
                code:'ERDEMO001',
                precinct:{ id:'P-1', code:'CURRIMAO-001' },
                tallies:[
                  { position_code:'PRESIDENT', candidate_code:'AA', candidate_name:'Alice A.', count:22 },
                  { position_code:'PRESIDENT', candidate_code:'BB', candidate_name:'Benedict B.', count:15 },
                  { position_code:'VP',        candidate_code:'CC', candidate_name:'Carla C.', count:19 },
                ],
                last_ballot:{
                  id:'BAL-999', code:'BAL999',
                  votes:[
                    { position_code:'PRESIDENT', candidate_codes:[{ code:'AA' }] },
                    { position_code:'VP',        candidate_codes:[{ code:'CC' }] },
                  ]
                }
              })
            "
                    >
                        Load Sample JSON
                    </button>
                    <button class="px-3 py-2 rounded bg-gray-200" @click="rawJson = ''">Clear JSON</button>
                </div>

                <p v-if="parseError" class="text-sm text-red-600 mt-2">Error: {{ parseError }}</p>
            </div>

            <!-- B) Manual chunk intake -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Paste ONE QR chunk text</label>
                <textarea
                    v-model="chunkTextInput"
                    rows="5"
                    class="w-full border rounded p-3 font-mono text-xs"
                    placeholder="ER|v1|ERXXXX|1/4|<payload>..."
                ></textarea>

                <label class="block text-xs font-semibold text-gray-600">Optional PNG Data URI for this same chunk</label>
                <textarea
                    v-model="chunkPngInput"
                    rows="3"
                    class="w-full border rounded p-2 font-mono text-[11px]"
                    placeholder="data:image/png;base64,iVBORw0K..."
                ></textarea>

                <div class="flex items-center gap-3">
                    <button
                        class="px-3 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700"
                        @click="addChunkFromInputs"
                    >
                        Add chunk
                    </button>
                    <span class="text-xs" :class="addStatus?.startsWith('Added') ? 'text-emerald-700' : 'text-amber-700'">
            {{ addStatus }}
          </span>
                </div>

                <!-- progress -->
                <div class="text-xs text-gray-600 mt-1">
                    <template v-if="totalKnown">
                        Set: <b class="font-mono">{{ erCode }}</b> — expecting <b>{{ total }}</b> chunk(s).
                    </template>
                    <template v-else>
                        Waiting for the first valid chunk to detect set/code and total…
                    </template>
                </div>
                <div v-if="totalKnown" class="mt-2">
                    <div class="h-2 bg-gray-200 rounded overflow-hidden">
                        <div class="h-full bg-emerald-500 transition-all" :style="{ width: completionPct + '%' }" />
                    </div>
                    <div class="text-xs text-gray-600 mt-1">
                        Collected <b>{{ collected }}</b> / <b>{{ total }}</b> ({{ completionPct }}%)
                    </div>
                </div>

                <p v-if="assembleStatus" class="text-xs mt-2"
                   :class="assembleStatus.startsWith('OK') ? 'text-emerald-700' : 'text-amber-700'">
                    {{ assembleStatus }}
                </p>
            </div>
        </section>

        <!-- Chunks grid -->
        <section class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Chunks in this set</h2>
                <div v-if="totalKnown" class="text-xs text-gray-600">
                    ER Code: <b class="font-mono">{{ erCode }}</b> • Total: <b>{{ total }}</b>
                </div>
            </div>

            <div v-if="orderedChunks.length" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div
                    v-for="(c, idx) in orderedChunks"
                    :key="idx"
                    class="border rounded p-3"
                    :class="c ? 'ring-1 ring-emerald-200' : 'opacity-60'"
                >
                    <div class="flex items-center justify-between text-xs mb-2">
                        <div>Chunk {{ idx + 1 }} / {{ total ?? '?' }}</div>
                        <button
                            v-if="c"
                            class="px-2 py-1 text-[11px] rounded bg-gray-800 text-white hover:bg-black"
                            @click="removeChunk(idx + 1)"
                            title="Remove this chunk"
                        >
                            Remove
                        </button>
                    </div>

                    <template v-if="c">
                        <img v-if="c.png" :src="c.png" alt="QR" class="w-full h-auto rounded mb-2" />
                        <div class="text-[11px] font-mono break-words bg-gray-50 border p-2 rounded">
                            {{ c.text }}
                        </div>
                        <div class="mt-2 flex gap-2">
                            <button
                                class="px-2 py-1 text-[11px] rounded bg-indigo-600 text-white hover:bg-indigo-700"
                                @click="navigator.clipboard?.writeText(c.text)"
                            >
                                Copy text
                            </button>
                            <button
                                v-if="!c.png"
                                class="px-2 py-1 text-[11px] rounded bg-gray-200"
                                @click="chunkPngInput = ''; chunkTextInput = c.text"
                                title="Prefill inputs with this chunk to attach a PNG"
                            >
                                Attach PNG…
                            </button>
                        </div>
                    </template>

                    <template v-else>
                        <div class="text-xs text-gray-500 italic">Missing…</div>
                    </template>
                </div>
            </div>

            <div v-else class="text-xs text-gray-500">
                No chunks yet. Paste one chunk line and click <b>Add chunk</b>.
            </div>
        </section>

        <!-- Preview -->
        <section v-if="er" class="border rounded p-4">
            <!-- Pass the gathered chunks to the viewer (it will show them if it wants) -->
            <ErTallyView :er="er" :qrChunks="[...chunkMap.values()].sort((a,b)=>a.index-b.index)" />
        </section>
        <section v-else class="text-sm text-gray-600">
            Paste **decoded JSON** and click <b>Preview</b> — or — keep adding chunks until all are present; the tally will appear automatically.
        </section>
    </div>
</template>

<style scoped>
/* progress bar styling handled inline */
</style>
