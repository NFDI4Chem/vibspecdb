import { ref } from 'vue'

export const split_views = ref({
  top_visible: false,
  left_visible: true,
  right_visible: true,
  metainfo_visible: false,
})

export const layout_switcher = (files = []) => {
  const treeNotEmpty = files?.length > 0 && files[0]?.children?.length > 0
  return {
    top_size: () => {
      return split_views.value?.top_visible
    },
    left_size: () => {
      if (!split_views.value?.right_visible) {
        return 100
      }
      if (treeNotEmpty && split_views.value?.left_visible) {
        return 45
      } else {
        return 0
      }
    },
    right_size: () => {
      if (!split_views.value?.left_visible || !treeNotEmpty) {
        return 100
      }
      return split_views.value?.right_visible ? 55 : 0
    },
    metainfo_visible: () => {
      return split_views.value.metainfo_visible
    },
  }
}
