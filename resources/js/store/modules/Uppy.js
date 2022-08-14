import * as types from '@/store/mutation-types'


const getters = {
  uppy: (state) => state.uppy,
  show: (state) => state.show,
  viewMode: (state) => state.viewMode,
  progress: (state) => state.progress,
  startUpload: (state) => state.startUpload
}

const mutations = {
  updateUppyState: (state, payload) => {
    state.uppy = payload
  },
  updateShow: (state, payload) => {
    state.show = {
      ...state.show,
      ...payload
    }
  },
  uppyUploading: (state, payload) => {
    state.startUpload = {
      ...state.startUpload,
      ...payload
    }
  },
  updateProgress: (state, payload) => {
    state.progress = {
      ...state.progress,
      ...payload
    }
  },
  updateViewMode: (state, payload) => {
    state.viewMode = {
      ...state.viewMode,
      ...payload
    }
  },
}

const actions = {
  updateUppyState: ({ commit }, payload) => {
    commit('updateUppyState', payload)
  },
  updateShow: ({ commit }, payload) => {
    commit('updateShow', payload)
  },
  uppyUploading: ({ commit }, payload) => {
    commit('uppyUploading', payload)
  },
  updateProgress: ({ commit }, payload) => {
    commit('updateProgress', payload)
  },
  updateViewMode: ({ commit }, payload) => {
    commit('updateViewMode', payload)
  },
}

const state = {
  viewMode: {
    files: false
  },
  show: {
    files: false
  },
  startUpload: {
    files: false
  },
  progress: {
    files: 0
  },
  uppy: {
    plugins: {},
    files: {},
    currentUploads: {},
    capabilities: {
      resumableUploads: false,
    },
    totalProgress: 0,
    meta: { },
    info: {
      isHidden: true,
      type: 'info',
      message: '',
    },
  },
}

export default {
  state,
  getters,
  mutations,
  actions,
}
