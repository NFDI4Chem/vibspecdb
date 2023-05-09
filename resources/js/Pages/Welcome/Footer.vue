<template>
  <!-- Footer -->
  <footer class="relative mt-32 sm:mt-40" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="mx-auto max-w-7xl px-6 pb-8 lg:px-8">
      <div class="xl:grid xl:grid-cols-3 xl:gap-8">
        <div class="space-y-8">
          <img
            class="h-10 w-auto"
            src="/imgs/logo_vibspecdb.png"
            alt="VibSpecDB"
          />
          <p class="text-sm leading-6 text-gray-300">Useful public links:</p>
          <div class="flex space-x-6">
            <a
              v-for="item in footerNavigation.social"
              :key="item.name"
              :href="item.href"
              class="text-gray-500 hover:text-gray-400"
            >
              <span class="sr-only">{{ item.name }}</span>
              <component :is="item.icon" class="h-6 w-6" aria-hidden="true" />
            </a>
          </div>
        </div>
        <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
          <div class="md:grid md:grid-cols-2 md:gap-8">
            <div>
              <h3 class="text-sm font-semibold leading-6 text-white">
                Documentation
              </h3>
              <ul role="list" class="mt-6 space-y-4">
                <li
                  v-for="item in footerNavigation.documentation"
                  :key="item.name"
                >
                  <a
                    :href="item.href"
                    target="_blank"
                    class="text-sm leading-6 text-gray-300 hover:text-white"
                    >{{ item.name }}</a
                  >
                </li>
              </ul>
            </div>
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm font-semibold leading-6 text-white">
                Support
              </h3>
              <ul role="list" class="mt-6 space-y-4">
                <li v-for="item in footerNavigation.support" :key="item.name">
                  <a
                    :href="item.href"
                    class="text-sm leading-6 text-gray-300 hover:text-white"
                    >{{ item.name }}</a
                  >
                </li>
              </ul>
            </div>
          </div>
          <div class="md:grid md:grid-cols-2 md:gap-8">
            <div>
              <h3 class="text-sm font-semibold leading-6 text-white">
                Company
              </h3>
              <ul role="list" class="mt-6 space-y-4">
                <li v-for="item in footerNavigation.company" :key="item.name">
                  <a
                    :href="item.href"
                    class="text-sm leading-6 text-gray-300 hover:text-white"
                    >{{ item.name }}</a
                  >
                </li>
              </ul>
            </div>
            <div class="mt-10 md:mt-0">
              <h3 class="text-sm font-semibold leading-6 text-white">Legal</h3>
              <ul role="list" class="mt-6 space-y-4">
                <li v-for="item in footerNavigation.legal" :key="item.name">
                  <a
                    :href="item.href"
                    class="text-sm leading-6 text-gray-300 hover:text-white"
                    >{{ item.name }}</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24">
        <p class="text-xs leading-5 text-gray-400">
          &copy; 2023 VibSpecDB. All rights reserved.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { defineComponent, h, ref } from 'vue'

const base_docs_page =
  'https://nfdi4chem.pages.photonicdata.science/vibspecdb-docs'

const ramanmetrix_docs_page = 'https://docs.ramanmetrix.eu/documentation'

const footerNavigation = {
  documentation: [
    {
      name: 'Main',
      href: `${base_docs_page}`,
    },
    {
      name: 'Data Types',
      href: `${ramanmetrix_docs_page}/Data.html#data-structure-types`,
    },
    {
      name: 'Supported Devices',
      href: `${ramanmetrix_docs_page}/Data.html#supported-devices`,
    },
    {
      name: 'Providing Metadata',
      href: `${ramanmetrix_docs_page}/Data.html#providing-metadata`,
    },
  ],
  support: [
    // { name: 'Pricing', href: '#' },
    // { name: 'Documentation', href: '#' },
    { name: 'Guides', href: '#' },
    { name: 'API Status', href: '#' },
  ],
  company: [
    { name: 'About', href: '#' },
    { name: 'Blog', href: '#' },
    // { name: 'Jobs', href: '#' },
    // { name: 'Press', href: '#' },
    // { name: 'Partners', href: '#' },
  ],
  legal: [
    // { name: 'Claim', href: '#' },
    { name: 'Privacy', href: '#' },
    { name: 'Terms', href: '#' },
  ],
  social: [
    {
      name: 'GitLab',
      href: 'https://git.photonicdata.science/nfdi4chem/vibspecdb',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M23.955 13.587l-1.342-4.135-2.664-8.189c-.135-.423-.73-.423-.867 0L16.418 9.45H7.582L4.919 1.263C4.783.84 4.185.84 4.05 1.26L1.386 9.449.044 13.587c-.121.375.014.789.331 1.023L12 23.054l11.625-8.443c.318-.235.453-.647.33-1.024',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    {
      name: 'Documentation',
      href: 'https://nfdi4chem.pages.photonicdata.science',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 64 64' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M62.869,16.842h-0.057c-1.096,0.007-1.266-0.945-1.285-1.387V8.542c0-0.738-0.598-1.336-1.334-1.336H40.428  c-4.959,0-6.809,1.838-7.268,2.415l-0.126,0.175l-0.001,0.003l-0.391,0.536c-0.001,0-0.001,0-0.001,0  c-0.525,0.721-0.982,0.414-1.202,0.178l-0.748-0.933l-0.37-0.446c-0.622-0.653-2.243-1.928-5.456-1.928H3.808  c-0.738,0-1.336,0.598-1.336,1.336v6.825c0,1.195-0.649,1.435-1.047,1.475H1.129c-0.324,0.02-1.078,0.208-1.078,1.595v32.529  c0,0.798,0.647,1.444,1.445,1.444h19.218c6.131,0,8.803,2.312,9.604,3.199l0.457,0.566h0.001c0,0,1.226,1.387,2.518,0l0,0  l0.268-0.314v0.001l0.004-0.003l0.342-0.4c0.01-0.012,0.092-0.104,0.211-0.226c1.023-0.995,3.58-2.823,8.58-2.823h19.805  c0.799,0,1.445-0.646,1.445-1.444V18.122C63.949,16.946,63.098,16.848,62.869,16.842z M58.551,45.862  c0,0.641-0.535,1.158-1.201,1.158H41.055c-5.799,0-7.904,2.62-7.904,2.62l-0.267,0.358l-0.125,0.173c-0.001,0-0.001,0-0.001,0  c-0.553,0.751-1.366,0.006-1.37,0.002l-0.375-0.469c-0.278-0.334-2.408-2.685-7.563-2.685h-16.8c-0.663,0-1.199-0.518-1.199-1.158  V11.199c0-0.64,0.536-1.158,1.199-1.158h13.043c9.702,0,10.621,5.511,10.684,7.112v24.106c0,1.752,0.835,2.081,1.306,2.129h0.701  c0.48-0.048,1.243-0.333,1.243-1.7V16.842h-0.008c0.072-2.005,0.854-6.802,6.689-6.802H57.35c0.666,0,1.201,0.518,1.201,1.158  V45.862z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    /*
    {
      name: 'Facebook',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    {
      name: 'Instagram',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    {
      name: 'Twitter',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              d: 'M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84',
            }),
          ]),
      }),
    },
    {
      name: 'YouTube',
      href: '#',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
    */
    {
      name: 'GitHub',
      href: 'https://github.com/Bocklitz-Lab',
      icon: defineComponent({
        render: () =>
          h('svg', { fill: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', {
              'fill-rule': 'evenodd',
              d: 'M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z',
              'clip-rule': 'evenodd',
            }),
          ]),
      }),
    },
  ],
}
</script>

<style lang="scss" scoped></style>
