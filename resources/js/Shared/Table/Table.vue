<template>
    <UniTable
        :data="data"
        :header="header"
        :visible="visible"
        :actions="actions"
        :title="title"
        :style="style"
        class="m-3"
    />
</template>

<script setup>
import { UniTable } from "@/packages/serviceappvuecomponents/src";

import {
  ChevronDownIcon,
  TrashIcon,
  PlusCircleIcon,
  PencilAltIcon,
} from "@heroicons/vue/outline";

const title = "Base Table Template";

const header = {
    name: "Name",
    title: "Title",
    email: "Email",
    role: "Role",
};

const style = {
    greyRows: true,
    divideX: true,
    themeColor: "cyan",
    rowActions: "icons",
};

const visible = {
    all: true,
    theader: true,
    tbody: true,
    commonActions: true,
    search: true,
    rowActions: true,
    checkBoxes: true,
    pagination: true,
    filters: true,
    sort: true
}

const GlobalRowAction = (key) => {
  console.log(`Action "${key}" here`);
  switch (key) {
    case "add":
      alert(`Action type: ${key}`);
      break;
    case "delete":
      alert(`Rows to be deleted (ids): ` + JSON.stringify(checkedRows.value));
      break;
    default:
      alert("Global RowAction");
  }
};

const DetailsAction = (rowId, actionKey) => {
  console.log("Open Details action", rowId, actionKey);
};

const actions = [
  {
    key: "add",
    icon: PlusCircleIcon,
    action: GlobalRowAction,
    visible: true,
  },
  {
    key: "delete",
    icon: TrashIcon,
    action: GlobalRowAction,
    visible: true,
  },
];

const data = {
    rows: [
        {
            name: "Lindsay Walton",
            title: "Front-end Developer",
            email: "lindsay.walton@example.com",
            role: "Member",
            detailsOpen: true,
            details: []
        },
        {
            name: "Breddi Pitt",
            title: "Backs-end Developer",
            email: "bredi.pitti@example.com",
            role: "Admin",
            detailsOpen: false,
            details: []
        },
    ],
    actions: [
        {
            key: "details",
            title: "Details",
            icon: ChevronDownIcon,
            visible: true,
            action: (rowId, actionKey) => DetailsAction(rowId, actionKey),
        },
        {
            key: "edit",
            title: "Edit",
            icon: PencilAltIcon,
            visible: true,
            action: (rowId, actionKey) => {
                alert(`Edit action rowId: ${rowId}, actionKey: ${actionKey}`);
            },
        },
        {
            key: "delete",
            title: "Delete",
            icon: TrashIcon,
            visible: true,
            action: (rowId, actionKey) => {
                alert(`Delete action rowId: ${rowId}, actionKey: ${actionKey}`);
            },
        },
    ],
};


</script>

<style lang="scss" scoped></style>
