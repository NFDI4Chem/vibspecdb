const getters = {
  Treefiles: state => state.files,
}

const mutations = {
  updateFilesTreeData: (state, payload) => {
    state.files = {
      ...state.files,
      ...payload,
    }
  },
}

const actions = {
  updateFilesTreeData: ({ commit }, payload) => {
    commit('updateFilesTreeData', payload)
  },
}

const state = {
  files: {
    activeItem: {
      id: 0,
      path: '/',
    },
  },
}

export default {
  state,
  getters,
  mutations,
  actions,
}
