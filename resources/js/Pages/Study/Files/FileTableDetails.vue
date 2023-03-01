<template>
  <div class="border border-gray-300 border-dashed border-t-transparent">
    <div class="py-4 w-1/2 flex justify-between px-10">
      <div class="relative font-bold w-100 h-5">{{ details.title }}</div>
      <ul class="list-decimal list-outside text-left">
        <li v-for="detail in details.items" :key="detail">
          {{ detail }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  details: {
    type: Object,
    required: false,
    default: () => {},
  },
  style: {
    type: Object,
    required: false,
    default: () => {
      return {
        greyRows: true,
        divideX: true,
        themeColor: 'cyan',
        rowActions: 'default',
      }
    },
  },
})
</script>

<style lang="scss" scoped></style>
