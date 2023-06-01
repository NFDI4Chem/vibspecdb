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
  alerts: [
    /*
    {
      status: 'done',
      argo_job_id: 1,
      created_at: 1674330769,
      study: {
        id: 1,
        name: 'New Study 1',
      },
    },
    {
      status: 'succeeded',
      argo_job_id: 2,
      created_at: 1674330769,
      study: {
        id: 2,
        name: 'New Study 2',
      },
    },
    {
      status: 'failed',
      argo_job_id: 3,
      created_at: 1674330769,
      study: {
        id: 3,
        name: 'New Study 3',
      },
    },
    {
      status: 'error',
      argo_job_id: 4,
      created_at: 1674330769,
      study: {
        id: 4,
        name: 'New Study 4',
      },
    },
    */
  ],
}

export default {
  state,
  getters,
  mutations,
  actions,
}
