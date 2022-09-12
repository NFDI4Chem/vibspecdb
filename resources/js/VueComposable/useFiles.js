import { usePage } from "@inertiajs/inertia-vue3";
import { computed, ref } from 'vue'

export const useFiles = () => {

  const study = computed(() => usePage().props.value.study)

  const showChildsAPI = (file) => {
    file.loading = true;
    axios
        .get("/api/v1/files/children/" + study.value.id + "/" + file.id)
        .then((response) => {
            file.children = response.data.files[0].children;
            file.loading = false;
        });
  };
  const create = (file) => {
    axios
        .post("/api/v1/files/create", {id: file.id})
        .then((response) => {
            console.log('response', response?.data)
        });
  };
  return {
    showChildsAPI,
    create
  }
}