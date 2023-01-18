<template>
    <jet-action-section>
        <template #title>
            Delete Assay
        </template>

        <template #description>
            Permanently delete your Assay.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once your Assay is deleted, all of its resources and data will be permanently deleted. Before deleting your Assay, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">
                <jet-danger-button @click="confirmAssayDeletion">
                    Delete Assay
                </jet-danger-button>
            </div>

            <!-- Delete Assay Confirmation Modal -->
            <jet-dialog-modal :show="confirmingAssayDeletion" @close="closeModal">
                <template #title>
                    Delete Assay
                </template>

                <template #content>
                    Are you sure you want to delete your Assay? Once your Assay is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your Assay.

                    <div class="mt-4">
                        <jet-input
ref="password" v-model="form.password" type="password"
                                    class="mt-1 block w-3/4"
                                    placeholder="Password"
                                    @keyup.enter="deleteAssay" />

                        <jet-input-error :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click="closeModal">
                        Cancel
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="deleteAssay">
                        Delete Assay
                    </jet-danger-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection.vue'
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
    import JetDangerButton from '@/Jetstream/DangerButton.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default {
        components: {
            JetActionSection,
            JetDangerButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
        },

        props: ['assay'],

        data() {
            return {
                confirmingAssayDeletion: false,

                form: this.$inertia.form({
                    password: '',
                })
            }
        },

        methods: {
            confirmAssayDeletion() {
                this.confirmingAssayDeletion = true;

                setTimeout(() => this.$refs.password.focus(), 250)
            },

            deleteAssay() {
                this.form.delete(route('assay.destroy', this.assay.id), {
                    preserveScroll: true,
                    onSuccess: () => this.closeModal(),
                    onError: () => this.$refs.password.focus(),
                    onFinish: () => this.form.reset(),
                })
            },

            closeModal() {
                this.confirmingAssayDeletion = false

                this.form.reset()
            },
        },
    }
</script>
