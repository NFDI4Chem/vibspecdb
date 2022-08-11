import * as types from '@/store/mutation-types'


const getters = {
  uppy: (state) => state.uppy,
}

const mutations = {
  updateUppyState: (state, payload) => {
    state.uppy = payload
  }
}

const actions = {
  updateUppyState: ({ commit }, payload) => {
    commit('updateUppyState', payload)
  }
}

const state = {
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
