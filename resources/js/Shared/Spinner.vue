<template>
  <div
    class="relative flex justify-center items-center ointer-events-none"
  >
    <div class="absolute bg-gray-100 opacity-100 min-w-full min-h-full"></div>
    <div
      :class="`animate-spin rounded-full h-${cradius} w-${cradius} border-b-2 border-teal-500 z-12`"
    ></div>
  </div>
</template>

<script setup>
const props = defineProps({
  style: {
    type: Object,
    required: false,
    default: () => {
      return {
        themeColor: "cyan",
      };
    },
  },
  cradius: {
    type: Number,
    required: false,
    default: 32,
  },
});
</script>

<style lang="scss" scoped></style>
