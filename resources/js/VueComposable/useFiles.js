import { usePage } from '@inertiajs/inertia-vue3'
import { computed, ref } from 'vue'

export const loading = ref({
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
})

export const useFiles = () => {
  const study = computed(() => usePage().props.value.study)

  const showChildsAPI = file => {
    file.loading = true
    axios
      .get('/api/v1/files/children/' + study.value.id + '/' + file.id)
      .then(response => {
        file.children = response.data?.files[0]?.children
        file.loading = false
      })
  }

  const getSpectraData = async input => {
    try {
      const { data } = await axios.post('/api/v1/spectra/get', input)
      return data
    } catch (err) {
      console.log('error: ', err)
    }
  }

  const getFilesListAPI = async id => {
    const res = await axios.get(`/api/v1/files/list/get/${id}`)
    return res.data
  }

  const extractzip = async file => {
    const res = await axios.get(`/extractzip/${file.id}`)
    return res.data
  }

  const saveFile = async data => {
    const res = await axios.post(`/files/create`, data)
    return res.data
  }

  const parseFile = async url => {
    try {
      const fetchResponse = await fetch(url)
      return fetchResponse?.text()
    } catch (ex) {
      console.log('Error in fetch file', url)
    }
  }

  const getPresignedURL = async (jobid, path) => {
    const res = await axios.post('/api/v1/files/content', { jobid, path })
    return res?.data?.url
  }

  const create = file => {
    axios.post('/api/v1/files/create', { id: file.id }).then(response => {
      console.log('response', response?.data)
    })
  }
  return {
    showChildsAPI,
    getFilesListAPI,
    parseFile,
    getPresignedURL,
    create,
    extractzip,
    saveFile,
    getSpectraData,
    loading,
  }
}
