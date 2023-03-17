<template>
  <w-form
    v-model="form_helpers.valid"
    v-model:errors-count="form_helpers.errorsCount"
    @submit.prevent="onSubmit"
    class="flex flex-col gap-10"
  >
    <div class="metadata-wrapper">
      <div v-for="(meta, idx) in metadata" :key="meta.key">
        <div class="flex flex-row gap-6 py4">
          <meta-info-element
            v-model:meta="metadata[idx]"
            class="w-full"
            :editablekey="editablekey"
          />

          <div
            class="mt-auto w-[20px]"
            @click="deleteRow(idx)"
            v-if="removeable"
          >
            <w-button
              class="text-gray-600"
              sm
              bg-color="error-light3"
              icon="wi-cross"
              :disabled="disabled"
            />
          </div>
        </div>
      </div>
    </div>
    <div v-if="exists_meta" class="flex flex-row justify-end my6">
      <w-button lg :loading="disabled" class="" type="submit"> Save </w-button>
    </div>
  </w-form>
</template>

<script setup>
import { ref, computed } from 'vue'
import MetaInfoElement from '@/Pages/Study/Partials/Elements/Metadata/MetaInfoElement.vue'

const emit = defineEmits(['metadata:update', 'onSubmit'])

const props = defineProps({
  metadata: {
    type: Object,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
    required: false,
  },
  removeable: {
    type: Boolean,
    default: true,
    required: false,
  },
  editablekey: {
    type: Boolean,
    default: true,
    required: false,
  },
})

const onSubmit = () => {
  if (!form_helpers.value.valid) {
    console.log('!form_helpers.value.valid')
    return false
  } else {
    console.log('form_helpers.value.valid')
    emit('onSubmit')
  }
}
const form_helpers = ref({
  valid: null,
  submitted: false,
  sent: false,
  errorsCount: 0,
})

const deleteRow = idx => {
  emit('metadata:update', props?.metadata.splice(idx, idx !== -1 ? 1 : 0))
}

const exists_meta = computed(() => {
  return props?.metadata?.length
})
</script>

<style lang="scss" scoped></style>
