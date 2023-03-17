<template>
  <div>
    <study-index :project="project" :study="study" current="Submit Job">
      <template #study-section>
        <div class="flex flex-1 flex-col justify-between">
          <div class="divide-y divide-gray-200 sm:col-span-9 h-full">
            <div class="flex justify-start p-2 px-6 flex-row">
              <div class="font-bold">Check Job overview and submit</div>
            </div>
            <div class="py-3 px-6">
              <JobPreview
                :files="selectedFiles"
                @onShowDetails="onShowDetails"
                :model="selectedModel"
              />
              <div class="grow flex flex-1 flex-row justify-between my-3">
                <JetButton
                  type="button"
                  @click="CheckEvent"
                  :loading="false"
                  class="bg-teal-600 hover:bg-teal-700 text-white font-bold rounded disabled:opacity-25"
                >
                  {{ 'Check Event Broadcasting' }}
                </JetButton>
                <JetButton
                  @click="SubmitJob()"
                  type="button"
                  :loading="formSubmit.processing"
                  :disabled="!selectedFiles.length || !selectedModel"
                  class="bg-teal-600 hover:bg-teal-700 text-white font-bold rounded disabled:opacity-25"
                >
                  Submit Job
                </JetButton>
              </div>
            </div>
          </div>
          <Footer :steps="steps" />
        </div>
      </template>
    </study-index>
  </div>
</template>

<script setup>
import JetButton from '@/Jetstream/Button.vue'
import StudyIndex from '@/Pages/Study/Index.vue'
import JobPreview from '@/Pages/Study/SubmitJob/JobPreview.vue'
import Footer from '@/Pages/Study/Helpers/Footer.vue'

import { useForm, usePage } from '@inertiajs/inertia-vue3'

import {
  selectedFiles,
  onShowDetails,
  selectedModel,
} from '@/VueComposable/store'
import { currentStudyStep, StudySubmitSteps } from '@/VueComposable/store'

import { computed } from 'vue'
const props = defineProps(['study', 'project', 'models'])

currentStudyStep.value = 3
const steps = computed(() => StudySubmitSteps(props?.study))

const CheckConnection = e => {
  axios
    .get('/api/v1/jobs/check/argo')
    .then(function (res) {
      console.log(res.data)
    })
    .catch(function (err) {
      console.log(err)
    })
}

const formCheck = useForm()
const CheckEvent = () => {
  formCheck
    .transform(data => {
      return data
    })
    .get(route('jobs.check', ['argo']), {
      preserveScroll: true,
    })
}

const formSubmit = useForm({
  type: 'argo',
  input_data: selectedFiles.value.map(f => f.path),
  // model_id: selectedModel.value,
  out_data: {
    prefix: 'tr_',
  },
  team_id: usePage()?.props?.value?.user?.current_team?.id,
  study_id: usePage()?.props?.value?.study?.id,
  project_id: usePage()?.props?.value?.project?.id,
  input_files: selectedFiles.value.map(f => f.id),
})

const SubmitJob = () => {
  formSubmit
    .transform(data => {
      return data
    })
    .post(route('jobs.submit'), {
      preserveScroll: true,
      onSuccess: job => {
        // console.log("job onSuccess", job);
      },
      onError: p => {
        // console.log("job onError", p);
      },
    })
}
</script>

<style lang="scss" scoped></style>
