<template>
  <div v-if="items?.length">
    <div class="flex flex-row justify-between items-center pb7 gap-2">
      <div class="font-bold text-md">
        <div v-if="treeOptions.showTitle">
          {{ treeOptions.title }}
        </div>
      </div>
      <TreeInfoPopper :options="treeOptions" />
    </div>
    <UniFilesTree
      @itemClick="TreeItemClick"
      :tree="items"
      :options="treeOptions"
      :onRemoveItem="onRemoveItem"
      :onAddChildren="onAddChildren"
      :activeItem="activeItem"
      @onRename="onRename"
      @onDrop="onDrop"
      :ondragend="onDragend"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

import TreeInfoPopper from '@/Components/Popper/TreeInfoPopper.vue'
import UniFilesTree from '@/Components/UniFilesTree/UniFilesTree.vue'

import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const props = defineProps(['items'])

const treeOptions = {
  checkable: false,
  deleteable: true,
  editable: true,
  createable: true,
  draggable: true,
  showInfo: true,
  showTitle: true,
  title: 'Projects Tree',
}

const activeItem = ref({})

const TreeItemClick = (file, parent) => {
  console.log(file, parent)
}

const onRemoveItem = (tree, node, path) => {
  console.log('node', node)
  deleteProject(node)
}

const onAddChildren = node => {}

const onDrop = (node, pnode, pnode_old) => {
  console.log('onDrop', node, pnode)
  onChange(node, { project_id: pnode?.id })
}

const onDragend = (tree, store) => {
  console.log('onDragend')
  const targetNode = tree.getNodeParentByPath(store.targetPath)
  const same_type = store?.dragNode?.type === targetNode?.type
  if (same_type) {
    setup_error_notify(
      `Not possible to use this item as parent item for drag-and-drop action.`,
    )
  }
  return !same_type
}

const onRename = node => {
  console.log('onRename', node)
  console.log('onRename', node)
  onChange(node, { name: node?.name })
}

const deleteProject = node => {
  const path =
    node?.type === 'project'
      ? route('project.destroy', node?.id)
      : node?.type === 'study'
      ? route('study.destroy', node?.id)
      : 'nodefined'

  Inertia.delete(path, {
    preserveScroll: true,
    onSuccess: () => {
      setup_info_notify('Item has been deleted successfully.')
    },
    onError: err => {
      const message = Object.values(err).join('<br>')
      setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
    },
    onFinish: () => {},
  })
}

const onChange = (node, data) => {
  // console.log('onChange', data)
  const path =
    node?.type === 'project'
      ? route('projects.update', node?.id)
      : node?.type === 'study'
      ? route('studies.update', node?.id)
      : 'nodefined'

  node.loading = true
  Inertia.put(path, data, {
    errorBag: 'treeUpdate',
    preserveScroll: true,
    onSuccess: () => {
      setup_info_notify(`The ${node?.type} has been successfully updated.`)
    },
    onError: err => {
      node.loading = false
      const message = Object.values(err).join('<br>')
      setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
    },
    onFinish: () => {
      node.loading = false
    },
  })
}
</script>

<style lang="scss" scoped></style>
