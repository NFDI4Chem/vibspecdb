<template>
    <div class="step-wrapper" v-if="false">
        <div class="container">
            <ul class="progressbar">
                <li
                    v-for="id in Array.from(Array(n).keys())"
                    :key="`id_${id}`"
                    :class="{ ['active']: active - 1 === id }"
                ></li>
            </ul>
        </div>
    </div>

    <nav aria-label="Progress">
        <ol role="list" class="flex items-center">
            <li
                v-for="(step, stepIdx) in steps"
                :key="step.name"
                :class="[
                    stepIdx !== steps.length - 1 ? 'pr-8 sm:pr-4' : '',
                    'relative',
                ]"
            >
                <template v-if="step.status === 'complete'">
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div class="h-0.5 w-full bg-teal-600" />
                    </div>
                    <a
                        :href="step.href"
                        class="pointer-events-none relative flex h-4 w-4 items-center justify-center rounded-full bg-teal-600 hover:bg-teal-900"
                    >
                        <span class="sr-only">{{ step.name }}</span>
                    </a>
                </template>
                <template
                    v-else-if="step.status === 'current'"
                    condition="step.status === 'current'"
                >
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div class="h-0.5 w-full bg-gray-200" />
                    </div>
                    <a
                        :href="step.href"
                        class="pointer-events-none relative flex h-4 w-4 items-center justify-center rounded-full border-2 border-teal-600 bg-white"
                        aria-current="step"
                    >
                        <span
                            class="h-2 w-2 rounded-full bg-teal-600"
                            aria-hidden="true"
                        />
                        <span class="sr-only">{{ step.name }}</span>
                    </a>
                </template>
                <template v-else>
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div class="h-0.5 w-full bg-gray-200" />
                    </div>
                    <a
                        :href="step.href"
                        class="pointer-events-none group relative flex h-4 w-4 items-center justify-center rounded-full border-2 border-gray-300 bg-white hover:border-gray-400"
                    >
                        <span
                            class="h-2.5 w-2.5 rounded-full bg-transparent group-hover:bg-gray-300"
                            aria-hidden="true"
                        />
                        <span class="sr-only">{{ step.name }}</span>
                    </a>
                </template>
            </li>
        </ol>
    </nav>
</template>

<script setup>
const props = defineProps(["steps"]);
</script>

<style lang="scss" scoped></style>
