<template>
  <w-tooltip bottom align-right transition="bounce">
    <template #activator="{ on }">
      <w-button
        xs
        class="rounded-lg"
        bg-color="cyan-dark1"
        icon="mdi mdi-help"
        v-on="on"
      >
      </w-button>
    </template>
    <slot></slot>
  </w-tooltip>
</template>

<script setup></script>

<style lang="scss" scoped></style>
