import './bootstrap';
import {createInertiaApp} from '@inertiajs/vue3';
import {createApp, h} from "vue";
import {createPinia} from 'pinia'
import {ZiggyVue} from "ziggy-js";

const pinia = createPinia()

createInertiaApp({
    pages: {
        path: './Pages',
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .mount(el!!)
    },
})