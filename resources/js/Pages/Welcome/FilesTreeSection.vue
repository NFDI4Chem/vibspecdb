<template>
  <!-- Content section -->
  <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-40 lg:px-8">
    <div class="mx-auto lg:mx-0 w-full">
      <h2 class="text-3xl font-bold tracking-tight text-cyan-500 sm:text-4xl">
        FileTree Viewer
      </h2>
    </div>
    <p class="mt-6 text-lg leading-8 text-gray-300">
      After the files are uploaded, they are shown in the file navigator tree.
      Files in the tree can be moved and viewed.
    </p>
    <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none mt-5">
      <div
        class="grid max-w-xl grid-cols-1 gap-12 lg:gap-x-20 text-base leading-7 text-gray-300 lg:max-w-none lg:grid-cols-2"
      >
        <div>
          <div class="h-auto w-full">
            <img
              src="/imgs/welcome/FilesTreeView.gif"
              alt=""
              class="w-auto mx-auto shadow-xl"
            />
          </div>
        </div>
        <div>
          <div class="w-full flex-auto lg:pl-20">
            <!-- <p class="mt-6 text-lg text-white uppercase">Wide functionality:</p> -->
            <ul
              role="list"
              class="mt-12 flex flex-col gap-x-8 gap-y-6 text-2xl leading-100"
            >
              <li
                v-for="(benefit, idx) in benefits"
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
          <!-- <p>
            Erat pellentesque dictumst ligula porttitor risus eget et eget.
            Ultricies tellus felis id dignissim eget. Est augue maecenas risus
            nulla ultrices congue nunc tortor. Enim et nesciunt doloremque
            nesciunt voluptate.
          </p>
          <p class="mt-8">
            Et vitae blandit facilisi magna lacus commodo. Vitae sapien duis
            odio id et. Id blandit molestie auctor fermentum dignissim. Lacus
            diam tincidunt ac cursus in vel. Mauris varius vulputate et ultrices
            hac adipiscing egestas. Iaculis convallis ac tempor et ut. Ac lorem
            vel integer orci.
          </p> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { CheckCircleIcon } from '@heroicons/vue/24/outline'

const benefits = [
  'Drag & Drop folder or file',
  'Add Folder as a subfolder',
  'Rename Tree element',
  'Delete any of Tree Element',
  'Click to View Spectra',
  'Multiple Spectra Viewer',
]

const isOdd = num => {
  return num % 2
}
</script>

<style lang="scss" scoped></style>
