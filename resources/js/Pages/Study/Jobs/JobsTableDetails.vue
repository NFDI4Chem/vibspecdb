<template>
  <div class="flex flex-col p-4">
    <section class="border-t border-gray-200 my-2">
      <div>
        <div class="my-2 pl-1 flex flex-row justify-between items-center">
          <div class="flex flex-row items-center align-middle space-x-2">
            <div
              v-if="!job_files?.logs"
              :class="`animate-spin rounded-full h-4 w-4 border-b-2 border-teal-500 z-12`"
            ></div>
            <div class="font-bold">Log/Error Files:</div>
          </div>
          <Button
            disabled
            :loading="!job_files?.logs"
            class="p-1 px-2 rounded border hover:cursor-pointer"
          >
            <div>Download ALL</div>
          </Button>
        </div>
        <div class="files flex flex-col gap-1 mt-4">
          <div class="" v-for="log in job_files?.logs" :key="`log_${log.name}`">
            <div
              class="w-full p-1 px-4 space-x-1 align-middle justify-between items-center bg-gray-100 flex flex-row rounded border border-gray-200"
            >
              <div class="capitalize mr-5">{{ log.name }}</div>
              <div class="flex flex-row items-center align-middle gap-2">
                <EyeIcon
                  class="w-5 h-5 text-gray-700 hover:cursor-pointer hover:text-sky-700"
                  aria-hidden="true"
                  @click="showContent('text', converter(log?.key))"
                />
                <a
                  :href="
                    route('files.downloadbypath', [
                      details?.job?.submit_uid,
                      converter(log?.key),
                    ])
                  "
                  class=""
                >
                  <CloudArrowDownIcon
                    class="w-6 h-6 text-gray-700 hover:text-sky-700"
                    aria-hidden="true"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="flex flex-col border-t border-gray-200 my-2">
      <div class="my-2 pl-1 flex flex-row justify-between items-center">
        <div class="font-bold">Input Data:</div>
        <div class="flex flex-row space-x-2">
          <Button
            disabled
            class="p-1 px-2 rounded bg-gray-100 border hover:cursor-pointer"
          >
            <div>Download ALL</div>
          </Button>
        </div>
      </div>
      <div class="files flex flex-col gap-1 mt-2">
        <div
          class="file group w-full"
          v-for="file in files"
          :key="`file_input_${file?.id}`"
        >
          <div
            class="w-full p-1 px-4 space-x-1 align-middle justify-between items-center bg-gray-100 over:bg-gray-200 flex flex-row rounded border border-gray-200"
          >
            <div class="mr-5 ellipsize-left">{{ file?.name }}</div>
            <div class="flex flex-row items-center align-middle gap-2">
              <EyeIcon
                class="w-5 h-5 text-gray-700 hover:text-sky-700"
                aria-hidden="true"
                @click="showContent('image', converter(file?.path))"
              />
              <a
                :href="route('files.downloadbyid', file?.id)"
                download
                class=""
              >
                <CloudArrowDownIcon
                  class="w-6 h-6 text-gray-700 hover:text-sky-700"
                  aria-hidden="true"
                />
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="flex flex-col border-t border-gray-200 my-2">
      <div>
        <div class="my-2 pl-1 flex flex-row justify-between items-center">
          <div class="flex flex-row items-center align-middle space-x-2">
            <div
              v-if="!job_files?.output"
              :class="`animate-spin rounded-full h-4 w-4 border-b-2 border-teal-500 z-12`"
            ></div>
            <div class="font-bold">Output Data:</div>
          </div>
          <div class="flex flex-row space-x-2">
            <Button
              :loading="!job_files?.output"
              disabled
              class="p-1 px-2 rounded bg-gray-100 border hover:cursor-pointer"
            >
              <div>Download ALL</div>
            </Button>
          </div>
        </div>
        <div class="files flex flex-col gap-1 mt-4">
          <div
            class="files flex w-full"
            v-for="file in job_files?.output"
            :key="`file_out_${file?.id}`"
          >
            <div
              class="w-full p-1 px-4 space-x-1 align-middle justify-between items-center bg-gray-100 over:bg-gray-200 flex flex-row rounded border border-gray-200"
            >
              <div class="mr-5 ellipsize-left">
                {{ file?.key }}
              </div>
              <div class="flex flex-row items-center align-middle gap-2">
                <EyeIcon
                  class="w-5 h-5 text-gray-700 hover:text-sky-700"
                  aria-hidden="true"
                  @click="showContent('image', converter(file?.key))"
                />
                <a
                  :href="
                    route('files.downloadbypath', [
                      details?.job?.submit_uid,
                      converter(file?.key),
                    ])
                  "
                  class=""
                >
                  <CloudArrowDownIcon
                    class="w-6 h-6 text-gray-700 hover:text-sky-700"
                    aria-hidden="true"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <jet-dialog-modal :show="dialog.show" @close="dialog.show = false">
      <template #title>
        <div class="flex flex-row justify-between">
          <div>Content Preview</div>
          <XMarkIcon
            class="w-8 h-8 text-gray-500"
            aria-hidden="true"
            @click="dialog.show = false"
          />
        </div>
      </template>

      <template #content>
        <div v-if="dialog.data">{{ dialog.data }}</div>
        <img class="object-fill w-full" v-else :src="dialog.url" alt="" />
      </template>
      <template #footer v-if="false">
        <div>Footer here</div>
      </template>
    </jet-dialog-modal>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { Link } from '@inertiajs/inertia-vue3'

import { useFiles } from '@/VueComposable/useFiles'

import Button from '@/Jetstream/Button.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'

import {
  CloudArrowDownIcon,
  EyeIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'

const dialog = ref({
  show: false,
  data: '',
  url: '',
})

const props = defineProps(['details'])

const { getFilesListAPI, getPresignedURL, parseFile } = useFiles()

const job_files = ref({})

const files = props?.details?.job?.files
console.log('files', files)
const logs = [
  {
    id: 1,
    title: 'Errors',
  },
  {
    id: 2,
    title: 'Process',
  },
]

const converter = string => {
  if (!string) {
    return 'undefined'
  }
  return Buffer.from(string).toString('base64')
}

const formDownload = useForm()
const DownloadClick = id => {
  formDownload
    .transform(id => {
      return id
    })
    .get(route('files.download', id), {
      preserveScroll: true,
    })
}

const showContent = async (type, path) => {
  const url = await getPresignedURL(props?.details?.job?.submit_uid, path)
  const text = type === 'text' && url ? await parseFile(url) : ''
  dialog.value = {
    show: true,
    data: text,
    url,
  }
}

;(async () => {
  job_files.value = await getFilesListAPI(props?.details?.job?.submit_uid)
  console.log('job_files', job_files)
})()
</script>

<style lang="scss" scoped>
.ellipsize-left {
  /* Standard CSS ellipsis */
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  width: calc(100% - 200px);

  /* Beginning of string */
  direction: rtl;
  text-align: left;
}
</style>
