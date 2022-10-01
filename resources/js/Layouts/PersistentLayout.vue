<template>
  <div class="default-layout-class relative z-20">
    <slot />
    <FilesModal :title="title" />
    <NotificationBox :notifications="notifications" @onDelete="deleteNotify"/>
  </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import FilesModal from '@/Components/UploadModal/FilesModal.vue';
import { useForm, usePage } from "@inertiajs/inertia-vue3";

import { useNotifications } from "@/VueComposable/useNotifications";

import NotificationBox from '@/Layouts/Elements/NotificationBox.vue'
import { notifications } from "@/VueComposable/store";


const {  addNotify, deleteNotify } = useNotifications();
const user = computed(() => usePage().props.value.user);

onMounted(() => {
  let  channel = Echo.channel('user-channel');
  channel
      .listen('.UserEvent',function (data){
          console.log('listended', data);
      })

  Echo.private(`App.Models.User.${user.value.id}`)
    .listen('.UserJobs.submitted', async (data) => {
      console.log('data private', data)
      await addNotify(data?.message);
    });
});


const title = 'Upload Files';

</script>