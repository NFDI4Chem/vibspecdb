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
      <div class="title3">Create New Study</div>
      <div class="spacer" />
      <w-icon class="mr2 cursor-pointer" @click="dialog.show = false">
        mdi mdi-close
      </w-icon>
    </template>

    <w-form v-model="valid">
      <w-input
        required
        type="text"
        v-model="createStudyForm.name"
        label="Name"
        :validators="[validators.required]"
        class="my4 mb8 title4"
      />
      <w-textarea
        v-if="!dialog.md_view"
        type="text"
        v-model="createStudyForm.description"
        label="Description"
        class="mt4 title4"
        :outline="false"
      />
      <div
        v-if="dialog.md_view"
        class="h-[83px]"
        v-html="md(createStudyForm.description)"
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
        <div class="caption">Styling with Markdown is supported</div>
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
        @click="createStudy"
        class="rounded-0"
        lg
        :loading="createStudyForm.processing"
        :disabled="!valid || !createStudyForm.name"
      >
        Save
      </w-button>
    </template>
  </w-dialog>
</template>

<script setup>
import { ref } from 'vue'
// import { Inertia } from '@inertiajs/inertia'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const props = defineProps(['project'])

const dialog = ref({
  show: false,
  md_view: false,
  fullscreen: false,
  persistent: true,
  persistentNoAnimation: false,
  width: 600,
})

const valid = ref(null)
const createStudyDialog = ref(false)

const validators = ref({
  required: value => !!value || 'This field is required',
})

const createStudyForm = useForm({
  _method: 'POST',
  name: '',
  description: '',
  error_message: null,
  team_id: usePage().props.value.user.current_team.id,
  owner_id: usePage().props.value.user.id, // TODO !! bug, fix it later, owner id should not be var
  project_id: props?.project?.id,
  color: null,
  starred: null,
  is_public: false,
})

const createStudy = () => {
  createStudyForm.post(route('studies.create'), {
    preserveScroll: true,
    onSuccess: () => {
      setup_info_notify('The study has been created')
      createStudyForm.reset()
    },
    onError: err => {
      setup_error_notify('Failed to Create Study')
      console.error(err)
    },
    onFinish: () => {
      dialog.value.show = false
    },
  })
}
const toggleCreateStudyDialog = () => {
  dialog.value.show = !dialog.value.show
}

defineExpose({
  toggleCreateStudyDialog,
})
</script>
