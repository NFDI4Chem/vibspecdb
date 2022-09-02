<template>
  <div class="bg-white">
    <div class="mx-auto max-w-2xl py-2 px-4 sm:py-4 sm:px-6 lg:max-w-7xl lg:px-8">
      <h2 class="sr-only">Model Items</h2>

      <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-8">
        <div v-for="item in items" :key="item.id" class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
          <div class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 sm:aspect-none sm:h-64">
            <img :src="item.imageSrc" :alt="item.imageAlt" class="h-full w-full object-cover object-center sm:h-full sm:w-full" />
          </div>
          <div class="flex flex-1 flex-col space-y-2 p-4">
            <h3 class="text-sm font-medium text-gray-900">
              <a :href="item.href">
                <span aria-hidden="true" class="absolute inset-0" />
                {{ item.name }}
              </a>
            </h3>
            <p class="text-sm text-gray-500">{{ item.description }}</p>
            <div class="flex flex-1 flex-col justify-end" v-if="false">
              <p class="text-sm italic text-gray-500">{{ item.options }}</p>
              <p class="text-base font-medium text-gray-900">{{ item.price }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

const props = defineProps(['items']);

</script>

<style lang="scss" scoped>

</style>