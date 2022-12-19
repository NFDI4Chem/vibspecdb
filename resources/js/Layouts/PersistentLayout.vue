<template>
  <div class="default-layout-class relative z-20">
    <slot />
    <FilesModal v-if="withAuth" :title="title" />
    <NotificationBox :notifications="notifications" @onDelete="deleteNotify"/>
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from "vue";
import FilesModal from '@/Components/UploadModal/FilesModal.vue';
import { useForm, usePage } from "@inertiajs/inertia-vue3";

import { useNotifications } from "@/VueComposable/useNotifications";

import NotificationBox from '@/Layouts/Elements/NotificationBox.vue'
import { notifications } from "@/VueComposable/store";

import { useStore } from "vuex";
const store = useStore();


const {  addNotify, deleteNotify } = useNotifications();
const user = computed(() => usePage().props.value.user);
const withAuth = computed(() => { return usePage().props.value?.user?.id ? true : false });

const fetchAlerts = async () => {
  axios.get(route('users.alerts')).then(res => {
    store.dispatch("update_alerts", res?.data ?? []);
  })
}

watch(withAuth, async (authDone, loggedOut) => {
  if (authDone) {
    await fetchAlerts();
  }
});

const onAuth = async () => {

  if (!withAuth.value) { return false; }

  await fetchAlerts();

  Echo.private(`App.Models.User.${user.value.id}`)
    .listen('.UserJobs.submitted', async (data) => {
      console.log('data private', data)
      if (data?.message?.action === 'update_alerts') {
        await fetchAlerts();
      }
      await addNotify(data?.message);
    });
}

onMounted( async () => {

  await onAuth();
  
  let  channel = Echo.channel('user-channel');
  channel
    .listen('.UserEvent',function (data){
        console.log('listended', data);
    })

});


const title = 'Upload Files';

</script>