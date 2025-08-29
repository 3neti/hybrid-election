import { ref } from 'vue'
import axios from 'axios'

type EncodeParams = {
    payload: any
    code?: string
    envelope?: string
    transport?: string
    serializer?: string
    by?: 'size' | 'count'
    size?: number
    count?: number
}

type DecodeParams = {
    lines: string[]
    envelope?: string
    transport?: string
    serializer?: string
}

export default function useTruthQr() {
    const encodeResult = ref<any>(null)
    const decodeResult = ref<any>(null)

    // Expect host routes to be named like this (documented in controller)
    const routes = {
        encode: (window as any).route?.('truth-qr.encode') ?? '/api/encode',
        decode: (window as any).route?.('truth-qr.decode') ?? '/api/decode',
    }

    async function encode(params: EncodeParams) {
        const res = await axios.post(routes.encode, params)
        encodeResult.value = res.data
    }

    async function decode(params: DecodeParams) {
        const res = await axios.post(routes.decode, params)
        decodeResult.value = res.data
    }

    return { encode, decode, encodeResult, decodeResult, routes }
}
