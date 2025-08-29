import { ref } from 'vue'

type EncodeArgs = {
    payload: unknown
    code?: string
    envelope?: 'v1url' | 'v1line'
    prefix?: string       // UI name
    version?: string      // UI name
    transport?: string
    serializer?: string
    by?: 'size' | 'count'
    size?: number
    count?: number
}

type DecodeArgs = {
    lines?: string[]
    chunks?: { text: string }[]
    envelope?: 'v1url' | 'v1line'
    prefix?: string       // UI name
    version?: string      // UI name
    transport?: string
    serializer?: string
}

export default function useTruthQr() {
    const encodeResult = ref<any>(null)
    const decodeResult = ref<any>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    // If you inject these via Inertia page props, you can read them here.
    // Fallbacks keep local dev easy.
    const routes = {
        encode: (window as any)?.TRUTH_QR_ENCODE_URL ?? '/api/encode',
        decode: (window as any)?.TRUTH_QR_DECODE_URL ?? '/api/decode',
    }

    async function encode(args: EncodeArgs) {
        error.value = null
        loading.value = true
        try {
            const res = await fetch(routes.encode, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    payload: args.payload,
                    code: args.code,
                    envelope: args.envelope,                      // alias
                    envelope_prefix: args.prefix || undefined,    // map UI → controller
                    envelope_version: args.version || undefined,  // map UI → controller
                    transport: args.transport,
                    serializer: args.serializer,
                    by: args.by,
                    size: args.size,
                    count: args.count,
                }),
            })
            const json = await res.json()
            if (!res.ok) throw new Error(json?.error || 'Encode failed')
            encodeResult.value = json
            return json
        } catch (e: any) {
            error.value = e?.message ?? String(e)
            throw e
        } finally {
            loading.value = false
        }
    }

    async function decode(args: DecodeArgs) {
        error.value = null
        loading.value = true
        try {
            const res = await fetch(routes.decode, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    lines: args.lines,
                    chunks: args.chunks,
                    envelope: args.envelope,                      // alias
                    envelope_prefix: args.prefix || undefined,    // map UI → controller
                    envelope_version: args.version || undefined,  // map UI → controller
                    transport: args.transport,
                    serializer: args.serializer,
                }),
            })
            const json = await res.json()
            if (!res.ok) throw new Error(json?.error || 'Decode failed')
            decodeResult.value = json
            return json
        } catch (e: any) {
            error.value = e?.message ?? String(e)
            throw e
        } finally {
            loading.value = false
        }
    }

    return { encode, decode, encodeResult, decodeResult, loading, error, routes }
}
