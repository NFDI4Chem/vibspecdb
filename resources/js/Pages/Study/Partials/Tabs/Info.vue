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
          v-model="form.valid"
          v-model:errors-count="form.errorsCount"
          @validate="onValidate"
          @success="onSuccess"
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
                v-if="!dialog.md_view"
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
          </div>

          <div>
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
              md
              :disabled="form_helpers.valid === false"
              :loading="form_helpers.submitted && !form_helpers.sent"
              class="my1"
            >
              Save
            </w-button>
          </w-flex>
        </w-form>
      </div>
      <w-notification
        v-model="form_helpers.sent"
        success
        transition="bounce"
        absolute
        plain
        round
        bottom
      >
        The project info has been successfully saved!
      </w-notification>
    </w-card>
  </div>
  {{ form }}
</template>

<script setup>
import { ref } from 'vue'
import Vue3TagsInput from 'vue3-tags-input'

import UploadImage from '@/Pages/Study/Partials/Elements/UploadImage.vue'

const props = defineProps(['item', 'type'])

const dialog = ref({
  md_view: false,
})

const form = ref({
  tags: ['asdf', '1'],
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

const onSuccess = () => {
  console.log('onSuccess')
  // API call here;
  // setTimeout(() => (form.value.sent = true), 2000)
}
const onValidate = () => {
  console.log('onValidate')
  form_helpers.value.sent = false
  form_helpers.value.submitted = form_helpers.value.errorsCount === 0
}

const onSubmit = () => {
  console.log('try submit', form_helpers.value.valid)
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
</style>
