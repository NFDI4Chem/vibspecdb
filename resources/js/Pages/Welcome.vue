<template>
  <div class="bg-gray-900">
    <!-- Header -->
    <header class="absolute inset-x-0 top-0 z-50">
      <nav
        class="mx-auto flex max-w-7xl items-center justify-between p-6 pt-8 lg:px-8"
        aria-label="Global"
      >
        <div class="flex lg:flex-1">
          <a href="#" class="-m-1.5 p-1.5">
            <span class="sr-only">VibSpecDB</span>
            <img class="h-10 w-auto" src="/imgs/logo_vibspecdb.png" alt="" />
          </a>
        </div>
        <div class="flex lg:hidden">
          <button
            type="button"
            class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-400"
            @click="mobileMenuOpen = true"
          >
            <span class="sr-only">Open main menu</span>
            <Bars3Icon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
          <a
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            class="text-sm font-semibold leading-6 text-white"
            >{{ item.name }}</a
          >
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <a href="/login" class="text-sm font-semibold leading-6 text-cyan-500"
            >Log in <span aria-hidden="true" class="text-white">&rarr;</span></a
          >
        </div>
      </nav>
      <Dialog
        as="div"
        class="lg:hidden"
        @close="mobileMenuOpen = false"
        :open="mobileMenuOpen"
      >
        <div class="fixed inset-0 z-50" />
        <DialogPanel
          class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10"
        >
          <div class="flex items-center justify-between">
            <a href="#" class="-m-1.5 p-1.5">
              <span class="sr-only">VibSpecDB</span>
              <img class="h-8 w-auto" src="/imgs/logo_vibspecdb.png" alt="" />
            </a>
            <button
              type="button"
              class="-m-2.5 rounded-md p-2.5 text-gray-400"
              @click="mobileMenuOpen = false"
            >
              <span class="sr-only">Close menu</span>
              <XMarkIcon class="h-6 w-6" aria-hidden="true" />
            </button>
          </div>
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/25">
              <div class="space-y-2 py-6">
                <a
                  v-for="item in navigation"
                  :key="item.name"
                  :href="item.href"
                  class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800"
                  >{{ item.name }}</a
                >
              </div>
              <div class="py-6">
                <a
                  href="#"
                  class="-mx-3 block rounded-lg px-3 py-2.5 font-semibold leading-7 text-white hover:bg-gray-800"
                  >Log in</a
                >
              </div>
            </div>
          </div>
        </DialogPanel>
      </Dialog>
    </header>

    <w-alert
      v-if="release_status == 'true'"
      icon=""
      no-border
      xs
      dismiss
      class="w-full h-[30px] bg-emerald-500 z-50 my-0 text-center rounded-none"
      >{{ release_text }}
    </w-alert>

    <main class="relative isolate">
      <HeaderSection class="mt12" />

      <TextSection />

      <DashboardSection />

      <FilesTreeSection />

      <SpectralViewerSection />

      <UploadSection />

      <RamanCompatible />
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { defineComponent, h, ref, computed } from 'vue'
import { Dialog, DialogPanel } from '@headlessui/vue'

import SpectralViewerSection from '@/Pages/Welcome/SpectralViewerSection.vue'
import FilesTreeSection from '@/Pages/Welcome/FilesTreeSection.vue'
import DashboardSection from '@/Pages/Welcome/DashboardSection.vue'
import TextSection from '@/Pages/Welcome/TextSection.vue'
import HeaderSection from '@/Pages/Welcome/HeaderSection.vue'
import UploadSection from '@/Pages/Welcome/UploadSection.vue'
import RamanCompatible from '@/Pages/Welcome/RamanCompatible.vue'
import Footer from '@/Pages/Welcome/Footer.vue'

import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'

const show_warn = ref(true)

const release_text = computed(() => {
  return import.meta.env.VITE_RELEASE_TEXT
})
const release_status = computed(() => {
  return import.meta.env.VITE_RELEASE_STATUS
})

const base_docs_page =
  'https://nfdi4chem.pages.photonicdata.science/vibspecdb-docs'

const ramanmetrix_docs_page = 'https://docs.ramanmetrix.eu/documentation'

const navigation = [
  { name: 'Documentation', href: base_docs_page, action: () => {} },
  {
    name: 'Spectra',
    href: '#',
    action: () => {},
  },
  { name: 'Projects', href: '/#projects', action: () => {} },
  { name: 'Search', href: '#', action: () => {} },
]

const mobileMenuOpen = ref(false)
</script>
