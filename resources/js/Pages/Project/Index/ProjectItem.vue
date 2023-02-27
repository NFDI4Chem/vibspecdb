<template>
    <div class="relative z-0">

        <div class="z-10 cursor-pointer bg-slate-400 text-white w-auto absolute -top-2 -left-2">
            <div class="inline-flex items-center text-sm h-full px2 gap-x-1">
                <w-icon v-if="item.is_public" class="mx-auto" sm>mdi mdi-web</w-icon>
                <w-icon v-else class="mx-auto" sm> mdi mdi-lock</w-icon>
                <div>{{ item.is_public ? 'Public' : 'Private' }}</div>
            </div>
        </div>

        <delete-btn 
            :pid="item.id"
            class="z-10 cursor-pointer absolute -right-8 top-5 rotate-45"
        >
            <div class="inline-flex items-center pr-1 text-gray-500 gap-1">
                <w-icon class="mx-auto" md>mdi mdi-close</w-icon>
                <div class="">Delete</div>
            </div>
        </delete-btn>

        <div
            class="w-[250px] h-[340px] mx-auto bg-white text-center shadow-lg rounded-0 overflow-hidden hover:opacity-75 hover:cursor-pointer"
            @click="onSelect(item)"
        >
            
            <div class="p-5 w-40 mx-auto min-h-20">
                <w-image
                    src="/imgs/project/project.svg"
                    :ratio="3/4"
                    transition="scale-fade"
                />
            </div>
            <div class="flex items-center px-6 py-0.5 text-white bg-gray-500 ">
                <BriefcaseIcon class="w-5 h-5 pb-0.5" />
                <h1 class=" text-white font-semibold text-md">
                    #0 Studies
                </h1>
            </div>
            <div class="h-[175px] py-4 px-5 text-gray-500 flex flex-col gap-1 justify-between "
            >
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 text-left">
                        {{ item.name }}
                    </h1>
                    <p
                        class="py-1 text-md text-gray-700 special-ellipsis "
                        v-if="item?.description?.length"
                        v-html="md(item.description)"
                    />
                </div>
                <div class="flex flex-col gap-y-1">
                    <div class="flex flex-end items-center mt-1">
                        <CalendarIcon class="h-5 w-5 pr-1" aria-hidden="true" />
                        <h1 class="px-2 text-sm pt-1">
                            {{ formatDate(item?.created_at) }}
                        </h1>
                    </div>
                    <div class="flex items-center mt-1">
                        <UserIcon class="h-5 w-5 pr-1" aria-hidden="true" />
                        <h1 class="px-2 text-sm pt-1">Owner / Shared</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- <delete-btn
            :pid="project.id"
        >
            <div>Delete Project</div>
        </delete-btn> -->
    </div>
</template>

<script setup>
import {
    BriefcaseIcon,
    GlobeAltIcon,
    CalendarIcon,
} from "@heroicons/vue/24/outline";
import { LockClosedIcon, UserIcon } from "@heroicons/vue/24/solid";

import DeleteBtn from "@/Pages/Project/Partials/DeleteBtn.vue";

const props = defineProps(["item"]);
const emit = defineEmits(["onSelect"]);

const onSelect = (model) => {
    emit("onSelect", model);
};
</script>

<style lang="scss" scoped>
.special-ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    text-align: left;
}
</style>
