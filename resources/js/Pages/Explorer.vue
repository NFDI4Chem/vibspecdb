<template>
  <app-layout title="Explorer">
    <template #header>
      <div class="flex content-center">
        <div
          class="flex items-center text-sm text-gray-700 uppercase font-bold tracking-widest pt-1"
        >
          {{ user.current_team.name }} File Explorer
        </div>
        <div class="flex mt-3 flex-row-reverse justify-end">
          <img
            v-for="user in team.users"
            :key="user.id"
            class="w-8 h-8 -mr-3 rounded-full border-3 border-dark"
            :src="user.profile_photo_url"
            :alt="user.name"
          />
        </div>
      </div>
      <div>
        <a
          :href="'/teams/' + user.current_team.id"
          class="text-sm text-gray-800 font-bold"
        >
          Team Settings
        </a>
      </div>
    </template>

    <div v-if="false" class="active">
      <Table />
    </div>

    <div v-if="false" class="disable">
      <DummyButton />

      <PyEditor :file="file" :visible="true" @toggleFilesTree="true" />

      <div class="flex flex-row justify-center pt-20 mx-20">
        <div class="w-1/2">
          <TreeEditor
            :visible="true"
            :list="files"
            :dialog-status="false"
            @addFile="actions"
            @addFolder="actions"
            @changed="actions"
            @changeItem="actions"
            @remove="actions"
            @fileActivate="actions"
            @changeDialogStatus="actions"
          />
        </div>
      </div>

      <div id="uplotExam" class="my-15">
        <div class="container bg-transparent w-auto h-auto w-full">
          <ExampleComponent />
        </div>
      </div>

      <div class="max-w-2lg mx-auto my-5 px-2">
        <div class="container bg-transparent w-auto h-auto w-full">
          <Draggable :list="elements" />
        </div>
      </div>
      <div v-if="true" class="max-w-2lg mx-auto my-5 px-2">
        <div class="container bg-transparent w-auto h-auto w-full">
          <BasicTreeSelect v-model:elements="elements" />
        </div>
      </div>
    </div>

    <div v-if="true" class="active">
      <codemirror
        v-model="code"
        placeholder="Code goes here..."
        :style="{ height: '400px' }"
        :autofocus="true"
        :indent-with-tab="true"
        :tab-size="2"
        :extensions="extensions"
        @ready="log('ready', $event)"
        @change="log('change', $event)"
        @focus="log('focus', $event)"
        @blur="log('blur', $event)"
      />
    </div>
    <div v-if="true" class="active">
      <UniFilesTree :tree="tree"></UniFilesTree>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Welcome from '@/Jetstream/Welcome.vue'
import Draggable from '@/Components/FilesExplorer/Draggable.vue'
import BasicTreeSelect from '@/Components/TreeSelect/BasicTreeSelect.vue'

import ExampleComponent from '@/Components/uPlot/ExampleComponent.vue'

// import DummyButton from "@/packages/serviceappvuecomponents/src/components/Examples/DummyButton.vue";
// import TreeEditor from "@/packages/serviceappvuecomponents/src/components/FilesTree/TreeEditor.vue";
// import PyEditor from "@/packages/serviceappvuecomponents/src/components/PyEditor/PyEditor.vue";

import {
  TreeEditor,
  PyEditor,
  DummyButton,
} from '@/packages/serviceappvuecomponents/src'
import Table from '@/Shared/Table/Table.vue'

import { ref } from 'vue'

import { Codemirror } from 'vue-codemirror'
import { javascript } from '@codemirror/lang-javascript'
import { python } from '@codemirror/lang-python'
import { oneDark } from '@codemirror/theme-one-dark'

import UniFilesTree from '@/Components/UniFilesTree/UniFilesTree.vue' // base style

export default {
  components: {
    AppLayout,
    Welcome,
    Draggable,
    BasicTreeSelect,
    ExampleComponent,
    DummyButton,
    TreeEditor,
    PyEditor,
    Table,
    Codemirror,
    UniFilesTree,
  },
  props: ['user', 'team'],
  setup() {
    const code = ref(`from PIL import Image, ImageOps\nimport os.path`)
    const extensions = [python(), oneDark]

    let file = ref({
      name: 'main.py',
      folder: false,
      id: 2,
      pid: 1,
      expanded: false,
      isActive: true,
      code: '# testing code of main.py \n \n \n \n \n \n \n \n',
      editing: false,
      deleting: false,
    })

    const tree = [
      { text: 'node 1' },
      { text: 'node 2', children: [{ text: 'node 2-1' }] },
      { text: 'node 3' },
      { text: 'node 4' },
      { text: 'node 5' },
      { text: 'node 6' },
    ]

    let files = ref([
      {
        id: 0,
        name: 'Home /',
        folder: true,
        expanded: true,
        editing: false,
        childern: [
          {
            id: 1,
            name: 'Project1',
            folder: true,
            expanded: true,
            editing: false,
            childern: [
              {
                id: 2,
                name: 'main.py',
                folder: false,
                expanded: false,
                editing: false,
                isActive: true,
                code: '# testing code of main.py \n \n \n \n \n \n \n \n',
                childern: [],
              },
            ],
          },
          {
            id: 3,
            name: 'Project2',
            folder: true,
            expanded: false,
            editing: false,
            childern: [
              {
                id: 4,
                name: 'file.py',
                folder: false,
                expanded: false,
                editing: false,
                isActive: false,
                code: '# testing code of file.py \n \n \n \n \n \n \n \n',
                childern: [],
              },
            ],
          },
        ],
      },
    ])
    const actions = () => {}

    return {
      file,
      files,
      actions,
      code,
      extensions,
      log: console.log,
      tree,
    }
  },
  computed: {
    showTreeSelect() {
      return this.$store.state.draggableStructure.showTreeSelect
    },
    elements: {
      get() {
        return this.$store.state.draggableStructure.folders
      },
      set(value) {
        this.$store.dispatch('updateFolders', value)
      },
    },
  },
}
</script>
