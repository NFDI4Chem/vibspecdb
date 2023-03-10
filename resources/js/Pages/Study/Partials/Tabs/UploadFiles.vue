<template>
  <div class="py-3 files-uploader-area flex">
    <splitpanes class="bg-gray-50" @resized="e => onPaneResize(e)">
      <pane
        :min-size="treeFilled && files?.length ? 30 : 0"
        :size="treeFilled && files?.length ? 45 : 0"
        class="flex flex-col items-start justify-start px-5 py-3 tree-plane"
      >
        <div class="w-full">
          <div class="" v-if="treeFilled">
            <div class="relative flex flex-col w-full">
              <div class="min-w-fit">
                <div
                  class="flex flex-row justify-between items-center pb7 gap-2"
                >
                  <div class="font-bold text-md">
                    <div v-if="treeOptions.showTitle">
                      {{ treeOptions.title }}
                    </div>
                  </div>
                  <TreeInfoPopper :options="treeOptions" />
                </div>
                <UniFilesTree
                  @itemClick="TreeItemClick"
                  :tree="files"
                  :options="treeOptions"
                  :onRemoveItem="onRemoveItem"
                  :onAddChildren="onAddChildren"
                  :activeItem="activeItem"
                  @change="onTreeChange"
                />
              </div>
            </div>
          </div>
        </div>
      </pane>
      <pane min-size="25">
        <splitpanes horizontal>
          <pane min-size="0" size="40">
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
                  <CircleStackIcon class="h-4 w-4 text-gray-500" />
                  <strong
                    class="flex items-center text-sm text-gray-600 force-wrap"
                  >
                    {{ selectTreeFolder }}
                  </strong>
                </div>
              </div>
              <Uploader
                :width="sectionWidth"
                :baseFolder="activeItem"
                @uploaded="onUploaded"
              />
            </div>
          </pane>
          <pane min-size="0">
            <div class="p-5">
              <SpectralPlotter
                :key="plotKey"
                v-if="spectraData"
                :data="spectraData"
                ref="spectral_plot"
              />
            </div>
          </pane>
        </splitpanes>
      </pane>
    </splitpanes>
  </div>
</template>

<script setup>
import {
  ChevronRightIcon,
  HomeIcon,
  InformationCircleIcon,
  CircleStackIcon,
} from '@heroicons/vue/24/solid'

import JetButton from '@/Jetstream/Button.vue'
import StudyContent from '@/Pages/Study/Content.vue'
import UniFilesTree from '@/Components/UniFilesTree/UniFilesTree.vue'
import Uploader from '@/Components/UploadForm/Uploader.vue'
import UploaderInfoPopper from '@/Components/Popper/UploaderInfoPopper.vue'
import TreeInfoPopper from '@/Components/Popper/TreeInfoPopper.vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'

import SpectralPlotter from '@/Components/uPlot/SpectralPlotter.vue'

import { useFiles } from '@/VueComposable/useFiles'

import { ref, computed, onMounted, reactive, watch } from 'vue'
import { useStore } from 'vuex'

const props = defineProps(['study', 'project', 'files'])
const { showChildsAPI, extractzip, getSpectraData } = useFiles()

const selectTreeItem = ref()
const selectTreeFolder = ref('/')
const store = useStore()

const { getFilesListAPI, getPresignedURL, parseFile } = useFiles()

const job_files = ref({})

const treeFilled = computed(() => {
  return props?.files?.length > 0 && props?.files[0].children?.length > 0
})

const treeOptions = {
  checkable: false,
  deleteable: true,
  editable: true,
  createable: true,
  draggable: false,
  showInfo: true,
  showTitle: true,
  title: 'Files Tree',
}

const sectionRef = ref()
const sectionWidth = ref()

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
    if (activeItem.value.id === 0) {
      MakeReload()
    } else {
      showChildsAPI(activeItem.value)
    }
  }
})

const MakeReload = () => {
  const form = useForm({
    email: null,
  })
  form.post(route('study.file-upload.update', study.value.id), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}

onMounted(() => {
  // sectionWidth.value = sectionRef.value.clientWidth;
})

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

const onTreeChange = node => {
  node.loading = true
  const form = useForm(node)
  form.put(route('files.update', node.id), {
    preserveScroll: true,
    onSuccess: () => {},
    onError: () => {},
    onFinish: () => {},
  })
}

const onAddChildren = node => {
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
}

const spectraData = ref()

const TreeItemClick = async (file, parent) => {
  const itemData = file.type === 'directory' ? file : parent
  displaySelected(itemData)
  storeSelected(itemData)

  if (file.type !== 'directory') {
    const input = {
      files: [
        {
          src: file?.path,
          path: file?.relative_url,
        },
      ],
    }
    const parsed = await getSpectraData(/*inputData.value*/ input)
    spectraData.value = parsed?.x?.map((plotX, idx) => {
      return {
        name: file.name,
        x: plotX,
        y: parsed?.y[idx],
        sd: [],
      }
    })
  }
}

const storeSelected = file => {
  activeItem.value = file
}

const onUploaded = (file, data) => {
  console.log('files uploaded (UploadFiles.vue)', file, data)
}

const displaySelected = file => {
  selectTreeItem.value = file
  // console.log('file', file)

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

  if (file.has_children && file.level > 0 && !file.children) {
    showChildsAPI(file)
  }
}

const onExtractZip = () => {
  const file = {
    id: 15,
  }
  extractzip(file)
}

const plotKey = ref(0)
const onPaneResize = e => {
  plotKey.value++
}

const inputData = ref({
  files: [
    {
      src: 'data1/data2/zips/130208_093455_E. coli DSM 10806 Ascites I_sort/SpRaw_130208_093808.txt',
      path: '/130208_093455_E. coli DSM 10806 Ascites I_sort/SpRaw_130208_093808.txt',
    },
    {
      src: 'data1/data2/zips/paracetamol/SpRaw_130208_093143.txt',
      path: '/paracetamol/SpRaw_130208_093143.txt',
    },
    {
      src: 'data1/data2/zips/metadata.xlsx',
      path: '/metadata.xlsx',
    },
  ],
  rootpath: 'data1/data2/zips/ascitic-fluid_bacteria_minimal.zip',
  local: false,
})
</script>

<style lang="scss">
.files-uploader-area {
  height: calc(100vh - 295px);

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
