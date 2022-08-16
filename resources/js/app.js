import './bootstrap';
import '../css/app.css';

// import uPlot from 'uplot';
import UplotVue from 'uplot-vue';
import 'uplot/dist/uPlot.min.css';
import helpers from "./Mixins/Global.js";

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import Popper from "vue3-popper";

import PersistentLayout from '@/Layouts/PersistentLayout.vue'

import { store } from './store'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => { 
        const page  = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
        page.then((module) => {
            module.default.layout = module.default.layout || PersistentLayout;
        });
        return page;
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props), store })
            .use(plugin)
            .use(store)
            .mixin({ methods: { route } })
            .mixin(helpers)
            .component('UplotVue', UplotVue)
            .component('Popper', Popper)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
