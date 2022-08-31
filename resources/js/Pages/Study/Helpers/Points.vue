<template>
    <div class="container">
        <ul class="progressbar">
            <li
                v-for="id in Array.from(Array(n).keys())"
                :key="`id_${id}`"
                :class="{ ['active']: (active - 1) === id }"
            ></li>
        </ul>
    </div>
</template>

<script setup>
const props = defineProps(["n", "active"]);
</script>

<style lang="scss" scoped>
.container {
    width: 160px;
    position: absolute;
    z-index: 1;
    // display: flex;
    // margin-top: 20px;
}
.progressbar {
    counter-reset: step;
}
.progressbar li {
    float: left;
    width: 14.28%;
    position: relative;
    text-align: center;
}
.progressbar li:before {
    content: "";
    counter-increment: step;
    width: 14px;
    height: 14px;
    border: 1px solid #bebebe;
    display: block;
    // margin: 0 auto 10px auto;
    border-radius: 50%;
    line-height: 27px;
    background: white;
    color: #bebebe;
    text-align: center;
    font-weight: bold;
}
.progressbar li:after {
    content: "";
    position: absolute;
    width: 100%;
    height: 2px;
    background: #979797;
    top: 6px;
    left: -50%;
    z-index: -1;
}
.progressbar li.active:before {
    border-color: #149cc9;
    background: #149cc9;
    color: white;
}
.progressbar li.active:after {
    background: #149cc9;
}

.progressbar li:first-child:after {
    content: none;
}
</style>
