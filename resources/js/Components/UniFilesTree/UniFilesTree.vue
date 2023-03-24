<template>
  <Tree
    ref="tree"
    :value="tree"
    v-slot="{ node, index, path, tree }"
    :draggable="options.draggable"
    :edgeScroll="true"
    :foldAllAfterMounted="false"
    @drop="ondrop"
    @before-drop="onBeforeDrop"
    :rootNode="{ $droppable: false, $draggable: true }"
    @change="handleTreeChange"
    class="files-tree"
  >
    <div
      class="relative z-0"
      :class="{ ['active-node']: node.id === activeItem.id }"
    >
      <div
        class="flex justify-between gap-2 items-center whitespace-nowrap ml2"
      >
        <div
          @click="onItemClick(node, path, tree)"
          class="flex flex-row gap-2 items-center w-full"
        >
          <div class="flex items-center" v-if="options.checkable">
            <input
              type="checkbox"
              :checked="node.$checked"
              @change="handleCheck(node, path, tree)"
            />
          </div>
          <b v-if="node.type === 'directory'">
            <ChevronRightIcon
              @click="toggleFold(tree, node, path)"
              v-if="node.$folded"
              class="text-gray-800 w-5"
            />
            <ChevronDownIcon
              @click="toggleFold(tree, node, path)"
              v-if="!node.$folded"
              class="text-gray-800 w-5"
            />
          </b>
          <FolderIcon
            v-if="node.type === 'directory'"
            class="text-gray-500 w-5 active-field"
          />
          <DocumentTextIcon
            v-if="node.type === 'file'"
            class="text-gray-500 w-4 active-field"
          />
          <input
            role="button"
            v-model="node.name"
            class="focus-visible:outline-none active-field w-full"
            :class="{ ['text-teal-500']: node.edit }"
            :readonly="node.edit ? false : 'readonly'"
            v-on:keydown="renameItemKeyBoard($event, node)"
          />
        </div>
        <div class="flex flex-row align-middle">
          <PlusSmallIcon
            v-if="!node.edit && node.type === 'directory' && options.createable"
            class="text-gray-500 w-6"
            @click="addChildren(node)"
          />
          <PencilIcon
            v-if="!node.edit && node.name !== '/' && options.editable"
            @click="node.edit = true"
            class="text-gray-500 w-4"
          />
          <CheckIcon
            v-if="node.edit"
            @click="
              () => {
                node.edit = false
                renameItem(node)
              }
            "
            class="text-gray-500 w-4 font-bold"
          />
          <PlusSmallIcon
            v-if="node.edit && false"
            class="text-gray-400 w-6 rotate-45"
            @click="node.edit = false"
          />
          <w-tooltip
            bottom
            align-left
            v-if="!node.edit && node.name !== '/' && options.deleteable"
          >
            <template #activator="{ on }">
              <w-icon
                v-on="on"
                class="text-red-400 h-6 w-4 ml1 cursor-pointer"
                @click="removeItem(tree, node, path)"
                md
              >
                mdi mdi-delete
              </w-icon>
            </template>
            <div class="inline-flex">
              <w-button
                class="mx1"
                bg-color="error"
                xs
                icon="mdi mdi-exclamation"
              ></w-button>
              <div class="whitespace-nowrap">
                This item and its childs will be deleted permanently.
              </div>
            </div>
          </w-tooltip>
        </div>
      </div>
      <div
        v-if="node.loading"
        class="absolute backdrop-blur top-0 left-0 flex justify-center items-center z-10 w-full h-full"
      >
        <w-progress class="w-6" color="blue-grey" circle></w-progress>
        <!-- <w-spinner xs class="h-3" color="blue-grey" /> -->
      </div>
    </div>
  </Tree>
</template>

<script>
import { Tree, Fold, Draggable, Check } from 'he-tree-vue'

import {
  PencilIcon,
  PlusSmallIcon,
  FolderIcon,
  DocumentTextIcon,
  ChevronDownIcon,
  ChevronRightIcon,
  CheckIcon,
  TrashIcon,
  InformationCircleIcon,
} from '@heroicons/vue/24/solid'

export default {
  props: {
    tree: {
      type: Object,
      required: true,
    },
    options: {
      type: Object,
      required: false,
      default: () => {
        return {
          checkable: false,
          deleteable: true,
          editable: true,
          createable: true,
          draggable: true,
          showInfo: true,
          title: 'Files Tree',
        }
      },
    },
    onRemoveItem: {
      type: Function,
      required: false,
      default: () => {},
    },
    onAddChildren: {
      type: Function,
      required: false,
      default: () => {},
    },
    checked: {
      type: Array,
      required: false,
      default: () => [],
    },
    activeItem: {
      type: Object,
      required: false,
      default: () => {
        return {
          id: -1,
        }
      },
    },
  },
  components: {
    Tree: Tree.mixPlugins([Fold, Draggable, Check]),
    PencilIcon,
    PlusSmallIcon,
    FolderIcon,
    DocumentTextIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    CheckIcon,
    TrashIcon,
    InformationCircleIcon,
  },
  data() {
    return {}
  },
  mounted() {
    this.setChecked()
  },
  methods: {
    onItemClick(node, path, tree) {
      const parent = this.$refs.tree.getNodeParentByPath(path)
      this.$emit('itemClick', node, parent)
    },
    handleCheck(node, path, tree) {
      tree.toggleCheck(node, path)
      const checked = this.getAllChecked(tree)
      this.$emit('onCheck', checked)
    },
    toggleFold(tree, node, path) {
      tree.toggleFold(node, path)
    },
    ondrop(tree) {
      console.log('ondrop', tree)
    },
    onBeforeDrop(tree) {
      console.log('onBeforeDrop', tree)
    },
    renameItem(node) {
      // console.log(node);
      this.$emit('change', node)
    },
    renameItemKeyBoard(event, node) {
      if (event.code === 'Enter') {
        this.$emit('change', node)
      }
    },
    removeItem(tree, node, path) {
      this.onRemoveItem(tree, node, path)
    },
    addChildren(node) {
      this.onAddChildren(node)
    },
    handleTreeChange() {
      const store = this.$refs.tree.treesStore.store
      const { dragNode } = store
      const targetNode = this.$refs.tree.getNodeParentByPath(store.targetPath)
      // get the parent of dragNode
      // this.$refs.tree.getNodeParentByPath(store.startPath)
      console.log('drag', dragNode)
      console.log('target', targetNode)
    },
    getAllChecked(tree) {
      const checked = []
      tree.walkTreeData(node => {
        node.$checked && checked.push(node)
      })
      return checked
    },
    setChecked() {
      const tree = this.$refs.tree
      tree.walkTreeData(node => {
        if (this.checked.includes(node.id)) {
          node.$checked = true
        }
      })
    },
  },
}
</script>

<style lang="scss" scoped>
.tree-item {
  color: black;
}
</style>

<style lang="scss">
.files-tree .tree-node {
  // padding: 0 !important;
  min-width: 300px;
}

.active-node .active-field {
  color: rgb(13, 138, 172);
  font-weight: bold;
}
</style>
