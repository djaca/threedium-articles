<template>
  <div v-show="loading">
    <fade-loader :loading="loading" color="grey" size="30px"></fade-loader>
  </div>
</template>

<script>
  import FadeLoader from 'vue-spinner/src/FadeLoader.vue'

  export default {
    name: 'VLoader',

    components: {
      FadeLoader
    },

    props: ['loading']
  }
</script>

<style scoped>

</style>
