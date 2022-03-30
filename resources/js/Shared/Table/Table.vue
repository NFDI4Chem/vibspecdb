<template>
  <UniTable
      :data="data"
      :header="header"
      :visible="visible"
      :actions="actions"
      :title="title"
  />
</template>

<script setup>
import { UniTable } from "@/packages/serviceappvuecomponents/src";

const title = "Base Table Template";

const header = ["Name", "Title", "Email", "Role"];

const data = {
    rows: [
        [
            "Lindsay Walton",
            "Front-end Developer",
            "lindsay.walton@example.com",
            "Member",
        ],
        [
            "Breddi Pitt",
            "Backs-end Developer",
            "bredi.pitti@example.com",
            "Admin",
        ],
    ],
    actions: [
        {
            title: "Edit",
            action: () => {
                console.log("Edit action");
            },
        },
        {
            title: "Delete",
            action: () => {
                console.log("Delete action");
            },
        },
    ],
};

const actions = [
    {
        name: "Create",
        action: () => {
            console.log("Action 1 here");
        },
    },
    {
        name: "Delete",
        action: () => {
            console.log("Action 2 here");
        },
    },
];
</script>

<style lang="scss" scoped></style>
