<template>
  <!-- Content section -->
  <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-40 lg:px-8">
    <div class="mx-auto lg:mx-0 w-full">
      <h2 class="text-3xl font-bold tracking-tight text-cyan-500 sm:text-4xl">
        File Uploader
      </h2>
    </div>
    <p class="mt-6 text-lg leading-8 text-gray-300">
      It has the ability to upload files from different sources: PC,
      <strong>DropBox, Google Drive, OneDrive, with using just link</strong>
      etc.
    </p>
    <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none mt-5">
      <div
        class="grid max-w-xl grid-cols-1 gap-12 lg:gap-x-20 text-base leading-7 text-gray-300 lg:max-w-none lg:grid-cols-2"
      >
        <div>
          <div class="w-full flex-auto lg:pl-20">
            <ul
              role="list"
              class="mt-12 flex flex-col gap-x-5 gap-y-4 text-xl leading-100"
            >
              <li
                v-for="(benefit, idx) in features"
                :key="benefit"
                class="flex gap-x-3 align-middle w-full"
                :class="{ 'md:pl-0': isOdd(idx) }"
              >
                <CheckCircleIcon
                  class="h-12 w-8 flex-none"
                  aria-hidden="true"
                />
                <div class="pt-2 text-gray-400">{{ benefit }}</div>
              </li>
            </ul>
          </div>
        </div>
        <div>
          <div class="h-auto w-full mt-8">
            <img
              src="/imgs/welcome/Uppy.png"
              alt=""
              class="w-auto mx-auto shadow-xl rounded-2xl"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { CheckCircleIcon } from '@heroicons/vue/24/outline'

const features = [
  'Upload from many resources',
  'Comfortable upload process',
  'The ability to upload large files and continue to use the platform at the same time',
  'The ability to download zip archive with many spectra.',
]

const isOdd = num => {
  return num % 2
}
</script>

<style lang="scss" scoped></style>
