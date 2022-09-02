<template>
    <div
        class="mt-2 px-5 h-10 w-full border-t border-gray-200 flex flex-row justify-end md:justify-between items-center"
    >
        <div class="hidden md:block">Submit Job Process</div>
        <div class="flex flex-row justify-end">
            <div class="whitespace-nowrap text-clip text-ellipsis desc-footer">
                <strong>{{ current?.name }}</strong>
            </div>
            <div class="ml-5 stepper">
                <Points :steps="steps" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import Points from "@/Pages/Study/Helpers/Points.vue";
const props = defineProps(["steps"]);

const current = computed(() => {
    return props?.steps?.find((s) => s.status === "current");
});
</script>

<style lang="scss" scoped>
.stepper {
    display: flex;
    justify-items: center;
    align-items: center;
}
.desc-footer {
    max-width: 30%;
    overflow: hidden;
    text-overflow: ellipsis ellipsis;
}
</style>
