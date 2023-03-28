<template>
  <Tree
    ref="tree"
    :value="tree"
    v-slot="{ node, index, path, tree }"
    :draggable="options.draggable"
    :edgeScroll="true"
    :foldAllAfterMounted="false"
    @drop="ondrop"
    :rootNode="{ $droppable: false, $draggable: true }"
    @before-drop="onBeforeDrop"
    :ondragend="ondragend"
    @change="handleTreeChange"
    class="files-tree"
  >
    <div
      class="relative z-0"
      :class="{ ['active-node']: node.id === activeItem.id }"
    >
      <div
        class="group flex justify-between gap-2 items-center whitespace-nowrap"
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

          <div
            v-if="!node.$folded && node.has_children"
            class="flex flex-row align-middle"
          >
            <w-icon
              class="text-gray-500 w-5 active-field"
              @click="toggleFold(tree, node, path)"
              >mdi mdi-chevron-down</w-icon
            >
            <TButton :type="node?.type" :open="true" />
          </div>
          <div v-else class="flex flex-row align-middle">
            <w-icon
              v-if="node.has_children"
              class="text-gray-500 w-5 active-field"
              @click="toggleFold(tree, node, path)"
              >mdi mdi-chevron-right</w-icon
            >
            <TButton
              :type="node?.type"
              :open="false"
              :class="{ 'cursor-move': moveCursor(node) }"
            />
          </div>

          <input
            role="button"
            v-model="node.name"
            class="focus-visible:outline-none active-field w-full px-1"
            :class="{ ['text-teal-500']: node.edit }"
            :readonly="node.edit ? false : 'readonly'"
            v-on:keydown="renameItemKeyBoard($event, node)"
          />
        </div>
        <div class="hidden flex-row align-middle group-hover:flex">
          <w-icon
            v-if="
              !node.edit &&
              ['directory', 'project', 'study'].includes(node.type) &&
              options.createable
            "
            class="text-gray-500 w-5 cursor-pointer"
            @click="addChildren(node)"
            >mdi mdi-folder-plus</w-icon
          >
          <w-icon
            v-if="!node.edit && node.name !== '/' && options.editable"
            class="text-gray-500 w-5 cursor-pointer"
            @click="node.edit = true"
            >mdi mdi-rename</w-icon
          >
          <w-icon
            v-if="node.edit"
            @click="
              () => {
                node.edit = false
                renameItem(node)
              }
            "
            class="text-gray-500 w-5 font-bold cursor-pointer"
            >mdi mdi-check</w-icon
          >

          <w-tooltip
            bottom
            align-left
            v-if="!node.edit && node.name !== '/' && options.deleteable"
            tooltip-class="t-class"
          >
            <template #activator="{ on }">
              <w-icon
                v-on="on"
                class="text-red-400 w-5 ml1 cursor-pointer"
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
import TButton from '@/Components/UniFilesTree/TButton.vue'

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
    TButton,
  },
  data() {
    return {}
  },
  mounted() {
    this.setChecked()
  },

  methods: {
    moveCursor(node) {
      return (
        ['directory', 'project', 'study'].includes(node.type) &&
        this.options.draggable
      )
    },
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
    ondragend(tree, store) {
      const targetNode = tree.getNodeParentByPath(store.targetPath)
      const same_type = store?.dragNode?.type === targetNode?.type
      return !same_type
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
      // console.log('drag', dragNode)
      // console.log('target', targetNode)
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
  // min-width: 300px;
  padding: 0 2px;
}

.active-node .active-field {
  color: rgb(13, 138, 172);
  font-weight: bold;
}

.t-class {
  max-width: none;
}
</style>
