<template lang="">
  <div ref="uplotexample" class="w-full">
    <UplotVue
      :data="input"
      :options="options"
      @create="onCreateFromTemplate"
      @delete="onDeleteFromTemplate"
    />
  </div>
</template>
<script>
export default {
  data() {
    return {
      options: {
        title: 'Example uPlot (Area Fill)',
        height: 500,
        scales: {
          x: {
            time: false,
          },
        },
        series: [
          {},
          {
            stroke: 'red',
            fill: 'rgba(255,0,0,0.1)',
          },
          {
            stroke: 'green',
            fill: 'rgba(0,255,0,0.1)',
          },
          {
            stroke: 'blue',
            fill: 'rgba(0,0,255,0.1)',
          },
        ],
      },
      input: [],
    }
  },
  mounted() {
    let xs = [
      1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21,
      22, 23, 24, 25, 26, 27, 28, 29, 30,
    ]
    let vals = [
      -10, -9, -8, -7, -6, -5, -4, -3, -2, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
    ]

    this.input = [
      xs,
      xs.map((t, i) => vals[Math.floor(Math.random() * vals.length)]),
      xs.map((t, i) => vals[Math.floor(Math.random() * vals.length)]),
      xs.map((t, i) => vals[Math.floor(Math.random() * vals.length)]),
    ]

    this.getSize()
    window.addEventListener('resize', this.getSize)
  },
  unmounted() {
    window.removeEventListener('resize', this.getSize)
  },
  methods: {
    onCreateFromTemplate(/* chart: uPlot */) {
      console.log('Created from template')
    },
    onDeleteFromTemplate(/* chart: uPlot */) {
      console.log('Deleted from template')
    },
    getSize() {
      this.options = {
        ...this.options,
        width: this.$refs.uplotexample.clientWidth,
        // height: this.$refs.uplotexample.clientHeight,
      }
    },
  },
}
</script>
<style lang=""></style>
