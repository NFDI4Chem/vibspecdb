require('./bootstrap');

// import uPlot from 'uplot';
import UplotVue from 'uplot-vue';
import 'uplot/dist/uPlot.min.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import helpers from "./Mixins/Global.js";

import { store } from './store'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props), store })
            .use(plugin)
            .use(store)
            .mixin({ methods: { route } })
            .mixin(helpers)
            .component('UplotVue', UplotVue)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
