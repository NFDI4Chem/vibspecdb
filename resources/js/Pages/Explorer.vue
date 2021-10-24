<template>
    <app-layout title="Explorer">
        <template #header>
            <div class="flex content-center">
                <div
                    class="
                        flex
                        items-center
                        text-sm text-gray-700
                        uppercase
                        font-bold
                        tracking-widest
                        pt-1
                    "
                >
                    {{ user.current_team.name }} File Explorer
                </div>
                <div class="flex mt-3 flex-row-reverse justify-end">
                    <img
                        :key="user.id"
                        v-for="user in team.users"
                        class="w-8 h-8 -mr-3 rounded-full border-3 border-dark"
                        :src="user.profile_photo_url"
                        :alt="user.name"
                    />
                </div>
            </div>
            <div>
                <a
                    :href="'/teams/' + user.current_team.id"
                    class="text-sm text-gray-800 font-bold"
                >
                    Team Settings
                </a>
            </div>
            <!-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2> -->
        </template>

        <!-- <welcome /> -->

        <div class="max-w-2lg mx-auto my-5 px-2">
            <div class="container bg-transparent w-auto h-auto w-full">
                <Draggable :list="myArray" />
                <!-- <rawDisplayer class="col-3" :value="myArray" title="List" /> -->
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import Draggable from "@/Components/FilesExplorer/Draggable";
import rawDisplayer from "@/Components/FilesExplorer/RawDisplayer";
import { v4 as uuidv4 } from "uuid";

export default {
    components: {
        AppLayout,
        Welcome,
        Draggable,
        rawDisplayer,
    },
    data() {
        return {
            myArray: [
                {
                    name: "New Folder 1",
                    id: uuidv4(),
                    children: [
                        {
                            name: "New Folder 2",
                            id: uuidv4(),
                            children: [],
                        },
                        {
                            name: "New Folder 3",
                            id: uuidv4(),
                            children: [],
                        },
                    ],
                },
            ],
        };
    },
    props: ["user", "team"],
};
</script>
