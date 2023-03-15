<template>
  <!-- bounce -->
  <w-tooltip
    :bottom="bottom && !(top || left || right)"
    :top="top && !(bottom || left || right)"
    :left="left && !(top || bottom || right)"
    :right="right && !(top || left || bottom)"
    :align-right="true"
    transition="twist"
  >
    <template #activator="{ on }">
      <div class="info-tooltip">
        <w-button
          xs
          class="rounded-lg w-3 h-3"
          bg-color="cyan-dark1"
          icon="mdi mdi-help"
          v-on="on"
        >
        </w-button>
      </div>
    </template>
    <slot></slot>
  </w-tooltip>
</template>

<script setup>
const props = defineProps(['bottom', 'top', 'right', 'left'])
</script>

<style lang="scss">
.info-tooltip {
  .size--xs {
    height: 0.75rem;
    .w-icon {
      font-size: 9px;
      margin-bottom: 2px;
    }
  }
}
</style>
