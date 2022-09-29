<template>
  <div class="default-layout-class relative z-20">
    <slot />
    <FilesModal :title="title" />
  </div>
</template>

<script setup>
import { computed } from "vue";
import FilesModal from '@/Components/UploadModal/FilesModal.vue';
import { useForm, usePage } from "@inertiajs/inertia-vue3";

const user = computed(() => usePage().props.value.user);

let  channel = Echo.channel('user-channel');
channel
    .listen('.UserEvent',function (data){
        console.log(data);
        console.log('listended');
    })

Echo.private(`App.Models.User.${user.value.id}`)
    .listen('.server.created', (e) => {
        console.log('private channel works');
});


const title = 'Upload Files';

</script>