import { ref, computed } from "vue";

export const sidebarOpen = ref(false);
export const selectedFiles = ref([]);
export const selectedModel = ref(-1);
export const currentStudyStep = ref(0);
export const jobs = ref([]);

const link_url = (study) => {
    return {
        files: `/studies/${study?.id}/files`,
        models: `/studies/${study?.id}/models`,
        jobs: `/studies/${study?.id}/jobs`,
    };
};

export const StudySubmitSteps = (study) => {
    const links = link_url(study);
    return [
        {
            name: "Step 1: select files to process",
            href: links?.files,
            status: currentStudyStep.value === 1 ? 'current' : '',
        },
        {
            name: "Step 2: select model to process",
            href: links?.models,
            status: currentStudyStep.value === 2 ? 'current' : '',
        },
        {
            name: "Step 3: view job details & submit",
            href: links?.jobs,
            status: currentStudyStep.value === 3 ? 'current' : '',
        },
    ];
};

export const onShowDetails = (rowId) => {
    selectedFiles.value = selectedFiles.value.map((item) => {
        return rowId === item.id
            ? {
                  ...item,
                  detailsOpen: !item?.detailsOpen,
              }
            : item;
    });
}
export const onShowJobDetails = (rowId) => {
    jobs.value = jobs.value.map((item) => {
        return rowId === item.id
            ? {
                  ...item,
                  detailsOpen: !item?.detailsOpen,
                  details: {} // TODO, bug, needs to be added to not break the table on details; 
              }
            : item;
    });
};
