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
    info_error(text = '') {
      this.$waveui.notify({
          ...this.common,
          message: text,
          error: true,
      })
    }
  },
}

export default useWave