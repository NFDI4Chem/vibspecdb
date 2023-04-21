import { ref } from 'vue'

export const spectraData = ref()
export const showOverlay = ref(false)

const selectTreeItem = ref()
export const selectTreeFolder = ref('/')
export const activeItem = ref({
  id: 0,
  path: '/',
})

export const displaySelected = file => {
  selectTreeItem.value = file

  let sFolder = '/'
  if (selectTreeItem.value?.name == '/') {
    sFolder = '/'
  } else {
    if (selectTreeItem.value?.type != 'file') {
      sFolder = selectTreeItem.value?.name
    } else {
      sFolder =
        selectTreeItem.value?.parent_id == null
          ? '/'
          : selectTreeItem.value?.name
    }
  }

  selectTreeFolder.value = sFolder
}

export const storeSelected = file => {
  activeItem.value = file
}
