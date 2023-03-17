<template>
  <div :class="{ 'border-b-4': exists_meta }">
    <div class="flex flex-row justify-between pb2 pr6">
      <div class="border-b-2 flex flex-row flex-start gap-2">
        <div class="mb-auto">
          <StudyInfoHelper :right="true" :text="helperText" />
        </div>
        <div class="title2 primary">{{ title }}</div>
      </div>
      <w-button
        md
        :disabled="loading"
        class=""
        @click="onAddEntry"
        v-if="addable"
      >
        Add New
      </w-button>
    </div>
    <div class="title5 px6">
      <MetaBlock
        v-model:metadata="metadata"
        :disabled="loading"
        :removeable="removeable"
        :editablekey="editablekey"
        @onSubmit="onSubmit"
      />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import MetaBlock from '@/Pages/Study/Partials/Elements/Metadata/MetaBlock.vue'
import StudyInfoHelper from '@/Components/Popper/Study/StudyInfoHelper.vue'

const props = defineProps([
  'loading',
  'metadata',
  'title',
  'helperText',
  'removeable',
  'addable',
  'editablekey',
])
const emit = defineEmits(['onAddEntry', 'onSubmit'])

const onAddEntry = () => {
  emit('onAddEntry')
}

const onSubmit = () => {
  emit('onSubmit')
}

const exists_meta = computed(() => {
  return props?.metadata?.length
})
</script>

<style lang="scss" scoped></style>
