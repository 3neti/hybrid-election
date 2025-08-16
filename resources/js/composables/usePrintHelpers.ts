import { Ref, unref } from 'vue'

/** Add/replace an @page CSS rule for the selected paper size. Safe to call multiple times. */
export function injectPageRule(paper: 'legal' | 'a4'): void {
    if (typeof document === 'undefined') return
    const id = 'er-print-page-size'
    let el = document.getElementById(id) as HTMLStyleElement | null
    const css = paper === 'legal'
        ? '@page{size:8.5in 14in; margin:0}'
        : '@page{size:A4; margin:0}'
    if (!el) {
        el = document.createElement('style')
        el.id = id
        el.media = 'print'
        document.head.appendChild(el)
    }
    el.textContent = css
}

/** Print-isolation style injection (toggleable). */
export function injectPrintIsolation(id: string, enabled: boolean): void {
    if (typeof document === 'undefined') return
    const styleId = `er-print-isolation-${id}`
    let el = document.getElementById(styleId) as HTMLStyleElement | null

    if (!enabled) {
        if (el?.parentNode) el.parentNode.removeChild(el)
        return
    }

    if (!el) {
        el = document.createElement('style')
        el.id = styleId
        el.media = 'print'
        document.head.appendChild(el)
    }

    el.textContent = `
@media print {
  body, html { height: auto !important; }
  body * { visibility: hidden !important; margin: 0 !important; }
  #${id}, #${id} * { visibility: visible !important; }
  #${id} { position: absolute !important; left: 0 !important; top: 0 !important; width: auto !important; }
}`
}

/** Await all <img> under a root element to load (or error) before printing. */
export async function waitForImagesWithin(rootId: string, imgSelector = '.qr-img'): Promise<void> {
    if (typeof document === 'undefined') return
    const root = document.getElementById(rootId)
    if (!root) return
    const imgs = Array.from(root.querySelectorAll<HTMLImageElement>(imgSelector))
    const notDone = imgs.filter(i => !i.complete || (i.naturalWidth === 0 && i.naturalHeight === 0))
    await Promise.all(
        notDone.map(
            i =>
                new Promise<void>(res => {
                    i.addEventListener('load', () => res(), { once: true })
                    i.addEventListener('error', () => res(), { once: true })
                })
        )
    )
}

type UseHandlePrintOptions = {
    paperRef: Ref<'legal' | 'a4'>
    scopeIdRef: Ref<string>
    autoQrRef: Ref<boolean | undefined>
    generateQr: () => Promise<void> | void
    /** Optional: whether to isolate the print area (defaults true) */
    printIsolatedRef?: Ref<boolean | undefined>
    /** Optional: selector for images to wait on */
    imgSelector?: string
}

/**
 * Returns a `handlePrint()` that:
 * - injects @page rule
 * - injects (or removes) isolation CSS
 * - optionally calls `generateQr()` if `autoQrRef` is truthy
 * - waits for images
 * - triggers window.print()
 */
export function useHandlePrint(opts: UseHandlePrintOptions) {
    const {
        paperRef,
        scopeIdRef,
        autoQrRef,
        generateQr,
        printIsolatedRef,
        imgSelector = '.qr-img',
    } = opts

    async function handlePrint() {
        const paper = unref(paperRef)
        const scopeId = unref(scopeIdRef)
        const autoQr = !!unref(autoQrRef)
        const printIsolated = printIsolatedRef == null ? true : !!unref(printIsolatedRef)

        injectPageRule(paper)
        injectPrintIsolation(scopeId, printIsolated)

        if (autoQr) {
            try { await generateQr() } catch { /* swallow – UI already surfaces errors */ }
        }

        // Next frame + tiny delay to allow DOM to settle & images to enqueue
        await new Promise(r => requestAnimationFrame(() => r(null)))
        await waitForImagesWithin(scopeId, imgSelector)

        setTimeout(() => {
            if (typeof window !== 'undefined' && typeof window.print === 'function') {
                window.print()
            }
        }, 60)
    }

    return { handlePrint }
}
