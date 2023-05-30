<template>
  <InfoPopper :position="position">
    <div>{{ text }}</div>
  </InfoPopper>
</template>

<script setup>
import InfoPopper from '@/Components/Popper/InfoPopper.vue'

const props = defineProps(['text', 'position'])
</script>
