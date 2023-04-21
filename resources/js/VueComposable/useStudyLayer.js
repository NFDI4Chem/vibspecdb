import { ref } from 'vue'

export const slit_views = ref({
  top_visible: false,
  left_visible: true,
  right_visible: true,
})

export const layout_switcher = files => {
  const treeNotEmpty = files?.length > 0 && files[0].children?.length > 0
  return {
    top_size: () => {
      return slit_views.value?.top_visible
    },
    left_size: () => {
      if (!slit_views.value?.right_visible) {
        return 100
      }
      if (treeNotEmpty && slit_views.value?.left_visible) {
        return 45
      } else {
        return 0
      }
    },
    right_size: () => {
      if (!slit_views.value?.left_visible || !treeNotEmpty) {
        return 100
      }
      return slit_views.value?.right_visible ? 55 : 0
    },
  }
}
