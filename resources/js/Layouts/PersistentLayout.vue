<template>
  <div class="default-layout-class">
    <slot />
    <FilesModal :title="title"/>
  </div>
</template>

<script setup>

import FilesModal from '@/Components/UploadModal/FilesModal.vue'

const title = 'Upload Some Files';

</script>