import { ref, reactive, computed, onBeforeUnmount } from 'vue'
import { inflateRaw } from 'pako'

export type QrChunkItem = {
    id: string
    index?: number | null
    total?: number | null
    text: string
    status: 'pending' | 'parsed' | 'invalid'
    error?: string | null
}

type ParsedHeader = { code: string; index: number; total: number; payload: string }

const PAYLOAD_RE = /^[A-Za-z0-9_-]+$/ // strict Base64URL

function b64urlDecodeBytes(input: string): Uint8Array {
    // Base64URL → Base64
    input = input.replace(/-/g, '+').replace(/_/g, '/')
    const pad = input.length % 4
    if (pad) input += '='.repeat(4 - pad)
    const binStr = atob(input)
    const out = new Uint8Array(binStr.length)
    for (let i = 0; i < binStr.length; i++) out[i] = binStr.charCodeAt(i)
    return out
}

function parseHeader(line: string): ParsedHeader | null {
    const parts = line.split('|', 5)
    if (parts.length < 5 || parts[0] !== 'ER' || parts[1] !== 'v1') return null
    const code = parts[2]
    const [idxStr, totalStr] = parts[3].split('/')
    const index = parseInt(idxStr, 10)
    const total = parseInt(totalStr, 10)
    const payload = parts[4]
    if (!index || !total || !payload) return null
    return { code, index, total, payload }
}

export function useChunkAssembler() {
    // visual list (history) + internal assembly buffers
    const chunks = reactive<QrChunkItem[]>([])
    const byIndex = reactive<Map<number, string>>(new Map())
    const total = ref<number | null>(null)
    const code = ref<string | null>(null)
    const version = ref<'v1' | null>(null)

    const assembleError = ref<string | null>(null)
    const assembling = ref(false)

    const seenLines = reactive<Set<string>>(new Set())

    const receivedIndices = computed(() => {
        const arr = Array.from(byIndex.keys())
        arr.sort((a, b) => a - b)
        return arr
    })
    const missingIndices = computed(() => {
        if (!total.value) return []
        const miss: number[] = []
        for (let i = 1; i <= total.value; i++) if (!byIndex.has(i)) miss.push(i)
        return miss
    })
    const isComplete = computed(() => total.value != null && missingIndices.value.length === 0)

    // Result
    const jsonText = ref<string | null>(null)
    const jsonObject = ref<any | null>(null)

    // debounce
    let t: ReturnType<typeof setTimeout> | undefined
    function scheduleAssemble() {
        if (t) clearTimeout(t)
        t = setTimeout(tryAssemble, 80)
    }
    onBeforeUnmount(() => { if (t) clearTimeout(t) })

    function nextId() { return Math.random().toString(36).slice(2) }

    function addChunkLine(raw: string) {
        const line = (raw || '').trim()
        if (!line) return

        // ignore exact duplicates early
        if (seenLines.has(line)) return
        seenLines.add(line)

        const item: QrChunkItem = { id: nextId(), text: line, status: 'pending', error: null }
        chunks.push(item)

        const parsed = parseHeader(line)
        if (!parsed) {
            item.status = 'invalid'
            item.error = 'Invalid header format.'
            return
        }

        // Base64URL strictness
        if (!PAYLOAD_RE.test(parsed.payload)) {
            item.status = 'invalid'
            item.error = `Chunk #${parsed.index} contains non-Base64URL characters.`
            return
        }

        // First line sets these; others must match
        code.value ??= parsed.code
        version.value ??= 'v1'
        total.value ??= parsed.total

        if (parsed.code !== code.value) {
            item.status = 'invalid'
            item.error = `Mismatched code '${parsed.code}' (expected '${code.value}').`
            return
        }
        if (parsed.total !== total.value) {
            item.status = 'invalid'
            item.error = `Mismatched total ${parsed.total} (expected ${total.value}).`
            return
        }
        if (parsed.index < 1 || parsed.index > (total.value ?? 0)) {
            item.status = 'invalid'
            item.error = `Index ${parsed.index} out of range.`
            return
        }

        // Duplicate index handling
        const existing = byIndex.get(parsed.index)
        if (existing && existing !== parsed.payload) {
            item.status = 'invalid'
            item.error = `Duplicate chunk #${parsed.index} has conflicting payload.`
            return
        }

        byIndex.set(parsed.index, parsed.payload)
        item.status = 'parsed'
        item.index = parsed.index
        item.total = parsed.total

        scheduleAssemble()
    }

    function addMany(lines: string[]) {
        for (const l of lines) addChunkLine(l)
    }

    function tryAssemble() {
        assembleError.value = null
        jsonText.value = null
        jsonObject.value = null

        if (!total.value) return
        if (byIndex.size !== total.value) return

        assembling.value = true
        try {
            const joined = Array.from({ length: total.value }, (_, i) => byIndex.get(i + 1) ?? '').join('')
            const inflated = inflateRaw(b64urlDecodeBytes(joined), { to: 'string' }) as unknown as string
            jsonText.value = inflated
            jsonObject.value = JSON.parse(inflated)
        } catch (e: any) {
            assembleError.value = e?.message || String(e)
        } finally {
            assembling.value = false
        }
    }

    function reset() {
        chunks.splice(0, chunks.length)
        byIndex.clear()
        total.value = null
        code.value = null
        version.value = null
        assembleError.value = null
        assembling.value = false
        jsonText.value = null
        jsonObject.value = null
        seenLines.clear()
    }

    return {
        // state
        chunks,
        total,
        code,
        version,
        receivedIndices,
        missingIndices,
        isComplete,
        assembling,
        assembleError,
        jsonText,
        jsonObject,
        // actions
        addChunkLine,
        addMany,
        tryAssemble,
        reset,
    }
}
