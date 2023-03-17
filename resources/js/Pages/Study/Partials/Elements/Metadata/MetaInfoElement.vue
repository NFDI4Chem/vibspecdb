<template>
  <div>
    <div class="flex flex-row gap-6 align-baseline">
      <w-input
        required
        v-model="meta.param"
        label="Parameter"
        inner-icon-left="mdi mdi-key-outline"
        :disabled="!editablekey"
        :validators="[validators.required]"
      >
      </w-input>
      <w-input
        required
        v-model="meta.value"
        label="Value"
        :validators="[validators.required]"
      >
      </w-input>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps(['meta', 'editablekey'])

const validators = {
  required: value => !!value || 'This field is required',
  consent: value => !!value || 'You must agree',
}

const types = [
  { label: 'string' },
  { label: 'integer' },
  // { label: 'collection' },
  // { label: 'json' },
  { label: 'array' },
  { label: 'boolean' },
]
</script>

<style lang="scss" scoped></style>
