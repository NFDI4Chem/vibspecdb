import Vuex from 'vuex'

import draggableStructure from "@/store/modules/draggableStructure";
import Uppy from "@/store/modules/Uppy";
import FilesTree from "@/store/modules/FilesTree";
import UserAlerts from "@/store/modules/UserAlerts";

export const store = new Vuex.Store({
  modules: {
    draggableStructure,
    Uppy,
    FilesTree,
    UserAlerts
  },
})

if (window.Cypress) {
  // Only available during E2E tests
  window.__store__ = store
}
