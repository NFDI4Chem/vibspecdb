<template>
  <div
    :key="refreshKey"
    id="upload-files-plotter-preview"
    class="w-full h-full"
    :ref="plot"
  />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import Plotly from 'plotly.js-dist-min'

const plot = ref()
const errorBars = ref(false)

const props = defineProps(['input'])

const dinp = computed(() => {
  const result = []
  props?.input.map(line => {
    result.push({
      x: line.x,
      y: line.y,
      // line: { color: 'blue' },
      mode: 'lines',
      name: line?.name,
      type: 'scatter',
    })

    if (errorBars.value) {
      result.push({
        x: line.x,
        y: line.y.map(y => {
          return Math.round(y + Math.sqrt(Math.abs(y)))
        }),
        line: { width: 0 },
        mode: 'lines',
        name: `${line?.name} + Error Bar`,
        showlegend: false,
      })

      result.push({
        x: line.x,
        y: line.y.map(y => {
          return Math.round(y - Math.sqrt(Math.abs(y)))
        }),
        fill: 'tonexty',
        line: { width: 0 },
        mode: 'lines',
        name: `${line?.name} - Error Bar`,
        showlegend: false,
      })
    }
  })
  return result
})

var layout = {
  // height: 400,
  // title: 'Spectra Plot Viewer',
  showlegend: true,
  paper_bgcolor: 'rgba(255,255,255, 1)',
  plot_bgcolor: 'rgba(229,229,229, 0.5)',
  xaxis: {
    title: 'Wavenumber / cm-1',
    gridcolor: 'rgb(255,255,255)',
    // range: [1, 10],
    showgrid: true,
    showline: false,
    showticklabels: true,
    tickcolor: 'rgb(127,127,127)',
    ticks: 'outside',
    zeroline: false,
  },
  yaxis: {
    title: 'Intencity / arb.u.',
    gridcolor: 'rgb(255,255,255)',
    showgrid: true,
    showline: false,
    showticklabels: true,
    tickcolor: 'rgb(127,127,127)',
    ticks: 'outside',
    zeroline: false,
  },
  margin: {
    l: 80,
    r: 15,
    b: 80,
    t: 10,
  },
  modebar: {
    orientation: 'v',
    // bgcolor: 'rgba(255 ,255 ,255 ,0.7)',
    // iconColor: 'rgba(0, 31, 95, 0.3)',
    // logoColor: 'rgba(0, 31, 95, 0.3)',
    position: 'left',
  },
}

const config = {
  responsive: true,
  staticPlot: false,
  displaylogo: false,
  displayModeBar: true,
  toImageButtonOptions: {
    format: 'svg', // one of png, svg, jpeg, webp
    // filename: 'custom_image',
    // height: 500,
    // width: 700,
    // scale: 1, // Multiply title/legend/axis/canvas sizes by this factor
  },
  modeBarButtonsToRemove: [''],
}

onMounted(() => {
  Plotly.newPlot('upload-files-plotter-preview', dinp.value, layout, config)
})
</script>

<style lang="scss">
.upload-files-plotter-preview {
  .plotly {
    .svg-container {
      width: 100% !important;
    }
  }
}
</style>
