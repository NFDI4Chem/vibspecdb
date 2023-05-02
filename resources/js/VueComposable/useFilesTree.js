import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

import { loading, useFiles } from '@/VueComposable/useFiles'

import {
  spectraData,
  showOverlay,
  setUploadFolder,
  setActive,
} from '@/VueComposable/usePlotter'

import { split_views } from '@/VueComposable/useStudyLayer'
import { activeItem, setClicked } from '@/VueComposable/usePlotter'

const { getSpectraData } = useFiles()

export const uniFilesTree = ref()
export const loading_parseMetadata = ref(false)

export const treeOptions = ref({
  checkable: true,
  deleteable: true,
  editable: true,
  createable: true,
  draggable: true,
  showInfo: true,
  showTitle: true,
  menuitem: true,
  title: 'Files Tree',
})

const matchSelectableType = type => {
  return ['directory', 'dataset'].includes(type)
}

const matchSelectableExtension = filename => {
  return ['txt'].includes(filename.split('.').pop())
}

export const TreeItemClick = async (file, parent) => {
  setClicked(file)
  const itemData = matchSelectableType(file.type) ? file : parent
  setUploadFolder(itemData)
  setActive(itemData)

  if (!matchSelectableType(file.type) && matchSelectableExtension(file.name)) {
    showOverlay.value = true
    split_views.value.top_visible = true

    const input = {
      files: [
        {
          src: file?.path,
          path: file?.relative_url,
        },
      ],
    }
    const parsed = await getSpectraData(input)
    spectraData.value = parsed?.x?.map((plotX, idx) => {
      return {
        name: file.name,
        x: plotX,
        y: parsed?.y[idx],
        sd: [],
      }
    })
    showOverlay.value = false
  }
}

export const onTreeCheck = async checked => {
  const files = checked.filter(f => f.type === 'file')

  if (files?.length === 0) {
    return
  }

  showOverlay.value = true
  split_views.value.top_visible = true

  const input = {
    files: files.map(f => ({
      src: f?.path,
      path: f?.relative_url,
    })),
  }

  const parsed = await getSpectraData(input)
  spectraData.value = parsed?.x?.map((plotX, idx) => {
    return {
      name: parsed?.filenames[idx],
      x: plotX,
      y: parsed?.y[idx],
      sd: [],
    }
  })
  showOverlay.value = false
}

export const MakeReload = () => {
  Inertia.reload({ only: ['files'] })
}

// export const MakeReload = study => {
//   const form = useForm({
//     files: null,
//   })
//   form.post(route('study.file-upload.update', study.value.id), {
//     preserveScroll: true,
//     onSuccess: () => form.reset(),
//   })
// }

export const onChangeNodeType = (node, type) => {
  onTreeChange({ ...node, type }, false)
}

export const onDrop = (node, pnode, pnode_old) => {
  onTreeChange({ ...node, parent_id: pnode?.id }, false)
}

const clickMetafileParent = (node = null) => {
  // console.log('node', node)
  if (node?.parent?.type !== 'dataset') {
    return
  }

  setClicked(node)
  const itemData = matchSelectableType(node.type) ? node : node?.parent
  setUploadFolder(itemData)
  setActive(itemData)

  /*
  setActive(node)
  console.log('updateActiveItemChildsMeta start', node?.id)
  parseMetadata(node)
  datasetSubmit(node)
  const node = getNodeInfoByID(activeItem.value?.id)
  console.log('node type', node?.type)
  setActive(node)
  setUploadFolder(node)
  console.log('updated', node)
  */
}

export const onTreeChange = (node, report = true) => {
  loading_parseMetadata.value = true
  const form = useForm(node)
  form.put(route('files.update', node.id), {
    preserveScroll: true,
    onSuccess: () => {
      if (report) {
        setup_info_notify('The item has been updated')
      }
      clickMetafileParent(node)
    },
    onError: err => {
      const message = Object.values(err).join('<br>')
      if (report) {
        setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
      }
    },
    onFinish: () => {
      loading_parseMetadata.value = false
    },
  })
}

export const datasetSubmit = node => {
  parseMetadata(node)
}

const parseMetadata = node => {
  if (!node?.id) {
    return
  }
  loading_parseMetadata.value = true
  const form = useForm(node)
  form.put(route('files.update.meta', node.id), {
    preserveScroll: true,
    onSuccess: () => {
      console.log('onSuccess parseMetadata')
      setActive(node)
      setUploadFolder(node)
    },
    onError: () => {
      console.log('onError parseMetadata')
    },
    onFinish: () => {
      console.log('onFinish parseMetadata')
      loading_parseMetadata.value = false
    },
  })
}

export const onDragend = (tree, store) => {
  const targetNode = tree.getNodeParentByPath(store.targetPath)
  const same_type = store?.dragNode?.type === targetNode?.type
  const allowed = ['directory', 'dataset'].includes(targetNode?.type)
  if (same_type && !allowed) {
    setup_error_notify(
      `Not possible to use this item as parent item for drag-and-drop action.`,
    )
  }
  return !same_type || allowed
}

export const onRemoveItem = (tree, node, path) => {
  console.log('onRemoveItem start')
  node.loading = true
  const form = useForm(node)
  form.delete(route('files.destroy', node.id), {
    preserveScroll: true,
    onSuccess: () => {
      // updateActiveItemChildsMeta(node)
    },
    onError: () => {},
    onFinish: () => {},
  })
}

export const onAddChildren = (node, type) => {
  node.loading = true
  const form = useForm(node)
  form
    .transform(data => {
      const name = type === 'directory' ? 'Folder' : 'Dataset'
      const parent_id = data?.id ?? 0
      const [ftype, size, uppyid] = ['directory', 0, '']
      const { project_id, study_id, level } = data
      return {
        name,
        parent_id,
        type,
        ftype,
        size,
        uppyid,
        project_id: parseInt(project_id),
        study_id: parseInt(study_id),
        level: parseInt(level) + 1,
      }
    })
    .post(route('files.create'), {
      preserveScroll: true,
      onSuccess: file => {
        node.loading = false
      },
      onError: e => {
        console.log('Error onAddChildren', e)
      },
      onFinish: () => {},
    })
}

export const saveDBfiles = files => {
  console.log('saveDBfiles start')
  loading.value = loadingStatus('saving_to_database_loading')

  const files2save = files.map(file => {
    const { name, type: ftype, size, id: uppyid } = file
    const type = 'file'
    const { project_id, study_id, level, parent_id } = file?.meta || {}
    return {
      name,
      type,
      ftype,
      size,
      uppyid,
      project_id: parseInt(project_id),
      study_id: parseInt(study_id),
      level: parseInt(level) + 1,
      parent_id: parseInt(parent_id),
    }
  })
  const form = useForm(files2save)
  form.post(route('files.create'), {
    preserveScroll: true,
    onSuccess: file => {
      setup_info_notify('All files has been successfully uploaded')
      // updateActiveItemChildsMeta()
      loading.value = loadingStatus('saving_to_database_done')
    },
    onError: e => {
      console.log('Error onAddChildren', e)
      setup_error_notify('Failed to store files. ' + e?.message)
      loading.value = loadingStatus('saving_to_database_done')
    },
    onFinish: () => {},
  })
}

const getNodeInfoByID = id => {
  let found
  const tree = uniFilesTree.value?.getTree()
  tree?.walkTreeData((node, index, parent, path) => {
    if (node.id === id) {
      found = { node, index, parent, path }
      return node
    }
  })
  return found?.node
}

export const loadingStatus = step => {
  let load = { ...loading.value }
  switch (step) {
    case 'minio_upload_loading':
      return {
        ...load,
        minio_upload: {
          loading: true,
          done: false,
          error: false,
        },
      }
      break
    case 'minio_upload_done':
      return {
        ...load,
        minio_upload: {
          loading: false,
          done: true,
          error: false,
        },
      }
      break
    case 'minio_upload_error':
      return {
        ...load,
        minio_upload: {
          loading: false,
          done: false,
          error: true,
        },
      }
      break
    case 'saving_to_database_loading':
      return {
        ...load,
        saving_to_database: {
          loading: true,
          done: false,
          error: false,
        },
      }
      break
    case 'saving_to_database_done':
      return {
        ...load,
        saving_to_database: {
          loading: false,
          done: true,
          error: false,
        },
      }
      break
    case 'saving_to_database_error':
      return {
        ...load,
        saving_to_database: {
          loading: false,
          done: false,
          error: true,
        },
      }
      break
    case 'zip_extracting_loading':
      return {
        ...load,
        zip_extracting: {
          loading: true,
          done: false,
          error: false,
        },
      }
      break
    case 'zip_extracting_done':
      return {
        ...load,
        zip_extracting: {
          loading: false,
          done: true,
          error: false,
        },
      }
      break
    case 'zip_extracting_error':
      return {
        ...load,
        zip_extracting: {
          loading: false,
          done: false,
          error: true,
        },
      }
      break
    case 'clear_all':
      return {
        minio_upload: {
          loading: false,
          done: false,
          error: false,
        },
        zip_extracting: {
          loading: false,
          done: false,
          error: false,
        },
        saving_to_database: {
          loading: false,
          done: false,
          error: false,
        },
      }
  }
}
