import './bootstrap';
import {createInertiaApp} from '@inertiajs/vue3';
import {createApp, h} from "vue";
import {ZiggyVue} from "ziggy-js";

createInertiaApp({
    pages: {
        path: './Pages',
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .mount(el!!)
    },
})