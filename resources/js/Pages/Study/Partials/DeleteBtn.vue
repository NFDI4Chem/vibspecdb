<template>
  <w-confirm
    question="Are you sure you want to delete your study? Once the 
          study is deleted, all of its resources and data will be
          permanently deleted."
    bg-color="error-light3"
    md
    @confirm="deleteStudy"
    :disabled="form.processing"
    transition="scale"
  >
    <slot></slot>
  </w-confirm>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'

import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const props = defineProps({
  sid: {
    type: [Number, String],
    default: -1,
    required: true,
  },
})

const form = useForm({})

const deleteStudy = () => {
  form.delete(route('study.destroy', props?.sid), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      setup_info_notify('The Study deleted successfully')
    },
    onError: () => {
      setup_error_notify('Failed to Delete the Study')
    },
    onFinish: () => form.reset(),
  })
}
</script>
