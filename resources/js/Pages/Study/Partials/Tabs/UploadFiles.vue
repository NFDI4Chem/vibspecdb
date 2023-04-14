<template>
  <div class="pb3 files-uploader-area">
    <div class="h-[400px]" v-if="layout_switcher.top_size()">
      <div
        v-if="showOverlay || !spectraData"
        class="overlay-progress flex p-5 h-full justify-center align-middle"
      >
        <w-progress class="ma1" circle color="light-blue-dark3"></w-progress>
      </div>
      <PlotlyPlotter v-else :input="spectraData" :showSqrtSd="false" />
      <!-- <SpectralPlotter
          v-else
          :key="plotKey"
          :data="spectraData"
          ref="spectral_plot"
        /> -->
    </div>

    <splitpanes
      @resized="e => onPaneResize(e)"
      :class="{ 'top-visible': layout_switcher.top_size() }"
      class="bg-slate-50"
    >
      <pane
        :size="layout_switcher.left_size()"
        class="flex flex-col items-start justify-start py-3 tree-plane"
      >
        <div class="w-full px-5">
          <div class="" v-if="treeFilled">
            <div class="relative flex flex-col w-full">
              <div class="min-w-fit">
                <div
                  class="flex flex-row justify-between items-center pb7 gap-2"
                >
                  <div class="flex flex-row justify-start align-middle w-full">
                    <div class="font-bold text-md mr-auto">
                      <div v-if="treeOptions.showTitle">
                        {{ treeOptions.title }}
                      </div>
                    </div>

                    <ToolTipWrapper text="Unfold All">
                      <template #btn>
                        <w-button
                          class="border-0 cursor-context-menu"
                          bg-color="transparent"
                          icon="mdi mdi-unfold-more-horizontal"
                          @click="unfoldAll"
                        ></w-button>
                      </template>
                    </ToolTipWrapper>
                    <ToolTipWrapper text="Fold All">
                      <template #btn>
                        <w-button
                          class="border-0 cursor-context-menu"
                          bg-color="transparent"
                          icon="mdi mdi-unfold-less-horizontal"
                          @click="foldAll"
                        ></w-button>
                      </template>
                    </ToolTipWrapper>

                    <TreeOptionSettings v-model:options="treeOptions" />
                  </div>
                  <TreeInfoPopper
                    :options="treeOptions"
                    v-if="treeOptions.showInfo"
                  />
                </div>
                <UniFilesTree
                  ref="uniFilesTree"
                  :tree="files"
                  :options="treeOptions"
                  :onRemoveItem="onRemoveItem"
                  :onAddChildren="onAddChildren"
                  :onChangeNodeType="onChangeNodeType"
                  :activeItem="activeItem"
                  :ondragend="onDragend"
                  @itemClick="TreeItemClick"
                  @onRename="onTreeChange"
                  @onCheck="onTreeCheck"
                  @onDrop="onDrop"
                />
                <!-- @change="onTreeChange" -->
              </div>
            </div>
          </div>
        </div>
      </pane>
      <pane :size="layout_switcher.right_size()">
        <div class="flex flex-col gap-2 h-full items-left p-4 py4">
          <div class="text-lg flex flex-col gap-1">
            <div
              class="flex flex-row flex-wrap gap-1 justify-between items-center"
            >
              <div class="text-bold text-md">
                Uploading files to the folder:
              </div>
              <UploaderInfoPopper :selectTreeFolder="selectTreeFolder" />
            </div>
            <div class="flex flex-row gap-2 items-center align-middle">
              <w-icon class="h-3 w-3 text-gray-500">mdi mdi-database</w-icon>
              <strong
                class="flex items-center text-sm text-gray-600 force-wrap"
              >
                {{ selectTreeFolder }}
              </strong>
            </div>
          </div>
          <Uploader :baseFolder="activeItem" @uploaded="onUploaded" />
        </div>
      </pane>
    </splitpanes>
  </div>
</template>

<script setup>
import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

import ToolTipWrapper from '@/Components/UniFilesTree/ToolTipWrapper.vue'
import UniFilesTree from '@/Components/UniFilesTree/UniFilesTree.vue'
import Uploader from '@/Components/UploadForm/Uploader.vue'
import UploaderInfoPopper from '@/Components/Popper/UploaderInfoPopper.vue'
import TreeInfoPopper from '@/Components/Popper/TreeInfoPopper.vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'

import SpectralPlotter from '@/Components/uPlot/SpectralPlotter.vue'
import PlotlyPlotter from '@/Components/plotly/PlotlyPlotter.vue'
import TreeOptionSettings from '@/Pages/Study/Partials/Elements/TreeOptionSettings.vue'

import { useFiles } from '@/VueComposable/useFiles'

import { ref, computed, watch } from 'vue'
import { useStore } from 'vuex'

const emit = defineEmits(['update:slit_views'])
const props = defineProps(['study', 'project', 'files', 'slit_views'])
const { showChildsAPI, extractzip, getSpectraData } = useFiles()

const selectTreeItem = ref()
const selectTreeFolder = ref('/')
const store = useStore()

const { getFilesListAPI, getPresignedURL, parseFile } = useFiles()

const treeFilled = computed(() => {
  return props?.files?.length > 0 && props?.files[0].children?.length > 0
})

const treeOptions = ref({
  checkable: true,
  deleteable: true,
  editable: true,
  createable: true,
  draggable: true,
  showInfo: true,
  showTitle: true,
  menuitem: true,
  title: 'Files Tree',
})

const activeItem = computed({
  get() {
    return store.getters.Treefiles.activeItem
  },
  set(val) {
    store.dispatch('updateFilesTreeData', { activeItem: val })
  },
})

const FilesUploaded = computed({
  get() {
    return store.state.Uppy.files.uploaded
  },
  set(val) {
    store.dispatch('updateFilesData', { uploaded: val })
  },
})

const study = computed(() => usePage().props.value.study)

watch(FilesUploaded, (newValue, oldValue) => {
  if (newValue) {
    MakeReload()
    // if (activeItem.value.id === 0) {
    //   MakeReload()
    // } else {
    //   showChildsAPI(activeItem.value)
    // }
  }
})

const MakeReload = () => {
  const form = useForm({
    files: null,
  })
  form.post(route('study.file-upload.update', study.value.id), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}

const onRemoveItem = (tree, node, path) => {
  node.loading = true
  const form = useForm(node)
  form.delete(route('files.destroy', node.id), {
    preserveScroll: true,
    onSuccess: () => {},
    onError: () => {},
    onFinish: () => {},
  })
}

const onTreeCheck = async checked => {
  const files = checked.filter(f => f.type === 'file')

  if (files?.length === 0) {
    return
  }

  showOverlay.value = true
  emit('update:slit_views', {
    ...props?.slit_views,
    top_visible: true,
  })

  const input = {
    files: files.map(f => ({
      src: f?.path,
      path: f?.relative_url,
    })),
  }

  const parsed = await getSpectraData(input)
  spectraData.value = parsed?.x?.map((plotX, idx) => {
    return {
      name: parsed?.filenames[idx],
      x: plotX,
      y: parsed?.y[idx],
      sd: [],
    }
  })
  showOverlay.value = false
}

const onChangeNodeType = (node, type) => {
  onTreeChange({ ...node, type }, false)
}

const onAddChildren = (node, type) => {
  node.loading = true
  const form = useForm(node)
  form
    .transform(data => {
      const name = type === 'directory' ? 'Folder' : 'Dataset'
      const parent_id = data?.id ?? 0
      const [ftype, size, uppyid] = ['directory', 0, '']
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
}

const spectraData = ref()
const showOverlay = ref(false)

const TreeItemClick = async (file, parent) => {
  const itemData = file.type === 'directory' ? file : parent
  displaySelected(itemData)
  storeSelected(itemData)

  if (file.type !== 'directory') {
    showOverlay.value = true
    emit('update:slit_views', {
      ...props?.slit_views,
      top_visible: true,
    })

    const input = {
      files: [
        {
          src: file?.path,
          path: file?.relative_url,
        },
      ],
    }
    const parsed = await getSpectraData(input)
    spectraData.value = parsed?.x?.map((plotX, idx) => {
      return {
        name: file.name,
        x: plotX,
        y: parsed?.y[idx],
        sd: [],
      }
    })
    showOverlay.value = false
  }
}

const onTreeChange = (node, report = true) => {
  console.log('onTreeChange', node)
  node.loading = true
  const form = useForm(node)
  form.put(route('files.update', node.id), {
    preserveScroll: true,
    onSuccess: () => {
      if (report) {
        setup_info_notify('The item has been updated')
      }
    },
    onError: () => {
      const message = Object.values(err).join('<br>')
      if (report) {
        setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
      }
    },
    onFinish: () => {
      node.loading = false
    },
  })
}

const onDrop = (node, pnode, pnode_old) => {
  onTreeChange({ ...node, parent_id: pnode?.id }, false)
}

const onDragend = (tree, store) => {
  const targetNode = tree.getNodeParentByPath(store.targetPath)
  const same_type = store?.dragNode?.type === targetNode?.type
  const allowed = ['directory', 'dataset'].includes(targetNode?.type)
  if (same_type && !allowed) {
    setup_error_notify(
      `Not possible to use this item as parent item for drag-and-drop action.`,
    )
  }
  return !same_type || allowed
}

const storeSelected = file => {
  activeItem.value = file
}

const onUploaded = (file, data) => {
  console.log('files uploaded (UploadFiles.vue)', file, data)
}

const displaySelected = file => {
  selectTreeItem.value = file

  let sFolder = '/'
  if (selectTreeItem.value.name == '/') {
    sFolder = '/'
  } else {
    if (selectTreeItem.value.type != 'file') {
      sFolder = selectTreeItem.value.name
    } else {
      sFolder =
        selectTreeItem.value.parent_id == null ? '/' : selectTreeItem.value.name
    }
  }

  selectTreeFolder.value = sFolder

  // disable it now:
  /*
  if (file.has_children && file.level > 0 && !file.children) {
    showChildsAPI(file)
  }
  */
}

const layout_switcher = computed(() => {
  const treeNotEmpty = treeFilled.value && props?.files?.length
  return {
    top_size: () => {
      return props?.slit_views?.top_visible
    },
    left_size: () => {
      if (!props?.slit_views?.right_visible) {
        return 100
      }
      if (treeNotEmpty && props?.slit_views?.left_visible) {
        return 45
      } else {
        return 0
      }
    },
    right_size: () => {
      if (!props?.slit_views?.left_visible || !treeNotEmpty) {
        return 100
      }
      return props?.slit_views?.right_visible ? 55 : 0
    },
  }
})

const uniFilesTree = ref()
const foldAll = () => {
  uniFilesTree.value.foldAll()
}
const unfoldAll = () => {
  uniFilesTree.value.unfoldAll()
}

const onPaneResize = () => {}
</script>

<style lang="scss">
.top-visible {
  height: calc(100% - 400px);
}

.overlay-progress {
  background-color: rgba(35, 71, 129, 0.1);
}

.files-uploader-area {
  height: calc(100vh - 238px);

  .tree-plane {
    height: 100%;
    overflow-y: auto;
  }

  .splitpanes__pane {
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.1) inset;
    // justify-content: center;
    // align-items: center;
    // display: flex;
    .splitpanes__pane {
      box-shadow: none;
    }
  }

  em.specs {
    font-size: 0.2em;
    line-height: 1;
    position: absolute;
    color: #bbb;
    bottom: 0.5em;
    left: 0;
    right: 0;
    text-align: center;
  }

  .splitpanes__splitter {
    touch-action: none;
    background-color: #fff;
    box-sizing: border-box;
    position: relative;
    flex-shrink: 0;
    &:before,
    &:after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      background-color: rgba(0, 0, 0, 0.15);
      transition: background-color 0.3s;
    }
    &:hover:before,
    &:hover:after {
      background-color: rgba(0, 0, 0, 0.25);
    }
    &:first-child {
      cursor: auto;
    }
  }

  &.splitpanes .splitpanes .splitpanes__splitter {
    z-index: 1;
  }
  &.splitpanes--vertical > .splitpanes__splitter,
  .splitpanes--vertical > .splitpanes__splitter {
    width: 7px;
    border-left: 1px solid #eee;
    margin-left: -1px;
    &:before,
    &:after {
      transform: translateY(-50%);
      width: 1px;
      height: 30px;
    }
    &:before {
      margin-left: -2px;
    }
    &:after {
      margin-left: 1px;
    }
  }
  &.splitpanes--horizontal > .splitpanes__splitter,
  .splitpanes--horizontal > .splitpanes__splitter {
    height: 7px;
    border-top: 1px solid #eee;
    margin-top: -1px;
    &:before,
    &:after {
      transform: translateX(-50%);
      width: 30px;
      height: 1px;
    }
    &:before {
      margin-top: -2px;
    }
    &:after {
      margin-top: 1px;
    }
  }
}
</style>

<style lang="scss" scoped>
.aside-menu {
  min-width: 300px;
}

.force-wrap {
  -ms-word-break: break-all;
  word-break: break-all;
  white-space: pre-wrap;
}
</style>
