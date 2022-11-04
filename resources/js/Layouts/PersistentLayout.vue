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

import { useStore } from "vuex";
const store = useStore();


const {  addNotify, deleteNotify } = useNotifications();
const user = computed(() => usePage().props.value.user);

const fetchAlerts = async () => {
    axios.get(route('users.alerts')).then(res => {
      store.dispatch("update_alerts", res?.data ?? []);
    })
  }

onMounted( async () => {
  await fetchAlerts();
  
  let  channel = Echo.channel('user-channel');
  channel
      .listen('.UserEvent',function (data){
          console.log('listended', data);
      })

  Echo.private(`App.Models.User.${user.value.id}`)
    .listen('.UserJobs.submitted', async (data) => {
      console.log('data private', data)
      if (data?.message?.action === 'update_alerts') {
        await fetchAlerts();
      }
      await addNotify(data?.message);
    });
});


const title = 'Upload Files';

</script>