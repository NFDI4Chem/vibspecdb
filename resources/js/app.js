import './bootstrap'
import '../css/app.css'

// import uPlot from 'uplot';
import UplotVue from 'uplot-vue'
import 'uplot/dist/uPlot.min.css'
import helpers from './Mixins/Global.js'

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3'

import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

import Popper from 'vue3-popper'

import WaveUI from 'wave-ui'
import { Splitpanes, Pane } from 'splitpanes'

import PersistentLayout from '@/Layouts/PersistentLayout.vue'

import { store } from './store'

const appName =
  window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
  title: title => `${title} - ${appName}`,
  resolve: name => {
    const page = resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    )
    page.then(module => {
      module.default.layout = module.default.layout || PersistentLayout
    })
    return page
  },
  setup({ el, app, props, plugin }) {
    const application = createApp({ render: () => h(app, props), store })
      .use(plugin)
      .use(store)
      .mixin({ methods: { route } })
      .mixin(helpers)
      .component('UplotVue', UplotVue)
      .component('Popper', Popper)
      .component('splitpanes', Splitpanes)
      .component('pane', Pane)
      .component('inertia-link', Link)

    application.provide(
      'csrf_token',
      document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    )

    application.config.warnHandler = function (msg, vm, trace) {
      return null
    }

    new WaveUI(application, {
      notificationManager: {
        align: 'left', // Or 'left'.
        bottom: true,
        absolute: true,
        transition: 'default', // Sliding from the side by default.
      },
    })
    application.provide('WaveUI', WaveUI)
    application.mount(el)
    return application
  },
})

InertiaProgress.init({ color: '#4B5563' })
