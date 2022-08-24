<template>
  <div class="default-layout-class relative">
    <slot />
    <FilesModal :title="title" />
  </div>
</template>

<script setup>

import FilesModal from '@/Components/UploadModal/FilesModal.vue'

const title = 'Upload Files';

</script>