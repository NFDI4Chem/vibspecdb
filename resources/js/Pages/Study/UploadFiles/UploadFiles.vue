<template>
  <!-- <w-app> -->
  <study-content :project="project" :study="study" current="Upload Files">
    <template #study-section>
      <div class="h-100 px-5 py-3 files-uploader-area">
        <div class="w-100 h-20 bg-gray-500">Metadata here</div>

        <splitpanes class="bg-gray-50" v-if="files?.length || true">
          <pane
            min-size="20"
            class="flex flex-col items-start justify-start px-5 py-3"
          >
            <!-- <div class="w-10 h-2 bg-blue-300"></div> -->
            <div class="my2">
              <div class="" v-if="treeFilled">
                <div class="aside-menu relative flex flex-col overflow-y-auto">
                  <div class="mr5 min-w-fit">
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
          <pane min-size="20">
            <section
              class="min-w-0 px-5 py-5 flex-1 flex flex-col overflow-y-auto lg:order-last"
              ref="sectionRef"
            >
              <div
                class="flex flex-col gap-2 text-gray-500h-full items-left pt-1"
              >
                <div class="text-lg flex flex-col gap-1">
                  <div
                    class="flex flex-row flex-wrap gap-1 justify-between items-center"
                  >
                    <div>Uploading files to the folder:</div>
                    <Popper
                      placement="bottom"
                      openDelay="50"
                      arrow
                      class="light-popper"
                    >
                      <InformationCircleIcon class="h-6 w-6 text-gray-500" />
                      <template #content>
                        <div>
                          <div
                            class="flex justify-center items-center h-6 bg-gray-500 text-white font-bold py-0 px-2 rounded mb-4"
                          >
                            File Uploader Info
                          </div>
                          <div>
                            File Uploader will upload files to the
                            <strong>selected folder</strong>
                            from the
                            <em>Files Tree</em>.
                          </div>
                          <div class="mt-2">
                            In case where no folder is selected, a root folder
                            of the Study will be used with the Sign:
                            <strong
                              class="flex flex-row justify-center items-center gap-2 bg-gray-100 mt-2"
                            >
                              <CircleStackIcon class="h-5 w-5 text-gray-500" />/
                            </strong>
                          </div>
                        </div>
                      </template>
                    </Popper>
                  </div>
                  <div class="flex flex-row gap-3 items-center">
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
            </section>
          </pane>
        </splitpanes>
      </div>

      <div class="divide-y divide-gray-200 sm:col-span-9 h-full" v-if="false">
        <div v-if="files?.length" class="h-full">
          <nav
            v-if="selectTreeFolder"
            class="flex p-3 w-full overflow-hidden cursor-pointer select-none"
            aria-label="Breadcrumb"
          >
            <ol role="list" class="flex items-center space-x-2">
              <li>
                <div>
                  <a class="text-gray-400 hover:text-gray-900">
                    <HomeIcon class="flex-shrink-0 h-5 w-5" />
                    <span class="sr-only">Home</span>
                  </a>
                </div>
              </li>
              <li v-for="page in selectTreeFolder.split('/')" :key="page.name">
                <div v-if="page != ''" class="flex items-center">
                  <ChevronRightIcon
                    class="flex-shrink-0 h-5 w-5 text-gray-400"
                  />
                  <a
                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                    :aria-current="page ? 'page' : undefined"
                    >{{ page }}</a
                  >
                </div>
              </li>
            </ol>
          </nav>
          <div
            class="h-full border-t border-gray-200 xl:flex px-0 sm:px-1 py-3 pb-12 height-limited"
          >
            <div class="py-1 px-0 sm:px-4" v-if="treeFilled">
              <div class="aside-menu relative flex flex-col overflow-y-auto">
                <div class="mr-5 min-w-fit">
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
            <div class="border-r border-gray-200 min-h-fit my-3"></div>
            <section
              class="min-w-0 px-5 py-5 flex-1 flex flex-col overflow-y-auto lg:order-last"
              ref="sectionRef"
            >
              <div
                class="flex flex-col gap-2 text-gray-500h-full items-left pt-1"
              >
                <div class="text-lg flex flex-col gap-1">
                  <div
                    class="flex flex-row flex-wrap gap-1 justify-between items-center"
                  >
                    <div>Uploading files to the folder:</div>
                    <Popper
                      placement="bottom"
                      openDelay="50"
                      arrow
                      class="light-popper"
                    >
                      <InformationCircleIcon class="h-6 w-6 text-gray-500" />
                      <template #content>
                        <div>
                          <div
                            class="flex justify-center items-center h-6 bg-gray-500 text-white font-bold py-0 px-2 rounded mb-4"
                          >
                            File Uploader Info
                          </div>
                          <div>
                            File Uploader will upload files to the
                            <strong>selected folder</strong>
                            from the
                            <em>Files Tree</em>.
                          </div>
                          <div class="mt-2">
                            In case where no folder is selected, a root folder
                            of the Study will be used with the Sign:
                            <strong
                              class="flex flex-row justify-center items-center gap-2 bg-gray-100 mt-2"
                            >
                              <CircleStackIcon class="h-5 w-5 text-gray-500" />/
                            </strong>
                          </div>
                        </div>
                      </template>
                    </Popper>
                  </div>
                  <div class="flex flex-row gap-3 items-center">
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
            </section>
          </div>
        </div>
      </div>
    </template>
  </study-content>
  <!-- </w-app> -->
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
import { useForm, usePage } from '@inertiajs/inertia-vue3'

import { useFiles } from '@/VueComposable/useFiles'

import { ref, computed, onMounted, reactive, watch } from 'vue'
import { useStore } from 'vuex'

const props = defineProps(['study', 'project', 'files'])
const { showChildsAPI, extractzip } = useFiles()

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

const TreeItemClick = (file, parent) => {
  const itemData = file.type === 'directory' ? file : parent
  displaySelected(itemData)
  storeSelected(itemData)
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
</script>

<style lang="scss">
.files-uploader-area {
  .splitpanes__pane {
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.2) inset;
    // justify-content: center;
    // align-items: center;
    // display: flex;
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
