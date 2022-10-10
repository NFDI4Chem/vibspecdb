<template>
    <div class="flex flex-col p-4">
        <section class="border-t border-gray-200 my-2">
            <div>
                <div
                    class="my-2 pl-1 flex flex-row justify-between items-center"
                >
                    <div
                        class="flex flex-row items-center align-middle space-x-2"
                    >
                        <div
                            v-if="!job_files?.logs"
                            :class="`animate-spin rounded-full h-4 w-4 border-b-2 border-teal-500 z-12`"
                        ></div>
                        <div class="font-bold">Log/Error Files:</div>
                    </div>
                    <Button
                        :loading="!job_files?.logs"
                        class="p-1 px-2 rounded border hover:cursor-pointer"
                    >
                        <div>Download ALL</div>
                    </Button>
                </div>
                <div class="files flex flex-col gap-1 mt-4">
                    <div
                        class=""
                        v-for="log in job_files?.logs"
                        :key="`log_${log.name}`"
                    >
                        <div
                            class="w-full p-1 px-4 space-x-1 align-middle justify-between items-center bg-gray-100 flex flex-row rounded border border-gray-200"
                        >
                            <div class="capitalize mr-5">{{ log.name }}</div>
                            <div
                                class="flex flex-row items-center align-middle gap-2"
                            >
                                <EyeIcon
                                    class="w-5 h-5 text-gray-700 hover:cursor-pointer hover:text-sky-700"
                                    aria-hidden="true"
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
                        <div
                            class="flex flex-row items-center align-middle gap-2"
                        >
                            <EyeIcon
                                class="w-5 h-5 text-gray-700 hover:text-sky-700"
                                aria-hidden="true"
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
                <div
                    class="my-2 pl-1 flex flex-row justify-between items-center"
                >
                    <div
                        class="flex flex-row items-center align-middle space-x-2"
                    >
                        <div
                            v-if="!job_files?.output"
                            :class="`animate-spin rounded-full h-4 w-4 border-b-2 border-teal-500 z-12`"
                        ></div>
                        <div class="font-bold">Output Data:</div>
                    </div>
                    <div class="flex flex-row space-x-2">
                        <Button
                            :loading="!job_files?.output"
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
                                {{ file?.name }}
                            </div>
                            <div
                                class="flex flex-row items-center align-middle gap-2"
                            >
                                <EyeIcon
                                    class="w-5 h-5 text-gray-700 hover:text-sky-700"
                                    aria-hidden="true"
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
    </div>
</template>

<script setup>
import Button from "@/Jetstream/Button.vue";
import { reactive, ref } from "vue";
import { CloudArrowDownIcon, EyeIcon } from "@heroicons/vue/24/outline";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";
import { useFiles } from "@/VueComposable/useFiles";

// import buffer from "buffer"
// import btoa from "btoa"

const props = defineProps(["details"]);

const { getFilesListAPI } = useFiles();

const job_files = ref({});

const files = props?.details?.job?.files;
const logs = [
    {
        id: 1,
        title: "Errors",
    },
    {
        id: 2,
        title: "Process",
    },
];

const converter = (string) => {
    return Buffer.from(string).toString("base64");
};

const formDownload = useForm();
const DownloadClick = (id) => {
    formDownload
        .transform((id) => {
            return id;
        })
        .get(route("files.download", id), {
            preserveScroll: true,
        });
};

const formDownloadByPath = useForm();
const DownloadByPath = (key) => {
    console.log("data", key, props?.details?.job?.submit_uid);
    /*
    formDownloadByPath
        .transform((form) => {
            return {
                key,
                jobid: props?.details?.job?.submit_uid,
            };
        })
        .post(route("files.downloadbypath"), {
            preserveScroll: true,
        });
        */
};

const formList = useForm();
const GetListClick = (id = 0) => {
    formList
        .transform((id) => {
            return id;
        })
        .get(route("files.list"), {
            preserveScroll: true,
        });
};

(async () => {
    job_files.value = await getFilesListAPI(props?.details?.job?.submit_uid);
    console.log("job_files", job_files);
})();
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
