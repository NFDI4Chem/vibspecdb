import { ref } from 'vue'
import { split_views } from '@/VueComposable/useStudyLayer'

export const spectraData = ref()
export const showOverlay = ref(false)

// export const activeDataset = ref()
export const dataset_data = ref({
  metafiles: [],
  dataset: {},
})

export const selectTreeFolder = ref('/')
export const activeItem = ref({
  id: 0,
  path: '/',
})

export const displaySelected = file => {
  const is_dataset = file?.type === 'dataset'
  getDatasetOptions(file)
  selectTreeFolder.value = getSFolder(file)
  split_views.value.metainfo_visible = is_dataset
}

export const getDatasetOptions = file => {
  const is_dataset = file?.type === 'dataset'
  if (is_dataset) {
    dataset_data.value = {
      dataset: { ...file, children: [] },
      metafiles: file.children?.filter(f => {
        return f.type === 'metafile'
      }),
    }
  }
}

export const storeSelected = file => {
  activeItem.value = file
}

const getSFolder = file => {
  let sFolder = '/'
  if (file?.name == '/') {
    sFolder = '/'
  } else {
    if (file?.type != 'file') {
      sFolder = file?.name
    }
  }
  return sFolder
}
