<template>
  <div class="form-group">
    <vue-editor
      ref="quill"
      v-model="body"
      :class="{ 'is-invalid': error }"
      @text-change="handleTextChanged"
    />

    <span
      :class="['invalid-feedback', { 'd-block': error }]"
      v-if="error"
      v-text="error"
    ></span>
  </div>
</template>

<script>
  import { VueEditor } from 'vue2-editor'

  export default {
    name: 'Wysiwyg',

    components: { VueEditor },

    props: ['content', 'error'],

    data () {
      return {
        body: ''
      }
    },

    methods: {
      handleTextChanged () {
        this.$emit('text-changed', this.body)
      }
    },

    mounted () {
      this.body = this.content
    }
  }
</script>

<style scoped>
  .quillWrapper.is-invalid {
    border: 1px solid #e3342f;
  }

  #quill-container {
    height: 400px;
  }
</style>
