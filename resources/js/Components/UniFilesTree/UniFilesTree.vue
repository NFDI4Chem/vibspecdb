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
      :class="{
        'active-node':
          node.id === activeItem.id && node.type === activeItem.type,
        'text-sky-800 text-bold': node.id === clickedItem?.id,
      }"
    >
      <ToolTipWrapper v-if="node.type === 'dataset'" text="The item is dataset">
        <template #btn>
          <div
            class="absolute top-[-1px] left-[-8px] w-1.5 h-1.5 bg-teal-500"
          />
        </template>
      </ToolTipWrapper>

      <ToolTipWrapper
        v-if="node.type === 'dataset' && checkMetaFiles(node)"
        text="The item has meta-files"
      >
        <template #btn>
          <div
            class="absolute top-[6px] left-[-8px] w-1.5 h-1.5 bg-green-500"
          />
        </template>
      </ToolTipWrapper>
      <div
        class="group flex justify-between gap-2 items-center whitespace-nowrap"
      >
        <div class="flex flex-row gap-2 items-center w-full">
          <div class="flex items-center" v-if="options.checkable">
            <w-checkbox
              :model-value="node.$checked"
              round
              color="cyan-dark3"
              @input="handleCheck(node, path, tree)"
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

            <ToolTipWrapper text="Click & Hold here to drag the item">
              <template #btn>
                <TButton
                  :type="node?.type"
                  :open="true"
                  :class="{ 'cursor-move': moveCursor(node) }"
                />
              </template>
            </ToolTipWrapper>
          </div>
          <div v-else class="flex flex-row align-middle">
            <w-icon
              v-if="node.has_children"
              class="text-gray-500 w-5 active-field"
              @click="toggleFold(tree, node, path)"
              >mdi mdi-chevron-right</w-icon
            >

            <ToolTipWrapper text="Click & Hold here to drag the item">
              <template #btn>
                <TButton
                  :type="node?.type"
                  :open="false"
                  :class="{ 'cursor-move': moveCursor(node) }"
                />
              </template>
            </ToolTipWrapper>
          </div>

          <input
            role="button"
            v-model="node.name"
            class="focus-visible:outline-none active-field w-full px-1 text-sm py-0.5"
            :class="{
              ['text-teal-500']: node.edit,
              'teal-dark1 text-bold': node.type === 'metafile',
            }"
            :readonly="node.edit ? false : 'readonly'"
            v-on:keydown="renameItemKeyBoard($event, node)"
            @click="onItemClick(node, path, tree)"
          />
        </div>
        <div class="hidden flex-row align-middle group-hover:flex">
          <div
            class="flex flex-row gap-1"
            v-if="
              !node.edit &&
              ['directory', 'dataset' /*, 'project', 'study'*/].includes(
                node.type,
              ) &&
              options.createable
            "
          >
            <w-button
              class="border-0 cursor-context-menu"
              bg-color="transparent"
              icon="mdi mdi-unfold-more-horizontal"
              @click="unfoldALevel(node)"
            ></w-button>

            <w-button
              class="border-0 cursor-context-menu"
              bg-color="transparent"
              icon="mdi mdi-unfold-less-horizontal"
              @click="foldALevel(node)"
            ></w-button>

            <ToolTipWrapper v-if="node.is_root" text="Add Dataset">
              <template #btn>
                <w-button
                  class="text-gray-500 w-5 cursor-pointer border-0"
                  bg-color="transparent"
                  icon="mdi mdi-database-plus"
                  @click="addChildrenDataset(node)"
                ></w-button>
              </template>
            </ToolTipWrapper>

            <ToolTipWrapper text="Add Folder">
              <template #btn>
                <w-button
                  class="text-gray-500 w-5 cursor-pointer border-0"
                  bg-color="transparent"
                  icon="mdi mdi-folder-plus"
                  @click="addChildren(node)"
                ></w-button>
              </template>
            </ToolTipWrapper>
          </div>

          <ToolTipWrapper
            v-if="!node.edit && node.name !== '/' && options.editable"
            text="Rename"
          >
            <template #btn>
              <w-button
                class="text-gray-500 w-5 cursor-pointer border-0"
                bg-color="transparent"
                icon="mdi mdi-rename"
                @click="node.edit = true"
              ></w-button>
            </template>
          </ToolTipWrapper>

          <ToolTipWrapper v-if="node.edit" text="Save changes">
            <template #btn>
              <w-button
                @click="
                  () => {
                    renameItem(node)
                  }
                "
                class="text-gray-500 w-5 font-bold cursor-pointer border-0"
                bg-color="transparent"
                icon="mdi mdi-check"
              ></w-button>
            </template>
          </ToolTipWrapper>
          <ToolTipWrapper
            v-if="!node.edit && node.name !== '/' && options.deleteable"
            text="This item and its childs will be deleted permanently."
          >
            <template #btn>
              <w-button
                v-on="on"
                class="text-red-400 w-5 ml1 cursor-pointer border-0"
                @click="removeItem(tree, node, path)"
                md
                bg-color="transparent"
                icon="mdi mdi-delete"
              ></w-button>
            </template>
            <template #icon>
              <w-button
                class="mx1"
                bg-color="error"
                xs
                icon="mdi mdi-exclamation"
              ></w-button>
            </template>
          </ToolTipWrapper>

          <ToolTipWrapper v-if="options.menuitem" text="Tree Item Options">
            <template #btn>
              <ItemSettings
                @onSelect="key => onChangeNodeType(node, key)"
                :type="node?.type"
              />
            </template>
          </ToolTipWrapper>
        </div>
      </div>
      <div
        v-if="node.loading"
        class="absolute backdrop-blur top-0 left-0 flex justify-center items-center z-10 w-full h-full"
      >
        <w-progress class="w-4" color="blue-grey" circle></w-progress>
        <!-- <w-spinner xs class="h-3" color="blue-grey" /> -->
      </div>
    </div>
  </Tree>
</template>

<script>
import { Tree, Fold, Draggable, Check } from 'he-tree-vue'
import TButton from '@/Components/UniFilesTree/TButton.vue'
import ItemSettings from '@/Components/UniFilesTree/ItemSettings.vue'
import ToolTipWrapper from '@/Components/UniFilesTree/ToolTipWrapper.vue'

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
          menuitem: false,
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
    onChangeNodeType: {
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
    clickedItem: {
      type: Object,
      required: false,
      default: () => {
        return {
          id: -1,
        }
      },
    },
    ondragend: {
      type: Function,
      required: false,
      default: () => {
        return false
      },
    },
  },
  components: {
    Tree: Tree.mixPlugins([Fold, Draggable, Check]),
    TButton,
    ItemSettings,
    ToolTipWrapper,
  },
  data() {
    return {}
  },
  mounted() {
    this.setChecked()
  },

  methods: {
    checkMetaFiles(node) {
      return node?.children?.filter(f => {
        return f.type === 'metafile'
      })?.length
    },
    unfoldAll() {
      this.$refs.tree.unfoldAll()
    },
    foldAll() {
      this.$refs.tree.foldAll()
    },
    unfoldALevel(node) {
      const tree = this.$refs.tree
      tree?.walkTreeData(_node => {
        if (
          _node?.parent?.id === node?.id &&
          ['dataset', 'directory'].includes(_node?.type)
        ) {
          tree.unfold(_node)
        }
      })
    },
    foldALevel(node) {
      const tree = this.$refs.tree
      tree?.walkTreeData(_node => {
        if (
          _node?.parent?.id === node?.id &&
          ['dataset', 'directory'].includes(_node?.type)
        ) {
          tree.fold(_node)
        }
      })
    },
    moveCursor(node) {
      return (
        [
          'directory',
          /*'project',*/ 'study',
          'file',
          'dataset',
          'metafile',
        ].includes(node.type) && this.options.draggable
      )
    },
    onItemClick(node, path, tree) {
      const parent = this.$refs.tree.getNodeParentByPath(path)
      if (!node.edit) {
        this.$emit('itemClick', node, parent)
      }
    },
    getTree() {
      return this.$refs.tree
    },
    handleCheck(node, path, tree) {
      tree.toggleCheck(node, path)
      const checked = this.getAllChecked(tree)
      this.$emit('onCheck', checked)
    },
    toggleFold(tree, node, path) {
      tree.toggleFold(node, path)
    },
    ondrop(store) {
      const parent_node = this.$refs.tree.getNodeParentByPath(store?.targetPath)
      const parent_node_old = this.$refs.tree.getNodeParentByPath(
        store?.startPath,
      )
      const item = store?.dragNode
      this.$emit('onDrop', item, parent_node, parent_node_old)
    },
    onBeforeDrop(tree) {
      console.log('onBeforeDrop', tree)
    },
    ondragend(tree, store) {
      return this.ondragend(tree, store)
    },
    renameItem(node) {
      node.edit = false
      this.$emit('onRename', node)
    },
    renameItemKeyBoard(event, node) {
      if (event.code === 'Enter') {
        node.edit = false
        this.$emit('onRename', node)
      }
    },
    removeItem(tree, node, path) {
      this.onRemoveItem(tree, node, path)
    },
    addChildren(node) {
      this.onAddChildren(node, 'directory')
    },
    addChildrenDataset(node) {
      this.onAddChildren(node, 'dataset')
    },
    handleTreeChange() {
      // const store = this.$refs.tree.treesStore.store
      // const { dragNode } = store
      // const targetNode = this.$refs.tree.getNodeParentByPath(store.targetPath)
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
  padding: 0 0 0 2px;
}

.active-node .active-field {
  color: rgb(13, 138, 172);
  font-weight: bold;
}
</style>
