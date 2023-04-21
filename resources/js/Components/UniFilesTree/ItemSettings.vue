<template>
  <w-menu
    v-if="items"
    bottom
    align-right
    hide-on-menu-click
    class="tree-item-setting-dots"
  >
    <template #activator="{ on }">
      <w-button
        v-on="on"
        class="border-0 cursor-context-menu"
        bg-color="transparent"
        icon="mdi mdi-dots-vertical"
      ></w-button>
    </template>
    <w-flex wrap align-center class="p-0">
      <w-list
        hover
        :items="items"
        color="primary"
        item-value-key="key"
        item-class="px3 py0 cursor-pointer"
      >
        <template #item="{ item }">
          <w-flex
            align-center
            justify-space-between
            class="align-middle gap-2"
            @click="onSelect(item?.key)"
          >
            <w-icon md>{{ item?.icon }}</w-icon>
            <div class="pt1">{{ item?.label }}</div>
          </w-flex>
          <!-- {{ props.type }} -->
        </template>
      </w-list>
    </w-flex>
  </w-menu>
</template>

<script setup>
import { ref, computed } from 'vue'

const emit = defineEmits(['onSelect'])
const props = defineProps(['type'])

const _items = [
  {
    label: 'Convert to Folder',
    key: 'directory',
    icon: 'mdi mdi-folder-plus',
  },
  {
    label: 'Convert to Dataset',
    key: 'dataset',
    icon: 'mdi mdi-database-plus',
  },
  {
    label: 'Convert to Metafile',
    key: 'metafile',
    icon: 'mdi mdi-table',
  },
  {
    label: 'Convert to File',
    key: 'file',
    icon: 'mdi mdi-file-document',
  },
]

const firstSet = ['file', 'metafile']
const secondSet = ['dataset', 'directory']

const items = computed(() => {
  if (firstSet.includes(props.type)) {
    return _items
      .filter(f => firstSet.includes(f.key))
      .filter(i => i.key !== props.type)
  }
  if (secondSet.includes(props.type)) {
    return _items
      .filter(f => secondSet.includes(f.key))
      .filter(i => i.key !== props.type)
  }
  return []
})

const onSelect = key => {
  emit('onSelect', key)
}
</script>

<style lang="scss">
.tree-item-setting-dots {
  .w-card__content {
    padding: 0;
  }
}
</style>
