<template>
  <InfoPopper :top="top" :left="left" :right="right" :bottom="bottom">
    <div>{{ text }}</div>
  </InfoPopper>
</template>

<script setup>
import InfoPopper from '@/Components/Popper/InfoPopper.vue'

const props = defineProps(['text', 'top', 'left', 'right', 'bottom'])
</script>
