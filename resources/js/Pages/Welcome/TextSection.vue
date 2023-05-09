<template>
  <!-- Content section -->
  <div class="mx-auto mt-20 max-w-7xl px-6 lg:px-8">
    <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
      <div
        class="grid max-w-xl grid-cols-1 gap-8 text-base leading-7 text-gray-300 lg:max-w-none lg:grid-cols-2"
      >
        <div>
          <p>
            The platform has powerful cloud infrastructure with using
            microservices, SQL-database, cache systems and search engine. The
            main page has easy navigation between projects and studies using the
            project navigation tree.
          </p>
        </div>
        <div>
          <p>
            It has powerful search engine on title, metadata and tags. After
            registration, everyone can create their own projects and stages,
            upload their spectra and work with them.
          </p>
        </div>
      </div>
      <dl
        v-if="false"
        class="mt-16 grid grid-cols-1 gap-x-8 gap-y-12 sm:mt-20 sm:grid-cols-2 sm:gap-y-16 lg:mt-28 lg:grid-cols-4"
      >
        <div
          v-for="(stat, statIdx) in stats"
          :key="statIdx"
          class="flex flex-col-reverse gap-y-3 border-l border-white/20 pl-6"
        >
          <dt class="text-base leading-7 text-gray-300">
            {{ stat.label }}
          </dt>
          <dd class="text-3xl font-semibold tracking-tight text-white">
            {{ stat.value }}
          </dd>
        </div>
      </dl>
    </div>
  </div>
</template>

<script setup>
const stats = [
  { label: 'Business was founded', value: '2012' },
  { label: 'People on the team', value: '120+' },
  { label: 'Users on the platform', value: '250k' },
  { label: 'Paid out to creators', value: '$70M' },
]
</script>

<style lang="scss" scoped></style>
