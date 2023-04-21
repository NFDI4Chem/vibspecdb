import { ref } from 'vue'
import { split_views } from '@/VueComposable/useStudyLayer'

export const spectraData = ref()
export const showOverlay = ref(false)

export const selectTreeFolder = ref('/')
export const activeItem = ref({
  id: 0,
  path: '/',
})

export const activeDataset = ref()

export const displaySelected = file => {
  let sFolder = '/'
  switch (file?.name) {
  }
  if (file?.name == '/') {
    sFolder = '/'
  } else {
    if (file?.type != 'file') {
      sFolder = file?.name
    }
  }

  selectTreeFolder.value = sFolder

  const is_dataset = file?.type === 'dataset'
  if (is_dataset) {
    activeDataset.value = file
  }
  split_views.value.metainfo_visible = is_dataset
}

export const storeSelected = file => {
  activeItem.value = file
}
