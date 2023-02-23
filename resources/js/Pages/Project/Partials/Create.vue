<template>

<w-dialog
  v-model="dialog.show"
  :fullscreen="dialog.fullscreen"
  :width="dialog.width"
  :persistent="dialog.persistent"
  :persistent-no-animation="dialog.persistentNoAnimation"
  title-class="primary-light1--bg white"
  dialog-class="create_item_dialog"
  content-class="mx3"
>
  <template #title>
    <w-icon class="mr2">mdi mdi-newspaper</w-icon>
    <div class="title3">Create New PROJECT</div>
    <div class="spacer" />
    <w-icon class="mr2 cursor-pointer" @click="dialog.show = false">
      mdi mdi-close
    </w-icon>
  </template>

    <w-form v-model="valid">
      <w-input
        required
        type="text"
        v-model="createProjectForm.name"
        label="Name"
        :validators="[validators.required]"
        class="my4 mb8 title4"
      />
      <w-textarea
        v-if="!dialog.md_view"
        type="text"
        v-model="createProjectForm.description"
        label="Description"
        class="mt4 title4"
        :outline="false"
      />
      <div
        v-if="dialog.md_view"
        class="h-[83px]"
        v-html="md(createProjectForm.description)"
      ></div>
      
      <div class="inline-flex items-center justify-between w-full">
        <div class="inline-flex align-middle items-center">
          <div class="body">Preview</div>
          <w-switch
            class="ma2"
            v-model="dialog.md_view"
            :thin="true"
            label=""
          />
        </div>
        <div class="spacer" />
        <div class="caption"> Styling with Markdown is supported</div>
      </div>

    </w-form>

  <template #actions>
      <div class="spacer" />
      <w-button
        @click="dialog.show = false"
        class="mx3"
        bg-color="secondary"
        lg
      >
        Close
      </w-button>
      <w-button
        @click="createProject"
        class="rounded-0"
        lg
        :loading="createProjectForm.processing"
        :disabled="!valid || !createProjectForm.name"
      >
          Save
      </w-button>
  </template>
</w-dialog>

</template>

<script>
import useWave from "@/VueComposable/mixins/useWave";

export default {
  mixins: [useWave],

  data() {
    return {
      dialog: {
        show: false,
        md_view: false,
        fullscreen: false,
        persistent: true,
        persistentNoAnimation: false,
        width: 600,
      },
      valid: null,
      validators: {
        required: value => !!value || 'This field is required'
      },
      createProjectForm: this.$inertia.form({
        _method: "POST",
        name: "",
        description: "",
        error_message: null,
        team_id: null,
        owner_id: null,
        color: null,
        starred: null,
        is_public: false,
      }),
      createProjectDialog: false,
    };
  },

  methods: {
    createProject() {
      this.createProjectForm.owner_id = this.$page.props.user.id;
      this.createProjectForm.team_id = this.$page.props.user.current_team.id;
      this.createProjectForm.post(route("projects.create"), {
        preserveScroll: true,
        onSuccess: () => {
          this.info_notify('Project Created')
          this.createProjectForm.reset();
        },
        onError: (err) => {
          this.error_notify('Failed to Create Project')
          console.error(err)
        },
        onFinish: () => {
          this.dialog.show = false;
        },
      });
    },
    toggleCreateProjectDialog(){
        this.dialog.show = !this.dialog.show;
    }
  },
};
</script>
