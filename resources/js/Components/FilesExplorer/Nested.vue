<template lang="">
  <draggable
      :list="list" 
      @start="drag = true"
      @end="drag = false"
      group="name"
      item-key="id"
  >
    <template #item="{ element }">
      <div class="item-group">
        <ListItem :item="element" @remove="handleRemove" @add="HandleAdd" />
        <nested class="item-sub" :list="element.children" />
      </div>
    </template>
  </draggable>
</template>


<script>
import draggable from "vuedraggable";
import ListItem from "@/Components/FilesExplorer/ListItem";
import { v4 as uuidv4 } from 'uuid';

export default {
  name: 'nested',
  components: {
    draggable,
    ListItem,
  },
  data() {
    return {
        drag: false,
    }
  },
  methods: {
    handleRemove(id) {
      const idx = this.realValue.findIndex(item => item.id === id);
      this.realValue ? this.realValue.splice(idx, 1) : [];
    },
    HandleAdd(id) {
      this.realValue.map((item) => {
        if (item.id === id) {
          item.children.push({ label: `New Folder ${item.children.length+1}`, id: uuidv4(), children: [] })
        }
        return item;
      })
    },
  },
  computed: {
    realValue() {
      return this.list ? this.list : [];
    },
  },
  props: {
    list: {
      required: true,
      type: Array,
      default: null,
    },
  },
}
</script>
<style lang="css">
  .item-sub {
    margin: 0 0 0 2rem;
  }
</style>