<template>
  <!-- bounce -->

  <w-tooltip :[position]="true" :align-right="true" transition="twist">
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
const props = defineProps(['position'])
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
