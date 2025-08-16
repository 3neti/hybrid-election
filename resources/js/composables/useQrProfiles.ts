// src/composables/useQrProfiles.ts
import { computed } from 'vue'

export type ECC = 'low' | 'medium' | 'quartile' | 'high'
export type QrProfile = 'small-clear' | 'normal' | 'high-capacity' | null

export interface QrProfileProps {
    /** Chosen preset (optional) */
    qrProfile?: QrProfile
    /** Optional explicit overrides */
    desiredChunks?: number | null
    ecc?: ECC
    size?: number
    margin?: number
}

/**
 * Centralizes “profile → effective params” logic.
 * - Profiles set sane defaults.
 * - Explicit props override profile defaults.
 * - desiredChunks is clamped 5..16.
 */
export function useQrProfiles(props: Readonly<QrProfileProps>) {
    const profileDefaults = computed(() => {
        switch (props.qrProfile) {
            case 'small-clear':
                return { desiredChunks: 8, ecc: 'quartile' as ECC, size: 384, margin: 16 }
            case 'high-capacity':
                return { desiredChunks: 5, ecc: 'low' as ECC,       size: 640, margin: 8 }
            case 'normal':
            default:
                return { desiredChunks: 5, ecc: 'medium' as ECC,    size: 512, margin: 12 }
        }
    })

    const effectiveDesiredChunks = computed<number>(() => {
        const v = props.desiredChunks ?? profileDefaults.value.desiredChunks
        const n = Number(v)
        // clamp 5..16 (your system minimum/maximum)
        return Math.max(5, Math.min(16, Number.isFinite(n) ? n : 5))
    })

    const effectiveEcc    = computed<ECC>(()    => props.ecc    ?? profileDefaults.value.ecc)
    const effectiveSize   = computed<number>(() => props.size   ?? profileDefaults.value.size)
    const effectiveMargin = computed<number>(() => props.margin ?? profileDefaults.value.margin)

    return {
        profileDefaults,
        effectiveDesiredChunks,
        effectiveEcc,
        effectiveSize,
        effectiveMargin,
    }
}
