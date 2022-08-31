<template>
    <div
        class="mt-2 px-5 h-10 w-full border-t border-gray-200 flex flex-row justify-between items-center"
    >
        <div>Submit Job Process:</div>
        <div class="flex flex-row">
            <div><strong>Step {{active}}</strong></div>
            <div class="mx-5 stepper">
                <Points :n="n" :active="active" />
            </div>
        </div>
    </div>
</template>

<script setup>

import Points from "@/Pages/Study/Helpers/Points.vue";
const props = defineProps(["n", "active"]);

</script>

<style lang="scss" scoped>

.stepper {
    width: 40px;
    overflow: hidden;
    display: flex;
    justify-items: center;
    align-items: center;
}

</style>
