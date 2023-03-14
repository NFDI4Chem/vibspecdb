<template>
  <div class="py-3 flex">
    <w-card
      class="white--bg w-full border-none"
      content-class="pa0 max-width-none px6"
    >
      <div class="message-box w-full sm:w-3/4">
        <w-transition-fade>
          <w-alert
            v-if="form_helpers.valid === false"
            error
            no-border
            class="my0 text-light"
          >
            There is {{ form_helpers.errorsCount }} errors.
          </w-alert>
        </w-transition-fade>
      </div>

      <div class="relative">
        <UploadImage
          class="raltive sm:absolute right-0 mt5 sm:mt0 top-[-20px] z-2"
          :item="item"
          :type="type"
        />
        <w-form
          v-model="form_helpers.valid"
          v-model:errors-count="form_helpers.errorsCount"
          @submit.prevent="onSubmit"
          class="py12 flex flex-col gap-10"
        >
          <w-input
            type="text"
            class="title4 w-1/2"
            v-model="form.name"
            required
            label="Study Name *"
            :validators="[validators.required]"
          >
          </w-input>

          <div class="lex flex-col mt2">
            <div class="">
              <w-textarea
                v-if="!md_view"
                v-model="form.description"
                class="title4"
                label="About"
              >
              </w-textarea>
              <div
                v-else
                class="h-auto min-h-[83px] py4 border-b"
                v-html="md(form.description)"
              ></div>
            </div>

            <div class="inline-flex items-center justify-between w-full">
              <div class="inline-flex align-middle items-center">
                <div class="body">Preview</div>
                <w-switch class="ma2" v-model="md_view" :thin="true" label="" />
              </div>
              <div class="spacer" />
              <div class="caption">Styling with Markdown is supported</div>
            </div>
          </div>

          <div class="study-keys">
            <div class="title4 primary mb2">Keywords</div>
            <vue3-tags-input
              class="rounded-0 focus:outline-none primary"
              :tags="form.tags"
              placeholder="Type a keyword and press enter or space button."
              :limit="10"
              @on-tags-changed="onSelectTag"
            />
          </div>

          <w-flex wrap align-center justify-end class="mt4">
            <div class="spacer" />
            <w-button
              type="submit"
              lg
              :disabled="form_helpers.valid === false"
              :loading="loading"
              class="my1"
            >
              Save
            </w-button>
          </w-flex>
        </w-form>
      </div>
    </w-card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Vue3TagsInput from 'vue3-tags-input'
import { Inertia } from '@inertiajs/inertia'

import UploadImage from '@/Pages/Study/Partials/Elements/UploadImage.vue'

import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const props = defineProps(['item', 'type'])

const md_view = ref(false)
const loading = ref(false)

const form = ref({
  tags: props?.item.tags_translated,
  name: props?.item.name,
  description: props?.item.description,
})

const form_helpers = ref({
  valid: null,
  submitted: false,
  sent: false,
  errorsCount: 0,
})

const validators = {
  required: value => !!value || 'This field is required',
  consent: value => !!value || 'You must agree',
}

const onSelectTag = tags => {
  form.value.tags = tags
}

const onSubmit = () => {
  loading.value = true

  Inertia.put(route('studies.update', props.item?.id), form.value, {
    errorBag: 'studiesUpdate',
    preserveScroll: true,
    onSuccess: () => {
      loading.value = false
      setup_info_notify('The study info has been successfully saved!')
    },
    onError: err => {
      loading.value = false
      const message = Object.values(err).join('<br>')
      setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
    },
    onFinish: () => {
      loading.value = false
    },
  })
}

const resetForm = () => {
  form_helpers.value.submitted = form_helpers.value.sent = false
}
</script>

<style lang="scss">
.max-width-none {
  max-width: none;
}

.w-textarea__label--left {
  margin-top: 1px;
}

.study-keys {
  .v3ti {
    border-radius: 0;
    border-color: #e5e7eb;
    border-top: none;
    border-left: none;
    border-right: none;
    .v3ti-tag {
      border-radius: 0;
      padding-left: 0.5rem;
    }
  }

  .v3ti--focus {
    border-color: teal;
    box-shadow: none;
  }
}
</style>
