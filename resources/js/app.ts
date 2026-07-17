import './bootstrap';
import {createInertiaApp} from '@inertiajs/vue3';
import {createApp, h} from "vue";
import {createPinia} from 'pinia'
import Vue3Toastify, {type ToastContainerOptions} from 'vue3-toastify';
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
            .use(Vue3Toastify, {
                autoClose: 3000
            } as ToastContainerOptions)
            .mount(el!!)
    },
})