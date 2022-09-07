<template>
    <Head :title="title" />
    <jet-banner />
    <div>
        <div class="flex flex-1">
            <MobileMenu :sidebarOpen="sidebarOpen" />
            <MiniMenu
                :sidebarOpen="sidebarOpen"
                @sidebarOpenChange="sidebarOpenChange"
            />
            <HeaderMenu
                :sidebarOpen="sidebarOpen"
                @sidebarOpenChange="sidebarOpenChange"
            >
              <template #header>
                <slot name="header"></slot>
              </template>
              <slot></slot>
            </HeaderMenu>
        </div>
    </div>
</template>

<script setup>

import MobileMenu from "@/Layouts/Elements/MobileMenu.vue";
import MiniMenu from "@/Layouts/Elements/MiniMenu.vue";
import HeaderMenu from "@/Layouts/Elements/HeaderMenu.vue";

import JetBanner from "@/Jetstream/Banner.vue"
import { Head, Link } from "@inertiajs/inertia-vue3";

import { sidebarOpen } from "@/VueComposable/store";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps(["title"]);

const sidebarOpenChange = (status) => {
    sidebarOpen.value = status;
};

const switchToTeam = (team) => {
    Inertia.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    Inertia.post(route("logout"));
};
</script>

<style lang="scss" scoped>
.min-width {
    min-width: 16rem;
}
.extra-small {
    font-size: 10px;
}
</style>
