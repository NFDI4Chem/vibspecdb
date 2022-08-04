<template>
  <div>
    <study-content  :project="project" 
      :study="study" 
      current="Jobs">
      <template #study-section>
        <UploadFormUppy :pid="3" @uploaded="onUploaded" />
      </template>
    </study-content >
  </div>
</template>

<script>
import { reactive, toRefs } from 'vue';
import StudyContent from "@/Pages/Study/Content.vue";
import UploadFormUppy from '@/Components/UploadForm/UploadFormUppy.vue'

export default {
  components: {
    StudyContent,
    UploadFormUppy
  },
  props: ["study", "project", "jobs"],
  setup () {
    const state = reactive({
      count: 0,
    })

    const onUploaded = (data) => {
      console.log('onUploaded', data)
    }
  
    return {
      ...toRefs(state),
      onUploaded,
    }
  }
}
</script>

<style lang="scss" scoped>

</style>