import { createApp } from 'vue'
import ElectionReturn from '@/components/ElectionReturn.vue'


// The controller will inject the ER JSON here:
declare global {
    interface Window {
        __ER_DATA__?: any
        __ER_READY__?: boolean
    }
}

const root = document.getElementById('app')

// Optional: guard if the shell forgot to add ER data
const er = window.__ER_DATA__ ?? { code: 'ER-UNKNOWN', tallies: [], precinct: {} }

const app = createApp(ElectionReturn, { er })
app.mount(root || document.body)

// Signal to Puppeteer (later) that the page is fully mounted
window.__ER_READY__ = true
