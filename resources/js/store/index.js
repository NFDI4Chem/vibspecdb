import Vuex from 'vuex'

import draggableStructure from "@/store/modules/draggableStructure";
import filesUppy from "@/store/modules/filesUppy";

export const store = new Vuex.Store({
  modules: {
    draggableStructure,
    filesUppy
  },
})

if (window.Cypress) {
  // Only available during E2E tests
  window.__store__ = store
}
