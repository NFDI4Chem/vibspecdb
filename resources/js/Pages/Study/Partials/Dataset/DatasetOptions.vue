<template>
  <div
    class="h-full w-full p-2 overflow-auto flex flex-col justify-between"
    v-if="show"
  >
    <div>
      <div class="flex flex-row justify-between">
        <div class="text-bold text-sm pb-2">Meta-files:</div>
      </div>
      <div v-if="data?.metafiles?.length">
        <div
          v-for="mf in data?.metafiles"
          :key="mf"
          class="flex flex-row justify-between bg-white border-b"
        >
          <div class="px-2 text-teal-600">- {{ mf.name }}</div>

          <ToolTipWrapper
            text="Metafile will be converted to a regular file thus it will not be in the metafiles list of this dataset."
          >
            <template #btn>
              <w-button
                v-on="on"
                class="text-red-400 w-5 ml1 cursor-pointer border-0"
                @click="deleteMetafile(mf)"
                md
                bg-color="transparent"
                icon="mdi mdi-delete"
              ></w-button>
            </template>
            <template #icon>
              <w-button
                class="mx1"
                bg-color="error"
                xs
                icon="mdi mdi-exclamation"
              ></w-button>
            </template>
          </ToolTipWrapper>
        </div>
      </div>
      <div v-else>
        <div class="text-red-600 text-sm">
          There is no metafiles connected with selected dataset
        </div>
      </div>
    </div>
    <div class="flex flex-row justify-end pt-2">
      <w-button class="ma1" :loading="false" @click="() => {}">
        <w-icon class="mr1">mdi mdi-tools</w-icon>
        Parse metadata
      </w-button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import ToolTipWrapper from '@/Components/UniFilesTree/ToolTipWrapper.vue'

const emit = defineEmits(['deleteMetafile'])
const props = defineProps(['data'])

const show = computed(() => {
  return props?.data?.dataset?.id
})

const deleteMetafile = file => {
  emit('deleteMetafile', file, file?.type === 'file' ? 'metafile' : 'file')
}
</script>

<style lang="scss" scoped></style>
