<template>
  <jet-dialog-modal :show="createProjectDialog" @close="createProjectDialog = false">
    <template #title> Create New Project: </template>

    <template #content>
      <w-form v-model="valid">
        <w-input
          label="First name"
          :validators="[validators.required]">
        </w-input>

        <w-input
          class="mt3"
          label="Last name"
          :validators="[validators.required]">
        </w-input>
      </w-form>

      <div class="relative z-0 mt1 cursor-pointer" v-if="false">
        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
          <div class="sm:col-span-6">
            <label for="name" class="block text-sm font-medium text-gray-700">
              Name
            </label>
            <div class="mt-1 flex rounded-md shadow-sm">
              <input
                id="name"
                v-model="createProjectForm.name"
                type="text"
                required
                name="name"
                autocomplete="off"
                class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full min-w-0 rounded sm:text-sm border-gray-300"
              />
            </div>
          </div>
          <div class="sm:col-span-6">
            <TabGroup v-slot="{ $selectedIndex }">
              <TabList class="flex items-center">
                <Tab v-slot="{ selected }" as="template">
                  <button
                    :class="[
                      selected
                        ? 'text-gray-900 bg-gray-100 hover:bg-gray-200'
                        : 'text-gray-500 hover:text-gray-900 bg-white hover:bg-gray-100',
                      'px-3 py-1.5 border border-transparent text-sm font-medium rounded-md',
                    ]"
                  >
                    Write
                  </button>
                </Tab>
                <Tab v-slot="{ selected }" as="template">
                  <button
                    :class="[
                      selected
                        ? 'text-gray-900 bg-gray-100 hover:bg-gray-200'
                        : 'text-gray-500 hover:text-gray-900 bg-white hover:bg-gray-100',
                      'ml-2 px-3 py-1.5 border border-transparent text-sm font-medium rounded-md',
                    ]"
                  >
                    Preview
                  </button>
                </Tab>
              </TabList>
              <TabPanels class="mt-2">
                <TabPanel class="p-0.5 -m-0.5 rounded-lg">
                  <label for="comment" class="sr-only">Comment</label>
                  <div>
                    <textarea
                      id="description"
                      v-model="createProjectForm.description"
                      name="description"
                      placeholder="Description (Optional)"
                      rows="3"
                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                </TabPanel>
                <TabPanel class="p-0.5 -m-0.5 rounded-lg">
                  <div class="border-b">
                    <div class="mx-px mt-px px-3 pt-2 pb-12">
                      <span
                        v-if="createProjectForm.description == ''"
                        class="text-gray-400 text-sm font-medium"
                      >
                        Nothing to preview
                      </span>
                      <span v-else>
                        <div
                          class="prose"
                          v-html="md(createProjectForm.description)"
                        ></div>
                      </span>
                    </div>
                  </div>
                </TabPanel>
              </TabPanels>
            </TabGroup>
            <label class="block text-sm font-medium text-gray-700"
              ><small
                ><svg
                  aria-hidden="true"
                  height="16"
                  viewBox="0 0 16 16"
                  version="1.1"
                  width="16"
                  data-view-component="true"
                  class="octicon octicon-markdown v-align-bottom inline"
                >
                  <path
                    fill-rule="evenodd"
                    d="M14.85 3H1.15C.52 3 0 3.52 0 4.15v7.69C0 12.48.52 13 1.15 13h13.69c.64 0 1.15-.52 1.15-1.15v-7.7C16 3.52 15.48 3 14.85 3zM9 11H7V8L5.5 9.92 4 8v3H2V5h2l1.5 2L7 5h2v6zm2.99.5L9.5 8H11V5h2v3h1.5l-2.51 3.5z"
                  ></path>
                </svg>
                Styling with Markdown is supported</small
              >
            </label>
            <jet-input-error
              :message="createProjectForm.errors.description"
              class="mt-2"
            />
          </div>
        </div>
      </div>
    </template>

    <template #footer>
      <jet-secondary-button @click="createProjectDialog = false">
        Cancel
      </jet-secondary-button>

      <jet-button
        class="ml-2"
        :class="{ 'opacity-25': createProjectForm.processing }"
        :disabled="createProjectForm.processing"
        @click="createProject"
      >
        Save
      </jet-button>
    </template>
  </jet-dialog-modal>
</template>

<script>
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetButton from "@/Jetstream/Button.vue";
import { CheckCircleIcon, ChevronRightIcon } from "@heroicons/vue/24/solid";
import { Link } from "@inertiajs/inertia-vue3";
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import { AtSymbolIcon, CodeBracketIcon, LinkIcon } from "@heroicons/vue/24/solid";
import JetInputError from "@/Jetstream/InputError.vue";
import { ref } from "vue";
import { Switch, SwitchDescription, SwitchGroup, SwitchLabel } from "@headlessui/vue";

import useWave from "@/VueComposable/mixins/useWave";

export default {
  mixins: [useWave],
  components: {
    Tab,
    TabGroup,
    TabList,
    TabPanel,
    TabPanels,
    Switch,
    SwitchDescription,
    SwitchGroup,
    SwitchLabel,
    AtSymbolIcon,
    CodeBracketIcon,
    LinkIcon,
    JetDialogModal,
    JetSecondaryButton,
    JetButton,
    Link,
    CheckCircleIcon,
    ChevronRightIcon,
    JetInputError,
  },

  props: [],

  data() {
    return {
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
        is_public: ref(false),
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
          this.info_error('Failed to Create Project')
          console.error(err)
        },
        onFinish: () => {
          this.createProjectDialog = false;
        },
      });
    },
    toggleCreateProjectDialog(){
        this.createProjectDialog = !this.createProjectDialog;
    }
  },
};
</script>
