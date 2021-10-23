<template lang="">
  <draggable
      v-model="data"
      group="people"
      @start="drag = true"
      @end="drag = false"
      item-key="id"
  >
    <template #item="{ element }">
      <ListItem :item="element" />
    </template>
  </draggable>
</template>


<script>

import draggable from "vuedraggable";
import ListItem from "@/Components/FilesExplorer/ListItem";

export default {
  components: {
    draggable,
    ListItem,
  },
  props: ["data"],
  data() {
    return {
        drag: false,
    }
  }
}
</script>
<style lang="css">
  
</style>