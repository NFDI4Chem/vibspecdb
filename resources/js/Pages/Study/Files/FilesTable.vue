<template>
  <UniTable
    :data="dataRow"
    :header="header"
    :visible="visible"
    :actions="actions"
    :title="title"
    :style="style"
    class="m-3"
    :mobileHide="[2, 3]"
    @onPaginationChange="onPaginationChange"
    :detailSlot="detailSlot"
  />
</template>

<script setup>
import { UniTable } from '@/packages/serviceappvuecomponents/src'
import FileTableDetails from '@/Pages/Study/Files/FileTableDetails.vue'
import { computed, reactive, ref } from 'vue'

import {
  ChevronDownIcon,
  TrashIcon,
  PlusCircleIcon,
  PencilSquareIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps(['files'])
const emit = defineEmits(['onShowDetails'])

const title = 'Base Table Template'
const detailSlot = FileTableDetails

const header = {
  name: 'Name',
  created_at: 'CreatedAt',
  updated_at: 'UpdatedAt',
}

const style = {
  greyRows: true,
  divideX: true,
  themeColor: 'cyan',
  rowActions: 'icons',
}

const visible = {
  all: true,
  theader: true,
  tbody: true,
  commonActions: false,
  search: false,
  rowActions: true,
  checkBoxes: false,
  pagination: true,
  filters: true,
  sort: true,
}

const GlobalRowAction = key => {
  console.log(`Action "${key}" here`)
  switch (key) {
    case 'add':
      alert(`Action type: ${key}`)
      break
    case 'delete':
      alert(`Rows to be deleted (ids): ` + JSON.stringify(checkedRows.value))
      break
    default:
      alert('Global RowAction')
  }
}

const actions = [
  {
    key: 'add',
    icon: PlusCircleIcon,
    action: GlobalRowAction,
    visible: true,
  },
  {
    key: 'delete',
    icon: TrashIcon,
    action: GlobalRowAction,
    visible: true,
  },
]

const tableConfig = ref({
  perPage: 5,
  currentPage: 1,
})

const dataRow = computed(() => {
  const id_start =
    (tableConfig.value.currentPage - 1) * tableConfig.value.perPage
  const id_end = tableConfig.value.currentPage * tableConfig.value.perPage

  return {
    rows: props.files.slice(id_start, id_end),
    pagination: {
      currentPage: tableConfig.value.currentPage,
      perPage: tableConfig.value.perPage,
      totalItems: props?.files?.length,
      options: [1, 5, 10, 25, 50, 100],
    },
    actions: [
      {
        key: 'details',
        title: 'Details',
        icon: ChevronDownIcon,
        visible: true,
        action: (rowId, actionKey) => DetailsAction(rowId, actionKey),
      },
    ],
  }
})

const onPaginationChange = changes => {
  tableConfig.value = {
    ...tableConfig.value,
    ...changes,
  }
}

const DetailsAction = (rowId, actionKey) => {
  emit('onShowDetails', rowId)
}
</script>

<style lang="scss" scoped></style>
