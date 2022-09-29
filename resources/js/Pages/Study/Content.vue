<template>
    <div>
        <study-layout :project="project" :study="study">
            <template #scontent>
                <div class="bg-white shadow-md rounded-lg flex flex-col flex-1">
                    <div class="md:hidden mb-2">
                        <label for="tabs" class="sr-only">Select a tab</label>
                        <select
                            @change="onMobileSelect"
                            class="block w-full focus:ring-sky-500 focus:border-sky-500 border-teal-500  rounded-0"
                        >
                            <option
                                v-for="tab in subNavigation"
                                :key="tab.name"
                                :selected="tab.name === current"
                                
                            >
                                {{ tab.name }}
                            </option>
                        </select>
                    </div>
                    <div class="hidden md:block">
                        <div class="border-b border-gray-200 flex flex-1 flex-row items-center justify-between px-4">
                            <nav
                                class="-mb-px flex space-x-8"
                                aria-label="Tabs"
                            >
                                <Link
                                    v-for="tab in subNavigation"
                                    :key="tab.name"
                                    :href="route(tab.route, [study.id])"
                                    :class="[
                                        tab.name == current
                                            ? 'border-sky-500 text-sky-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                        'group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm',
                                    ]"
                                    :aria-current="
                                        tab.name === current
                                            ? 'page'
                                            : undefined
                                    "
                                >
                                    <component
                                        :is="tab.icon"
                                        :class="[
                                            tab.name === current
                                                ? 'text-sky-600'
                                                : 'text-gray-400 group-hover:text-gray-500',
                                            '-ml-0.5 mr-2 h-5 w-5',
                                        ]"
                                        aria-hidden="true"
                                    />
                                    <span>
                                      {{ tab.name}}
                                      <sup class="uppercase">{{uppercase_id(tab?.route)}}</sup>
                                    </span
                                    >
                                </Link>
                            </nav>
                        </div>
                    </div>
                    <slot name="study-section"></slot>
                </div>
            </template>
        </study-layout>
    </div>
</template>

<script>
import StudyLayout from "@/Pages/Study/Layout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia';

import {
    InformationCircleIcon,
    FolderOpenIcon,
    BriefcaseIcon,
    QueueListIcon,
    BeakerIcon,
    ClipboardDocumentListIcon,
    Cog6ToothIcon,
} from "@heroicons/vue/24/outline";
const subNavigation = [
    // { name: "About", route: "study", icon: InformationCircleIcon },
    { name: "Upload Files", route: "study.file-upload", icon: FolderOpenIcon },
    { name: "Files", route: "study.files", icon: ClipboardDocumentListIcon },
    { name: "Select Model", route: "study.models", icon: BeakerIcon },
    { name: "Submit Job", route: "study.submit-job", icon: QueueListIcon },
    { name: "Jobs", route: "study.jobs", icon: BriefcaseIcon },
];

export default {
    components: {
        StudyLayout,
        Link,
    },
    // props: ["study", "project", "current"],
    setup() {
        return {
            subNavigation,
        };
    },
};
</script>

<script setup>
import { ref } from 'vue'
const selectedMobile = ref('')

const props = defineProps(["study", "project", "current"])

const uppercase_id = (route) => {
    switch (route) {
        case "study.files":
            return 1;
        case "study.models":
            return 2;
        case "study.submit-job":
            return 3;
        default:
            return "";
    }
};

const onMobileSelect = (e) => {
    const selected = e?.target?.value
    const tab = subNavigation.find((t) => t?.name === selected)
    Inertia.visit(route(tab.route, [props?.study?.id])) 
}

</script>
