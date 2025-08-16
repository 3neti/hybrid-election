import { ref, computed, watch, type Ref } from 'vue'
import axios from 'axios'

export type ECC = 'low' | 'medium' | 'quartile' | 'high'

export interface QrChunk { index: number; text: string; png?: string; png_error?: string }
export interface QrResponse {
    code: string
    version: string
    total: number
    chunks: QrChunk[]
    params?: {
        payload?: string
        desired_chunks?: number | null
        effective_max_chars_per_qr?: number
        ecc?: string
        size?: number
        margin?: number
    }
}

/** Public helper: same behavior you had in the component. */
export function resolveQrUrl(erCode: string, explicitEndpoint?: string | null): string {
    if (explicitEndpoint) return explicitEndpoint

    if (typeof (window as any).route === 'function') {
        try { return (window as any).route('qr.er', { code: erCode }) } catch {}
    }
    return `/api/qr/election-return/${encodeURIComponent(erCode)}`
}

type PayloadMode = 'minimal' | 'full'

export interface UseQrApiOptions {
    /** ER JSON (used only for POST path) */
    erJsonRef: Ref<any | null | undefined>
    /** ER code */
    erCodeRef: Ref<string | null | undefined>

    /** Behavior flags / inputs */
    autoQrRef: Ref<boolean | undefined>
    qrChunksPropRef?: Ref<QrChunk[] | undefined>
    payloadRef?: Ref<PayloadMode | undefined>
    desiredChunksRef?: Ref<number | null | undefined>
    eccRef?: Ref<ECC | undefined>
    sizeRef?: Ref<number | undefined>
    marginRef?: Ref<number | undefined>
    endpointRef?: Ref<string | null | undefined>

    /** Debounce (ms) for triggerGenerateQr, defaults 250ms */
    debounceMs?: number
}

export function useQrApi(opts: UseQrApiOptions) {
    const qr        = ref<QrChunk[]>([])
    const qrLoading = ref(false)
    const qrError   = ref<string | null>(null)

    const debounceMs = opts.debounceMs ?? 250
    let regenTimer: ReturnType<typeof setTimeout> | undefined

    async function generateQr() {
        const erCode = opts.erCodeRef.value
        const auto   = !!opts.autoQrRef.value

        // external chunks override everything
        if (opts.qrChunksPropRef?.value?.length) {
            qr.value = opts.qrChunksPropRef.value
            qrError.value = null
            return
        }

        if (!auto || !erCode) return

        qrLoading.value = true
        qrError.value = null
        qr.value = []

        const endpoint = opts.endpointRef?.value || null
        const url = resolveQrUrl(erCode, endpoint)

        const desired_chunks = opts.desiredChunksRef?.value ?? undefined
        const ecc    = opts.eccRef?.value
        const size   = opts.sizeRef?.value
        const margin = opts.marginRef?.value

        try {
            if (endpoint) {
                // POST: send in-memory ER JSON (same as before)
                const { data } = await axios.post<QrResponse>(url, {
                    json: opts.erJsonRef.value ?? null,
                    code: erCode,
                    desired_chunks,
                    make_images: 1,
                    ecc,
                    size,
                    margin
                })
                qr.value = (data?.chunks ?? []).slice().sort((a, b) => a.index - b.index)
            } else {
                // GET: DB-backed endpoint by code
                const { data } = await axios.get<QrResponse>(url, {
                    params: {
                        payload: opts.payloadRef?.value,
                        desired_chunks,
                        make_images: 1,
                        ecc,
                        size,
                        margin
                    }
                })
                qr.value = (data?.chunks ?? []).slice().sort((a, b) => a.index - b.index)
            }

            if (!qr.value.length) {
                qrError.value = 'No QR chunks were returned.'
            }
        } catch (e: any) {
            qrError.value = e?.response?.data?.message || String(e)
        } finally {
            qrLoading.value = false
        }
    }

    function triggerGenerateQr() {
        if (regenTimer) clearTimeout(regenTimer)
        regenTimer = setTimeout(() => { generateQr() }, debounceMs)
    }

    // Internal watcher that mirrors the one from ElectionReturn.vue
    watch(
        () => [
            opts.erCodeRef.value,
            opts.payloadRef?.value,
            opts.desiredChunksRef?.value,
            opts.eccRef?.value,
            opts.sizeRef?.value,
            opts.marginRef?.value,
            opts.autoQrRef?.value,
            opts.endpointRef?.value,
            // include external chunks ref so switching to it refreshes UI state
            opts.qrChunksPropRef?.value?.length,
        ],
        () => triggerGenerateQr(),
        { immediate: true }
    )

    return { qr, qrLoading, qrError, generateQr, triggerGenerateQr }
}
