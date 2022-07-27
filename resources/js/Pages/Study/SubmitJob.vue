<template>
  <div>
    <study-content  :project="project" 
      :study="study" 
      current="Submit Job">
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
  props: ["study", "project", "files"],
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