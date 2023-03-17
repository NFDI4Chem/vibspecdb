<template>
  <div class="relative z-0 project-card">
    <div
      class="z-10 cursor-pointer bg-slate-400 text-white w-auto absolute -top-2 -left-2"
    >
      <div class="inline-flex items-center text-sm h-full px2 gap-x-1">
        <w-icon v-if="item.is_public" class="mx-auto" sm>mdi mdi-web</w-icon>
        <w-icon v-else class="mx-auto" sm> mdi mdi-lock</w-icon>
        <div>{{ item.is_public ? 'Public' : 'Private' }}</div>
      </div>
    </div>

    <delete-btn
      :pid="item.id"
      class="z-10 cursor-pointer absolute -right-8 top-5 rotate-45"
      v-if="false"
    >
      <div class="inline-flex items-center pr-1 text-gray-500 gap-1">
        <w-icon class="mx-auto" md>mdi mdi-close</w-icon>
        <div class="">Delete</div>
      </div>
    </delete-btn>

    <div class="absolute right-0 h-[340px] top-[170px] z-10">
      <w-menu right align-bottom arrow class="project-card">
        <template #activator="{ on }">
          <w-icon v-on="on" class="mx-auto text-gray-400 cursor-pointer" xl
            >mdi mdi-dots-vertical</w-icon
          >
        </template>
        <w-list
          class="white--bg"
          v-model="listSelection"
          :items="listItems"
          item-class="pa0"
        >
          <template #item.1>
            <delete-btn :pid="item.id" class="cursor-pointer">
              <div class="inline-flex items-center pr-1 text-gray-500 gap-1">
                <w-icon class="mx-auto" md>mdi mdi-close</w-icon>
                <div class="">Delete</div>
              </div>
            </delete-btn>
          </template>
          <template #item.2>
            <w-button md class="w-full text-left" bg-color="secondary">
              <w-icon class="mr1">wi-check</w-icon>Share
            </w-button>
          </template>
        </w-list>
      </w-menu>
    </div>

    <div
      class="w-[250px] h-[340px] mx-auto bg-white text-center shadow-lg rounded-0 overflow-hidden hover:opacity-75 hover:cursor-pointer"
      @click="onSelect(item)"
    >
      <div class="p-5 w-40 mx-auto min-h-20">
        <w-image
          src="/imgs/study/study.png"
          :ratio="3 / 4"
          transition="scale-fade"
        />
      </div>
      <div
        class="flex flex-row justify-between px-6 align-middle text-white bg-gray-500"
      >
        <w-icon class="pt-1" md>mdi mdi-briefcase</w-icon>
        <h1 class="text-white font-semibold text-md">#0 Datasets</h1>
      </div>

      <div
        class="h-[175px] py-4 px-5 relative text-gray-500 flex flex-col gap-1 justify-between"
      >
        <div>
          <h1 class="text-2xl font-semibold text-gray-800 text-left">
            {{ item.name }}
          </h1>
          <p
            class="py-1 text-md text-gray-700 special-ellipsis"
            v-if="item?.description?.length"
            v-html="md(item.description)"
          />
        </div>
        <div class="flex flex-col gap-y-1">
          <div class="flex flex-end items-center mt-1">
            <w-icon class="pr-1 pt-0.5" md
              >mdi mdi-calendar-blank-outline</w-icon
            >
            <h1 class="px-2 text-sm pt-1">
              {{ formatDate(item?.created_at) }}
            </h1>
          </div>
          <div class="flex items-center mt-1">
            <w-icon class="pr-1 pt-0.5" md>mdi mdi-account</w-icon>
            <h1 class="px-2 text-sm pt-1">Owner / Shared</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

import DeleteBtn from '@/Pages/Project/Partials/DeleteBtn.vue'

const props = defineProps(['item'])
const emit = defineEmits(['onSelect'])

const onSelect = model => {
  emit('onSelect', model)
}

const showmenu = ref(true)
const listSelection = null
const listItems = [{ label: 'Item 1' }, { label: 'Item 2' }]
</script>

<style lang="scss">
.project-card {
  > .w-card__content {
    padding: 0;
  }
  .w-card .w-card__content {
    width: 300px;
  }
}
</style>
