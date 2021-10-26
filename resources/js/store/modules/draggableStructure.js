import * as types from '@/store/mutation-types'

const initFolders = [
  {
    id: 1,
    label: 'New Folder',
    children: [
      {
        id: 2,
        label: 'New Subfolder1',
        children: [],
      },
      {
        id: 3,
        label: 'New Subfolder2',
        children: [],
      },
    ],
  },
]

const getters = {
  folders: (state) => state.folders,
  showTreeSelect: (state) => state.showTreeSelect,
}

const mutations = {
  updateFolders: (state, payload) => {
    state.folders = payload
  },
  showTreeSelect: (state, payload) => {
    state.showTreeSelect = payload
  },
}

const actions = {
  updateFolders: ({ commit }, payload) => {
    commit('updateFolders', payload)
  },
  showTreeSelect: ({ commit }, payload) => {
    commit('updateFolders', JSON.parse(JSON.stringify(initFolders)))
    commit('showTreeSelect', payload)
  },
}

const state = {
  showTreeSelect: false,
  folders: [
    {
      id: 1,
      label: 'New Folder',
      children: [
        {
          id: 2,
          label: 'New Subfolder1',
          children: [],
        },
        {
          id: 3,
          label: 'New Subfolder2',
          children: [],
        },
      ],
    },
  ],
}

export default {
  state,
  getters,
  mutations,
  actions,
}
