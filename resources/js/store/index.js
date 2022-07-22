import Vuex from 'vuex'

import draggableStructure from "@/store/modules/draggableStructure";

export const store = new Vuex.Store({
  modules: {
    draggableStructure,
  },
})

if (window.Cypress) {
  // Only available during E2E tests
  window.__store__ = store
}
