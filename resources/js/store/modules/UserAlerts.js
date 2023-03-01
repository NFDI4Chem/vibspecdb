const getters = {
  alerts: state => state.alerts,
}

const mutations = {
  update_alerts: (state, payload) => {
    state.alerts = payload
  },
}

const actions = {
  update_alerts: ({ commit }, payload) => {
    commit('update_alerts', payload)
  },
}

const state = {
  alerts: [],
}

export default {
  state,
  getters,
  mutations,
  actions,
}
