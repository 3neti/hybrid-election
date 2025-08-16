import { computed } from 'vue'

export type QrLayoutProfile =
    | 'small-clear'
    | 'normal'
    | 'high-capacity'
    | null

export interface QrLayoutProps {
    /** Preferred single control: printed square size in inches */
    qrPrintSizeIn?: number
    /** Deprecated, kept for back-compat: will be coerced to square via min(w,h) */
    qrPrintWidthIn?: number
    qrPrintHeightIn?: number
    /** Grid controls */
    qrGridCols?: number
    qrGridGapIn?: number
}

/**
 * Single source of truth for QR card sizing and grid CSS variables.
 * - Coerces legacy width/height to a square using min(w,h), with a warning if mismatched.
 * - Clamps size to 1–6 inches.
 * - Produces CSS vars: --qrsize, --qrcols, --qrgap
 */
export function useQrCardLayout(props: Readonly<QrLayoutProps>) {
    const qrCardSizeIn = computed<number>(() => {
        const MIN = 1
        const MAX = 6

        const w = props.qrPrintWidthIn
        const h = props.qrPrintHeightIn

        // Back-compat path: width/height provided → use min(w,h)
        if (w != null || h != null) {
            if (w != null && h != null && w !== h) {
                console.warn(
                    '[ElectionReturn] qrPrintWidthIn/qrPrintHeightIn are deprecated; using min(w,h) to keep square.'
                )
            }
            const side = Math.min(
                w != null ? Number(w) : Number.POSITIVE_INFINITY,
                h != null ? Number(h) : Number.POSITIVE_INFINITY
            )
            const fallback = props.qrPrintSizeIn ?? 2.5
            const chosen = Number.isFinite(side) ? side : fallback
            return Math.max(MIN, Math.min(MAX, chosen))
        }

        // Preferred single control
        const sz = Number(props.qrPrintSizeIn ?? 2.5)
        return Math.max(MIN, Math.min(MAX, sz))
    })

    const qrStyleVars = computed<Record<string, string>>(() => {
        const cols = Math.max(1, Math.min(6, Number(props.qrGridCols || 0) || 3))
        const gap  = Math.max(0, Number(props.qrGridGapIn || 0) || 0.10)
        return {
            '--qrsize': `${qrCardSizeIn.value}in`,
            '--qrcols': String(cols),
            '--qrgap':  `${gap}in`,
        }
    })

    return { qrCardSizeIn, qrStyleVars }
}
