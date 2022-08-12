import * as types from '@/store/mutation-types'


const getters = {
  uppy: (state) => state.uppy,
  show: (state) => state.show,
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
  updateStartUpload: (state, payload) => {
    state.startUpload = {
      ...state.startUpload,
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
  updateStartUpload: ({ commit }, payload) => {
    commit('updateStartUpload', payload)
  },
}

const state = {
  show: {
    files: false
  },
  startUpload: {
    files: false
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
