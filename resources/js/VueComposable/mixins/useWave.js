import WaveUI from 'wave-ui'

const common_set = {
  timeout: 4000,
  dismiss: true,
  shadow: true,
  round: false,
  md: true,
  bottom: true,
  right: true,
  tile: true,
  "no-border": true,
}

export const setup_info_notify = (text = '') => {
  const wave = new WaveUI()
  wave.notify({
    ...common_set,
    message: text,
    info: true,
  })
}

export const setup_error_notify = (text = '') => {
  const wave = new WaveUI()
  wave.notify({
    ...common_set,
    message: text,
    error: true,
  })
}

const useWave = {
  data() {
    return {
      common: {
        timeout: 4000,
        dismiss: true,
        shadow: true,
        round: false,
        md: true,
        bottom: true,
        right: true,
        tile: true,
        "no-border": true,
      }
    }
  },
  methods: {
    info_notify(text = '') {
      this.$waveui.notify({
          ...this.common,
          message: text,
          info: true,
      })
    },
    error_notify(text = '') {
      this.$waveui.notify({
          ...this.common,
          message: text,
          error: true,
      })
    }
  },
}

export default useWave