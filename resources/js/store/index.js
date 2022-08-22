import Vuex from 'vuex'

import draggableStructure from "@/store/modules/draggableStructure";
import Uppy from "@/store/modules/Uppy";
import FilesTree from "@/store/modules/FilesTree";

export const store = new Vuex.Store({
  modules: {
    draggableStructure,
    Uppy,
    FilesTree
  },
})

if (window.Cypress) {
  // Only available during E2E tests
  window.__store__ = store
}
