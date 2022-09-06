<template>
  <div class="default-layout-class relative z-20">
    <slot />
    <FilesModal :title="title" />
  </div>
</template>

<script setup>

import FilesModal from '@/Components/UploadModal/FilesModal.vue'

const title = 'Upload Files';

</script>