<template>
  <div class="default-layout-class">
    <slot />
    <FilesModal />
  </div>
</template>

<script>

import FilesModal from '@/Components/UploadModal/FilesModal.vue'

export default {
  components: {
    FilesModal
  }
}
</script>