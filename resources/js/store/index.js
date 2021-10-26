import Vuex from 'vuex'
import modules from '@/store/modules'

export const store = new Vuex.Store({
  modules: {
    ...modules,
  },
})

if (window.Cypress) {
  // Only available during E2E tests
  window.__store__ = store
}
