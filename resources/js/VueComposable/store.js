import { ref, computed } from 'vue'

export const sidebarOpen = ref(false)
export const selectedFiles = ref([])
export const selectedModel = ref(-1)
export const currentStudyStep = ref(0)
export const jobs = ref([])
export const notifications = ref([])

import {
  HomeIcon,
  BriefcaseIcon,
  RectangleGroupIcon,
} from '@heroicons/vue/24/outline'
export const leftMenu = ref([
  {
    name: 'Dashboard',
    shortname: 'Board',
    icon: 'mdi mdi-home',
    href: route('dashboard'),
    active: true,
  },
  {
    name: 'Teams',
    shortname: 'Teams',
    icon: 'mdi mdi-account-group',
    href: route('dashboard'),
    active: true,
  },
  {
    name: 'Settings',
    shortname: 'Settings',
    icon: 'mdi mdi-cog',
    href: route('dashboard'),
    active: true,
  },
  // {
  //   name: 'Models',
  //   shortname: 'Models',
  //   icon: RectangleGroupIcon,
  //   href: route('job_models'),
  //   active: false,
  // },
])

export const updateLeftMenu = page => {
  leftMenu.value = leftMenu.value.map(item => {
    item.active = item.name === page ? true : false
    return item
  })
}

const link_url = study => {
  return {
    files: `/studies/${study?.id}/files`,
    models: `/studies/${study?.id}/models`,
    jobs: `/studies/${study?.id}/jobs`,
  }
}

export const StudySubmitSteps = study => {
  const links = link_url(study)
  return [
    {
      name: 'Step 1: select files to process',
      href: links?.files,
      status: currentStudyStep.value === 1 ? 'current' : '',
    },
    {
      name: 'Step 2: select model to process',
      href: links?.models,
      status: currentStudyStep.value === 2 ? 'current' : '',
    },
    {
      name: 'Step 3: view job details & submit',
      href: links?.jobs,
      status: currentStudyStep.value === 3 ? 'current' : '',
    },
  ]
}

export const onShowDetails = rowId => {
  selectedFiles.value = selectedFiles.value.map(item => {
    return rowId === item.id
      ? {
          ...item,
          detailsOpen: !item?.detailsOpen,
        }
      : item
  })
}
export const onShowJobDetails = rowId => {
  jobs.value = jobs.value.map(item => {
    return rowId === item.id
      ? {
          ...item,
          detailsOpen: !item?.detailsOpen,
          details: {}, // TODO, bug, thie key needs to be added to not break the table on detailsOpen;
        }
      : item
  })
}
