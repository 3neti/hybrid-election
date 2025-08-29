import { ref } from 'vue'
import useTruthQr from './useTruthQr'
import { parseIndexTotal } from './MultiPartTools'

export type GetDecodeArgs = () => {
    envelope?: 'v1url' | 'v1line'
    prefix?: string
    version?: string
    transport?: string
    serializer?: string
}

export default function useScannerSession(getArgs: GetDecodeArgs) {
    const { decode, decodeResult, error, loading } = useTruthQr()

    // unique buffer of lines
    const lines = ref<string[]>([])
    const lineSet = new Set<string>()

    // progress/status snapshot (mirrors backend response)
    const status = ref<{
        code: string
        total: number
        received: number
        missing: number[]
        complete: boolean
    }>({ code: '', total: 0, received: 0, missing: [], complete: false })

    function normalizeLine(raw: string): string {
        return (raw || '').trim()
    }

    function addScan(raw: string) {
        const line = normalizeLine(raw)
        if (!line) return
        if (!lineSet.has(line)) {
            lineSet.add(line)
            lines.value = Array.from(lineSet)
        }
    }

    function addMany(raw: string) {
        // accept pasted multi-line
        raw.split(/\r?\n/).forEach(addScan)
    }

    function remove(line: string) {
        if (lineSet.delete(line)) {
            lines.value = Array.from(lineSet)
        }
    }

    function clear() {
        lineSet.clear()
        lines.value = []
        status.value = { code: '', total: 0, received: 0, missing: [], complete: false }
    }

    async function decodeNow() {
        const args = getArgs()
        const res = await decode({
            lines: lines.value,
            envelope: args.envelope,
            prefix: args.prefix,
            version: args.version,
            transport: args.transport,
            serializer: args.serializer,
        } as any)

        // shape → status
        status.value = {
            code: String(res?.code ?? ''),
            total: Number(res?.total ?? 0),
            received: Number(res?.received ?? 0),
            missing: Array.isArray(res?.missing) ? res?.missing : [],
            complete: Boolean(res?.complete),
        }

        return res
    }

    function simulateMissing() {
        // Drop one "middle" index if we can infer i/n from any line
        if (lines.value.length < 2) return
        // Try to parse any line's i/n to compute middle index
        const samples = lines.value
            .map((ln, idx) => ({ ln, meta: parseIndexTotal(ln), idx }))
            .filter(s => !!s.meta)
        if (!samples.length) return

        // Guess total from the first parsed sample
        const n = samples[0].meta!.n
        const mid = Math.floor((n + 1) / 2)

        // remove the line with i==mid if present
        const hit = lines.value.find(ln => parseIndexTotal(ln)?.i === mid)
        if (hit) remove(hit)
    }

    return {
        // state
        lines,
        status,
        decodeResult,
        error,
        loading,

        // actions
        addScan,
        addMany,
        remove,
        clear,
        decodeNow,
        simulateMissing,
    }
}
