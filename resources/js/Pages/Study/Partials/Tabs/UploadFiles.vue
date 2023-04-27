<template>
  <div class="pb3 files-uploader-area">
    <div class="h-[400px]" v-if="layout_switcher(files).top_size()">
      <div
        v-if="showOverlay || !spectraData"
        class="overlay-progress flex flex-col p-5 h-full justify-center align-middle gap-4"
      >
        <div class="flex justify-center">No data at the moment</div>
        <div class="flex justify-center">
          <w-progress class="ma1" circle color="light-blue-dark3" />
        </div>
      </div>
      <PlotlyPlotter v-else :input="spectraData" :showSqrtSd="false" />
      <!-- <SpectralPlotter
          v-if="false"
          :key="plotKey"
          :data="spectraData"
          ref="spectral_plot"
        /> -->
    </div>
    <!-- {{ activeItem }} -->
    <splitpanes
      @resized="e => onPaneResize(e)"
      :class="{ 'top-visible': layout_switcher(files).top_size() }"
      class="bg-slate-50"
    >
      <pane
        :size="layout_switcher(files).left_size()"
        class="flex flex-col items-start justify-start py-3 tree-plane"
      >
        <div class="w-full px-4">
          <div class="" v-if="treeFilled">
            <div class="relative flex flex-col w-full">
              <div class="min-w-fit">
                <div
                  class="flex flex-row justify-between items-center pb3 gap-2"
                >
                  <div class="flex flex-row justify-start align-middle w-full">
                    <div class="font-bold text-md mr-auto">
                      <div v-if="treeOptions?.showTitle">
                        {{ treeOptions?.title }}
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
                    v-if="treeOptions?.showInfo"
                  />
                </div>
                <!-- {{ clickedItem }} -->
                <UniFilesTree
                  ref="uniFilesTree"
                  :tree="files"
                  :options="treeOptions"
                  :onRemoveItem="onRemoveItem"
                  :onAddChildren="onAddChildren"
                  :onChangeNodeType="onChangeNodeType"
                  :activeItem="activeItem"
                  :clickedItem="clickedItem"
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
      <pane :size="layout_switcher(files).right_size()">
        <div class="w-full h-[500px] p-4" v-if="show_dataset_options">
          <div class="w-full h-full flex flex-col">
            <div class="text-md font-bold pb-2 h-8">Dataset Options:</div>
            <div class="bg-gray-100 overflow-auto grow">
              <DatasetOptions
                :data="dataset_data"
                @deleteMetafile="onChangeNodeType"
                @onParseMetadata="datasetSubmit"
              />
            </div>
          </div>
        </div>

        <div class="w-full h-[500px] p-4" v-if="show_selected_file">
          <div class="w-full h-full flex flex-col">
            <div class="text-md font-bold pb-2 h-8">
              File
              <span class="text-blue-900">"{{ clickedItem?.name }}"</span>
              metadata:
            </div>
            <div class="bg-gray-100 overflow-auto grow">
              <FilesMeta :data="clickedItem" />
            </div>
          </div>
        </div>

        <div
          class="flex flex-col gap-2 h-full items-left p-4 py4"
          :class="{
            'metainfo-visible': show_dataset_options || show_selected_file,
          }"
        >
          <div class="text-lg flex flex-col gap-1">
            <div
              class="flex flex-row flex-wrap gap-1 justify-between items-center"
            >
              <div class="text-bold text-md">
                Uploading files to the {{ activeItem?.type }}:
              </div>
              <UploaderInfoPopper :selectTreeFolder="selectTreeFolder" />
            </div>
            <div class="flex flex-row gap-2 items-center align-middle">
              <w-icon class="h-3 w-3 text-gray-500" sm
                >mdi
                {{
                  activeItem?.type === 'directory'
                    ? 'mdi-folder'
                    : 'mdi-database'
                }}</w-icon
              >
              <strong
                class="flex items-center text-sm text-gray-600 force-wrap pt-0.5"
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
import { ref, computed } from 'vue'

import ToolTipWrapper from '@/Components/UniFilesTree/ToolTipWrapper.vue'
import UniFilesTree from '@/Components/UniFilesTree/UniFilesTree.vue'
import Uploader from '@/Components/UploadForm/Uploader.vue'
import UploaderInfoPopper from '@/Components/Popper/UploaderInfoPopper.vue'
import TreeInfoPopper from '@/Components/Popper/TreeInfoPopper.vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'

// import SpectralPlotter from '@/Components/uPlot/SpectralPlotter.vue'
import PlotlyPlotter from '@/Components/plotly/PlotlyPlotter.vue'
import TreeOptionSettings from '@/Pages/Study/Partials/Elements/TreeOptionSettings.vue'
import DatasetOptions from '@/Pages/Study/Partials/Dataset/DatasetOptions.vue'
import FilesMeta from '@/Pages/Study/Partials/Metadata/FilesMeta.vue'

import {
  onRemoveItem,
  onAddChildren,
  onTreeChange,
  onDragend,
  onDrop,
  treeOptions,
  onChangeNodeType,
  onTreeCheck,
  TreeItemClick,
  uniFilesTree,
  datasetSubmit,
} from '@/VueComposable/useFilesTree'
import {
  spectraData,
  showOverlay,
  selectTreeFolder,
  activeItem,
  clickedItem,
} from '@/VueComposable/usePlotter'

import { layout_switcher } from '@/VueComposable/useStudyLayer'

const props = defineProps([/*'study', 'project',*/ 'files'])

const onUploaded = (file, data) => {
  console.log('files uploaded (UploadFiles.vue)', file, data)
}

const dataset_data = computed(() => {
  const is_dataset = activeItem.value?.type === 'dataset'
  if (is_dataset) {
    return {
      dataset: { ...activeItem.value, children: [] },
      metafiles: activeItem.value.children?.filter(f => {
        return f.type === 'metafile'
      }),
    }
  } else {
    return {
      metafiles: [],
      dataset: {},
    }
  }
})

const show_selected_file = computed(() => {
  return clickedItem.value?.type === 'file' && clickedItem.value?.metadata
})

const show_dataset_options = computed(() => {
  return (
    layout_switcher(props?.files).metainfo_visible() &&
    activeItem.value?.type === 'dataset'
  )
})

const treeFilled = computed(() => {
  return props?.files?.length > 0 && props?.files[0].children?.length > 0
})

const foldAll = () => {
  uniFilesTree.value.foldAll()
}
const unfoldAll = () => {
  uniFilesTree.value.unfoldAll()
}

const onPaneResize = () => {}
</script>

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
