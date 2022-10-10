<template>
    <div class="flex flex-col p-4">
        <section class="border-t border-gray-200">
          <div class="my-2 pl-1 flex flex-row justify-between items-center">
              <div class="">Log/Error Files:</div>
              <div class="p-1 px-2 rounded bg-gray-100 border border-gray-200 hover:bg-gray-200 hover:cursor-pointer">
                  <div>Download ALL</div>
              </div>
          </div>
        </section>
        <section class="flex flex-col border-t border-gray-200">
            <div class="my-2 pl-1 flex flex-row justify-between items-center">
                <div class="">Output Data:</div>
                <div class="p-1 px-2 rounded bg-gray-100 border border-gray-200 hover:bg-gray-200 hover:cursor-pointer">
                    <div>Download ALL</div>
                </div>
            </div>
            <div class="files flex flex-row space-x-2">
                <div
                    class="file"
                    v-for="file in files"
                    :key="`file_${file.id}`"
                >
                    <div
                        class="w-100 p-2 px-4 space-x-2 align-middle items-center bg-gray-100 flex flex-row rounded-xl border border-gray-200"
                    >
                        <div>{{ file.name }}</div>
                        <CloudArrowDownIcon
                            class="w-8 h-8 text-gray-700 hover:cursor-pointer hover:text-sky-700"
                            aria-hidden="true"
                        />
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { CloudArrowDownIcon } from "@heroicons/vue/24/outline";

const props = defineProps(["details"]);

const files = props?.details?.job?.files;
const paths = files.map((f) => f.path);
</script>

<style lang="scss" scoped></style>
