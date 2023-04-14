<template>
  <w-tooltip bottom align-right tooltip-class="t-class">
    <template #activator="{ on }">
      <div v-on="on" class="flex align-middle">
        <slot name="btn"> </slot>
      </div>
    </template>
    <div class="inline-flex">
      <slot name="icon"></slot>
      <div class="whitespace-nowrap text-sm">{{ text }}</div>
    </div>
  </w-tooltip>
</template>

<script setup>
const props = defineProps(['text'])
</script>

<style lang="scss">
.t-class {
  max-width: none;
}
</style>
