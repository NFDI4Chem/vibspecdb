<template lang="">
    <div class="bg-gray-50 group hover:bg-gray-100">
        <div class="w-full h-15 border-gray-200 border-2 p-2">
            <div class="flex flex-row justify-between items-center">
                <div class="">
                    <SwitchVerticalIcon class="h-6 default-icon" />
                </div>
                <div class="w-full mx-6 overflow-hidden text-left">
                    <p
                        class="
                            text-gray-900
                            font-semibold
                            whitespace-nowrap
                            truncate
                            input
                        "
                    >
                        {{ item.name }}
                    </p>
                </div>
                <div class="flex flex-row justify-between w-16">
                    <PlusCircleIcon class="h-6 success-icon" @click="addAt" />
                    <XCircleIcon class="h-6 delete-icon" @click="removeAt" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    SwitchVerticalIcon,
    XCircleIcon,
    PlusCircleIcon,
} from "@heroicons/vue/outline";

export default {
    components: {
        SwitchVerticalIcon,
        XCircleIcon,
        PlusCircleIcon,
    },
    methods: {
        removeAt() {
            this.$emit("remove", this.item.id);
        },
        addAt() {
            this.$emit("add", this.item.id);
        },
    },
    props: ["item"],
};
</script>
<style lang=""></style>
