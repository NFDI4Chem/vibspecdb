<template>
    <div>
        <study-content :project="project" :study="study" current="Files">
            <template #study-section>
                <div class="divide-y divide-gray-200 sm:col-span-9">
                    <div v-if="files">
                        <nav
                            v-if="selectTreeFolder"
                            class="flex p-3"
                            aria-label="Breadcrumb"
                        >
                            <ol role="list" class="flex items-center space-x-2">
                                <li>
                                    <div>
                                        <a
                                            class="text-gray-400 hover:text-gray-900"
                                        >
                                            <HomeIcon
                                                class="flex-shrink-0 h-5 w-5"
                                                aria-hidden="true"
                                            />
                                            <span class="sr-only">Home</span>
                                        </a>
                                    </div>
                                </li>
                                <li
                                    v-for="page in selectTreeFolder.split(
                                        '/'
                                    )"
                                    :key="page.name"
                                >
                                    <div
                                        v-if="page != ''"
                                        class="flex items-center"
                                    >
                                        <ChevronRightIcon
                                            class="flex-shrink-0 h-5 w-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                        <a
                                            class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                            :aria-current="
                                                page ? 'page' : undefined
                                            "
                                            >{{ page }}</a
                                        >
                                    </div>
                                </li>
                            </ol>
                        </nav>
                        <div
                            class="min-w-0 flex-1 min-h-screen border-t border-gray-200 lg:flex"
                        >
                            <aside class="py-3 px-2">
                                <div
                                    v-if="treeFilled"
                                    class="h-full min-w-sidebar relative flex flex-col border-r border-gray-200 overflow-y-auto"
                                >
                                    <UniFilesTree
                                        @itemClick="displaySelected"
                                        class="mr-2 min-w-min"
                                        :tree="files"
                                    />
                                </div>
                            </aside>
                            <section
                                class="min-w-0 p-6 flex-1 h-full flex flex-col overflow-y-auto lg:order-last"
                            >
                                <div></div>
                            </section>
                        </div>
                    </div>
                </div>
            </template>
        </study-content>
    </div>
</template>

<script setup>

import {ChevronRightIcon, HomeIcon} from "@heroicons/vue/solid";
import StudyContent from "@/Pages/Study/Content.vue";
import UniFilesTree from "@/Pages/UniFilesTree.vue";
import { ref, computed, onMounted, reactive } from "vue";

const props = defineProps(["study", "project", "files"]);

const selectTreeItem = ref();
const selectTreeFolder = ref();

const treeFilled = computed(() => {
    return props?.files?.length > 0 && props?.files[0].children?.length > 0;
});

const displaySelected = (file) => {
  selectTreeItem.value = file;

  let sFolder = "/";
  if (selectTreeItem.value.name == "/") {
    sFolder = "/";
  } else {
    if (selectTreeItem.value.type != "file") {
      sFolder = selectTreeItem.value.relative_url;
    } else {
      if (selectTreeItem.value.parent_id == null) {
        sFolder = "/";
      } else {
        sFolder = selectTreeItem.value.relative_url.replace(
          "/" + selectTreeItem.value.name,
          ""
        );
      }
    }
  }

  selectTreeFolder.value = sFolder;

  if (file.has_children && file.level > 0 && !file.children) {
    file.loading = true;
    axios
      .get("/api/v1/files/children/" + props.study.id + "/" + file.id)
      .then((response) => {
        file.children = response.data.files[0].children;
        file.loading = false;
      });
  }
}

</script>

<style lang="scss" scoped></style>
