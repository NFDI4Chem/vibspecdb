module.exports = {
  extends: [
    'eslint:recommended',
    "plugin:vue/vue3-recommended",
    "prettier"
  ],
  rules: {
    "vue/require-default-prop": "off",
    "vue/multi-word-component-names": "off",
    "vue/require-prop-types": "off",
    "vue/no-unused-components": "off",
    "vue/require-explicit-emits": "off",
    "no-unused-vars": "off",
    "vue/component-tags-order": "off",
    "vue/no-setup-props-destructure": "off",
    "no-undef": "off",
    "vue/no-v-html": "off",
    "vue/no-mutating-props": "off",
    "vue/no-template-shadow": "off",
    "vue/require-valid-default-prop": "off",
    "vue/no-arrow-functions-in-watch": "off",
    "vue/no-unused-vars": "off",
  }
}