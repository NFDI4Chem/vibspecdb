import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

import { useFiles } from '@/VueComposable/useFiles'

import {
  spectraData,
  showOverlay,
  displaySelected,
  storeSelected,
} from '@/VueComposable/usePlotter'

import { split_views } from '@/VueComposable/useStudyLayer'
import { activeItem } from '@/VueComposable/usePlotter'

const { getSpectraData } = useFiles()

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

export const TreeItemClick = async (file, parent) => {
  const itemData = matchSelectableType(file.type) ? file : parent
  displaySelected(itemData)
  storeSelected(itemData)

  if (!matchSelectableType(file.type)) {
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

export const MakeReload = study => {
  const form = useForm({
    files: null,
  })
  form.post(route('study.file-upload.update', study.value.id), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}

export const onChangeNodeType = (node, type) => {
  onTreeChange({ ...node, type }, false)
}

export const onDrop = (node, pnode, pnode_old) => {
  onTreeChange({ ...node, parent_id: pnode?.id }, false)
}

const updateActiveItemChilds = file => {
  activeItem.value = {
    ...activeItem.value,
    children: activeItem.value?.children?.map(item => {
      return item.id === file.id ? { ...item, type: file.type } : item
    }),
  }
}

export const onTreeChange = (node, report = true) => {
  node.loading = true
  const form = useForm(node)
  form.put(route('files.update', node.id), {
    preserveScroll: true,
    onSuccess: () => {
      if (report) {
        setup_info_notify('The item has been updated')
      }
      updateActiveItemChilds(node)
    },
    onError: () => {
      const message = Object.values(err).join('<br>')
      if (report) {
        setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
      }
    },
    onFinish: () => {
      node.loading = false
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
  node.loading = true
  const form = useForm(node)
  form.delete(route('files.destroy', node.id), {
    preserveScroll: true,
    onSuccess: () => {},
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
      onError: p => {
        console.log('onAddChildren', p)
      },
    })
}
