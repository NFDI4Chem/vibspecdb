<template>
  <w-menu right align-top>
    <template #activator="{ on }">
      <w-button
        v-on="on"
        class="border-0 cursor-context-menu"
        bg-color="transparent"
        icon="mdi mdi-dots-vertical"
      ></w-button>
    </template>
    <w-list
      v-model="selection"
      :items="items"
      color="primary"
      checklist
      item-value-key="key"
      class="text-md"
      @input="onInput"
    >
    </w-list>
  </w-menu>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps(['options'])
const emit = defineEmits(['update:options'])

const items_all = [
  { label: 'Checkable', key: 'checkable', shown: true },
  { label: 'Deleteable', key: 'deleteable', shown: true },
  { label: 'Editable', key: 'editable', shown: true },
  { label: 'Createable', key: 'createable', shown: true },
  { label: 'Draggable', key: 'draggable', shown: true },
  { label: 'Show Info', key: 'showInfo', shown: true },
  { label: 'Show Title', key: 'showTitle', shown: true },
]

const check_options = computed(() => Object.keys(props?.options))

const default_keys = items_all.map(item => item.key)

const check_options_active = computed(() =>
  check_options.value.filter(
    opt => props?.options[opt] && default_keys.includes(opt),
  ),
)

const items = computed(() => {
  return items_all.filter(item => check_options.value.includes(item.key))
})

const selection = ref(check_options_active)

const onInput = active => {
  let opts = {}
  items.value.map(item => {
    opts[item.key] = active.includes(item.key)
  })
  const res = {
    ...props?.options,
    ...opts,
  }
  emit('update:options', res)
}
</script>

<style lang="scss" scoped></style>
