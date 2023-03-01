<template>
  <app-layout :title="assay.name">
    <template #header>
      <div>
        <div
          class="flex items-center text-sm text-gray-700 uppercase font-bold tracking-widest"
        >
          <Link :href="route('assay', assay.id)" class="hover:text-teal-600">{{
            assay.name
          }}</Link
          >&nbsp;/&nbsp;Settings
        </div>
      </div>
    </template>
    <div class="py-12 px-10">
      <div>
        <assay-delete :assay="assay"></assay-delete>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue3'
import AssayDelete from '@/Pages/Assay/Partials/Delete.vue'

export default {
  components: {
    Link,
    AppLayout,
    AssayDelete,
  },
  props: ['assay', 'studies'],
}
</script>
