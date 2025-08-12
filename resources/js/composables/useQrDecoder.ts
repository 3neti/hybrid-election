import { ref, computed } from 'vue'
import axios from 'axios'

/** Shape returned by your decoder endpoint */
export interface QrDecodeResponse {
    code: string
    version: string
    total: number
    received_indices: number[]
    missing_indices: number[]
    json: Record<string, unknown> | null
    raw_json?: string
    persisted_to?: string
}

/** Internal representation of a chunk we’ve collected */
interface ChunkEntry {
    index: number
    total: number
    code: string
    version: string
    text: string
}

/** Try to parse: ER|v1|<CODE>|<i>/<N>|<payload>  */
function parseChunkLine(line: string): ChunkEntry | null {
    const trimmed = (line || '').trim()
    if (!trimmed) return null
    // split into 5 parts max
    const parts = trimmed.split('|', 5)
    if (parts.length < 5) return null
    const [prefix, version, code, idxTot] = parts
    if (prefix !== 'ER' || !version || !code) return null

    const match = idxTot.match(/^(\d+)\s*\/\s*(\d+)$/)
    if (!match) return null
    const index = Number(match[1])
    const total = Number(match[2])
    if (!Number.isFinite(index) || !Number.isFinite(total) || index < 1 || total < 1) return null

    return { index, total, code, version, text: trimmed }
}

/**
 * Composable for collecting QR chunk texts and submitting to the backend decoder.
 */
export function useQrDecoder() {
    // state
    const chunks = ref<Map<number, ChunkEntry>>(new Map())
    const code = ref<string | null>(null)
    const version = ref<string | null>(null)
    const total = ref<number | null>(null)

    const error = ref<string | null>(null)
    const busy = ref(false)
    const lastResponse = ref<QrDecodeResponse | null>(null)

    // derived
    const receivedIndices = computed(() => Array.from(chunks.value.keys()).sort((a, b) => a - b))
    const receivedCount = computed(() => chunks.value.size)
    const missingIndices = computed(() => {
        if (!total.value) return []
        const rec = new Set(receivedIndices.value)
        return Array.from({ length: total.value }, (_, i) => i + 1).filter(i => !rec.has(i))
    })
    const readyToSubmit = computed(() => total.value != null && receivedCount.value > 0)

    /** Reset everything */
    function reset() {
        chunks.value.clear()
        code.value = null
        version.value = null
        total.value = null
        error.value = null
        lastResponse.value = null
    }

    /**
     * Add a single chunk line (paste one QR’s text).
     * Returns true if accepted; false if rejected (and sets `error`).
     */
    function addChunk(line: string): boolean {
        error.value = null
        const parsed = parseChunkLine(line)
        if (!parsed) {
            error.value = 'Invalid chunk format. Expect: ER|v1|CODE|i/N|<payload>'
            return false
        }

        // First chunk establishes session metadata
        if (code.value == null) code.value = parsed.code
        if (version.value == null) version.value = parsed.version
        if (total.value == null) total.value = parsed.total

        // Consistency checks
        if (parsed.code !== code.value) {
            error.value = `Mismatched code: got ${parsed.code}, expected ${code.value}`
            return false
        }
        if (parsed.version !== version.value) {
            error.value = `Mismatched version: got ${parsed.version}, expected ${version.value}`
            return false
        }
        if (parsed.total !== total.value) {
            error.value = `Mismatched total: got ${parsed.total}, expected ${total.value}`
            return false
        }

        // De-duplicate by index; replace if different text
        const existing = chunks.value.get(parsed.index)
        if (existing && existing.text === parsed.text) {
            // silently accept duplicate paste of same text
            return true
        }
        chunks.value.set(parsed.index, parsed)
        return true
    }

    /**
     * Bulk add from a text blob (one per line). Skips blanks. Stops on first hard error.
     * Returns an object with counts.
     */
    function addMany(blob: string) {
        const lines = (blob || '').split(/\r?\n/).map(s => s.trim()).filter(Boolean)
        let accepted = 0
        for (const l of lines) {
            const ok = addChunk(l)
            if (!ok) break
            accepted++
        }
        return { accepted, total: lines.length }
    }

    /**
     * Submit collected chunks to the backend decoder.
     * If `persist` is true, the server will archive inputs/output for audit.
     */
    async function submitToServer(persist = false, persistDir = 'manual_ui') {
        error.value = null
        busy.value = true
        lastResponse.value = null
        try {
            const lines = receivedIndices.value.map(i => chunks.value.get(i)!.text)
            const { data } = await axios.post<QrDecodeResponse>(route('qr.decode'), {
                chunks: lines,
                persist,
                persist_dir: persistDir,
            })
            lastResponse.value = data
            // Update any missing/received info from server (authoritative)
            if (data.total && total.value == null) total.value = data.total
            return data
        } catch (e: any) {
            const msg = e?.response?.data?.message ?? e?.message ?? 'Submit failed'
            error.value = String(msg)
            throw e
        } finally {
            busy.value = false
        }
    }

    return {
        // state
        code,
        version,
        total,
        chunks,
        error,
        busy,
        lastResponse,

        // derived
        receivedIndices,
        receivedCount,
        missingIndices,
        readyToSubmit,

        // actions
        reset,
        addChunk,
        addMany,
        submitToServer,
    }
}
