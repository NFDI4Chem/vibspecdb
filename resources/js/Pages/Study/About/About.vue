<template>
  <div>
    <study-index :project="project" :study="study" current="About">
      <template #study-section>
        <div class="divide-y divide-gray-200 sm:col-span-9">
          <!-- Profile section -->
          <div class="py-3 px-4 sm:p-6 lg:pb-8">
            <div class="mt-0">
              <div>
                <div class="relative">
                  <div
                    class="absolute inset-0 flex items-center"
                    aria-hidden="true"
                  >
                    <div class="w-full border-t border-gray-300" />
                  </div>
                  <div class="relative flex items-center justify-between">
                    <span
                      class="pr-3 bg-white text-lg font-medium text-gray-900"
                    >
                      <b>Description</b>
                    </span>
                    <button
                      type="button"
                      class="inline-flex items-center shadow-sm px-4 py-1.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      <PlusSmallIcon
                        class="-ml-1.5 mr-1 h-5 w-5 text-gray-400"
                        aria-hidden="true"
                      />
                      <span>Edit</span>
                    </button>
                  </div>
                </div>
                <p
                  class="mt-3 text-gray-600"
                  v-html="md(study.description)"
                ></p>
              </div>
            </div>
          </div>
        </div>
      </template>
    </study-index>
  </div>
</template>

<script>
import { PlusSmallIcon } from '@heroicons/vue/24/solid'
import StudyIndex from '@/Pages/Study/Index.vue'
export default {
  components: {
    StudyIndex,
    PlusSmallIcon,
  },
  props: ['study', 'project'],
  setup() {},
  mounted() {},
}
</script>
