<template>
  <div>
    <study-content  :project="project" 
      :study="study" 
      current="Jobs">
      <template #study-section>
        <div>{{'Title'}}</div>
      </template>
    </study-content >
  </div>
</template>

<script>
import { reactive, toRefs } from 'vue';
import StudyContent from "@/Pages/Study/Content.vue";

export default {
  components: {
    StudyContent
  },
  props: ["study", "project", "jobs"],
  setup () {
    const state = reactive({
      count: 0,
    })
  
    return {
      ...toRefs(state),
    }
  }
}
</script>

<style lang="scss" scoped>

</style>