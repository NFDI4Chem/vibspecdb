<template>
  <div class="" :key="imgKey">
    <w-form
      @success="onFormSuccess"
      class="flex flex-row gap-12 justify-between"
    >
      <w-image
        class=""
        :src="imgSrc ? imgSrc : defaultImg"
        :fallback="defaultImg"
        :width="120"
        :height="120"
      />
      <w-input
        type="file"
        name="image"
        v-model="files"
        class="grow"
        :validators="[() => files.length || 'Please add a file']"
      >
        File
      </w-input>

      <div class="flex-col items-center align-middle justify-between">
        <w-button
          type="submit"
          md
          :loading="loading"
          class="d-flex mla mt2"
          :width="100"
        >
          Upload
        </w-button>
        <w-button
          bg-color="error"
          md
          :width="100"
          :loading="loading_destroy"
          class="d-flex mla mt2"
          @click="deleteImage"
        >
          Delete
        </w-button>
      </div>
    </w-form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

import { Inertia } from '@inertiajs/inertia'
import { useForm, usePage } from '@inertiajs/inertia-vue3'

const props = defineProps(['item', 'type', 'defaultImg'])
const imgKey = ref(0)
const files = ref([])
const loading = ref(false)
const loading_destroy = ref(false)
const errors = ref({ image: '' })
const imgSrc = computed(() => {
  return props?.item?.photo_url
})

const deleteImage = () => {
  loading_destroy.value = true
  const form = useForm({
    type: props?.type,
    itemId: props?.item?.id,
  })
  form.post(route('image.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      setup_info_notify('The Image deleted successfully')
      loading_destroy.value = false
    },
    onError: () => {
      setup_error_notify('Failed to Delete the Image')
      loading_destroy.value = false
    },
    onFinish: () => form.reset(),
  })
}

const onFormSuccess = async () => {
  console.log('try submit')
  errors.value = {}
  loading.value = true

  const form = useForm({
    _method: 'PUT',
    image: files.value[0],
    type: props?.type,
    itemId: props?.item?.id,
  })

  form.post(route('image.store'), {
    errorBag: 'imageStore',
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      loading.value = false
      setup_info_notify('The image has been successfully uploaded!')
    },
    onError: err => {
      loading.value = false
      const message = Object.values(err).join('<br>')
      setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
    },
    onFinish: () => {
      files.value = false
      imgKey.value++
    },
  })
}
</script>

<style lang="scss" scoped></style>
