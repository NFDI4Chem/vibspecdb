<template>
  <div>
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
      @change="onTreeChange"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import TreeInfoPopper from '@/Components/Popper/TreeInfoPopper.vue'
import UniFilesTree from '@/Components/UniFilesTree/UniFilesTree.vue'

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
  /*
  node.loading = true
  const form = useForm(node)
  form.delete(route('files.destroy', node.id), {
    preserveScroll: true,
    onSuccess: () => {},
    onError: () => {},
    onFinish: () => {},
  })
  */
}

const onTreeChange = node => {
  /*
  node.loading = true
  const form = useForm(node)
  form.put(route('files.update', node.id), {
    preserveScroll: true,
    onSuccess: () => {},
    onError: () => {},
    onFinish: () => {},
  })
  */
}

const onAddChildren = node => {
  /*
  node.loading = true
  const form = useForm(node)
  form
    .transform(data => {
      const name = 'NewFolder'
      const parent_id = data?.id ?? 0
      const type = 'directory'
      const [ftype, size, uppyid] = ['folder', 0, '']
      const { project_id, study_id, level } = data
      return {
        name,
        parent_id,
        type,
        ftype,
        size,
        uppyid,
        project_id: parseInt(project_id),
        study_id: parseInt(study_id),
        level: parseInt(level) + 1,
      }
    })
    .post(route('files.create'), {
      preserveScroll: true,
      onSuccess: file => {
        node.loading = false
      },
      onError: p => {
        console.log('onAddChildren', p)
      },
    })
    */
}
</script>

<style lang="scss" scoped></style>
