import * as types from "@/store/mutation-types";

const getters = {
    files: (state) => state.files,
};

const mutations = {
    updateFilesData: (state, payload) => {
      state.files = {
        ...state.files,
        ...payload,
      };
    }
};

const actions = {
  updateFilesData: ({ commit }, payload) => {
        commit("updateFilesData", payload);
    },
};

const state = {
    files: {
        viewMode: false,
        show: false,
        uploading: false,
        progress: 0,
        uppy: {
            plugins: {},
            files: {},
            currentUploads: {},
            capabilities: {
                resumableUploads: false,
            },
            totalProgress: 0,
            meta: {},
            info: {
                isHidden: true,
                type: "info",
                message: "",
            },
        },
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
