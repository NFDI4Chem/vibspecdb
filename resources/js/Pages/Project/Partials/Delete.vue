<template>
    <div class="flex flex-col sm:flex-row justify-between gap-4 project-delete">
        <div class="flex flex-col">
            <div class="text-bold"> Delete Project </div>
            <div>
                <div class="mt2 text-sm">
                    Permanently delete your project.
                </div>
            </div>
        </div>

        <div class="bg-white p-4 sm:w-full md:w-2/3">
            <div class="flex flex-col gap-2">
                <div class="text-sm text-gray-600">
                    Once your project is deleted, all of its resources and data will
                    be permanently deleted. Before deleting your project, please
                    download any data or information that you wish to retain.
                </div>

                <w-confirm 
                    id="project-delete"
                    question="Are you sure you want to delete your project? Once your
                        project is deleted, all of its resources and data will be
                        permanently deleted."
                    bg-color="error"
                    md
                    @confirm="deleteProject"
                    :disabled="form.processing"
                    transition="scale"
                >
                    Delete Project
                </w-confirm>
           
            </div>
        </div>
    </div>
</template>

<script>

import useWave from "@/VueComposable/mixins/useWave";

export default {
    mixins: [useWave],

    props: ["project"],

    data() {
        return {
            form: this.$inertia.form({}),
        };
    },

    methods: {
        deleteProject() {
            this.form.delete(route("project.destroy", this.project.id), {
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.info_notify('Project deleted successfully');
                },
                onError: () => {
                    this.info_error('Failed to Delete the Project');
                },
                onFinish: () => this.form.reset(),
            });
        },
    },
};
</script>
