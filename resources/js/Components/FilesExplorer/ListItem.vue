<template lang="">
    <div class="bg-gray-50 group hover:bg-gray-100">
        <div class="w-full h-15 border-gray-200 border-2 p-2">
            <div class="flex flex-row justify-between items-center">
                <div class="">
                    <SwitchVerticalIcon class="h-6 default-icon" />
                </div>
                <div class="w-full mx-6 overflow-hidden text-left">
                    <div
                        class="
                            text-gray-900
                            font-semibold
                            whitespace-nowrap
                            truncate
                            input
                        "
                    >
                        <input
v-model="item.label" placeholder="Item Name" 
                            maxlength="120"
                            class="border-0 focus-visible:outline-none bg-transparent w-full"
                        >
                    </div>
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
    ArrowsUpDownIcon,
    XCircleIcon,
    PlusCircleIcon,
} from "@heroicons/vue/24/outline";

export default {
    components: {
        ArrowsUpDownIcon,
        XCircleIcon,
        PlusCircleIcon,
    },
    props: ["item"],
    methods: {
        removeAt() {
            this.$emit("remove", this.item.id);
        },
        addAt() {
            this.$emit("add", this.item.id);
        },
    },
};
</script>
<style lang=""></style>
