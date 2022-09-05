<template>
    <div class="border-t my-3 w-full">
        <div class="font-bold">Selected Model Details:</div>
        <div class="flex flex-1 flex-col sm:flex-row">
            <div class="w-full sm:w-1/2  flex flex-col gap-3">
                <div class="italic my-3 border-b w-full sm:w-3/4">Default settings:</div>
                <div>Input data path: <strong>{{model?.input}}</strong></div>
                <div>Output data path: <strong>{{model?.output}}</strong></div>
            </div>
            <div class="w-full sm:w-1/2  flex flex-col gap-3">
                <div class="italic my-3 border-b w-full sm:w-3/4" >Special settings:</div>
                <div>Start Script <strong>{{model?.startscript}}</strong></div>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps(["model"])

</script>

<style lang="scss" scoped></style>
