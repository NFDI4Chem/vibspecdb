<template>
  <w-confirm 
      question="Are you sure you want to delete your project? Once your
          project is deleted, all of its resources and data will be
          permanently deleted."
      bg-color="error-light3"
      md
      @confirm="deleteProject"
      :disabled="form.processing"
      transition="scale"
  >
      <slot></slot>
  </w-confirm>
</template>

<script setup>

import { Inertia } from '@inertiajs/inertia';
import { useForm } from "@inertiajs/inertia-vue3";

import { setup_info_notify, setup_error_notify } from "@/VueComposable/mixins/useWave";

const props = defineProps({
    pid: {
        type: [Number, String],
        default: -1,
        required: true
    },
});

const form = useForm({});

const deleteProject = () => {
  form.delete(route("project.destroy", props.pid), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      setup_info_notify('Project deleted successfully');
    },
    onError: () => {
      setup_error_notify('Failed to Delete the Project');
    },
    onFinish: () => form.reset(),
  });
}

</script>