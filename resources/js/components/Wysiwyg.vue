<template>
  <div class="form-group">
    <vue-editor
      ref="quill"
      v-model="body"
      :class="{ 'is-invalid': error }"
      useCustomImageHandler
      @imageAdded="handleImageAdded"
      @text-change="handleTextChanged"
      :customModules="customModulesForEditor"
      :editorOptions="editorSettings"
    />

    <span
      :class="['invalid-feedback', { 'd-block': error }]"
      v-if="error"
      v-text="error"
    ></span>
  </div>
</template>

<script>
  import { VueEditor, Quill } from 'vue2-editor'
  import ImageResize from 'quill-image-resize-module'

  let Image = Quill.import('formats/image');
  Image.className = 'img-fluid';
  Quill.register(Image, true);

  export default {
    name: 'Wysiwyg',

    components: { VueEditor },

    props: ['content', 'error'],

    data () {
      return {
        body: this.content,
        images: [],
        customModulesForEditor: [
          { alias: 'imageResize', module: ImageResize }
        ],
        editorSettings: {
          modules: {
            imageResize: {}
          }
        }
      }
    },

    computed: {
      bodyIsEmpty () {
        return this.body === ''
      }
    },

    methods: {
      handleTextChanged (delta, oldDelta, source) {
        this.$emit('text-changed', this.body)

        this.handleImageDelete(oldDelta)
      },

      handleImageDelete (oldDelta) {
        if (this.images.length === 0) { return }

        let data = this.$refs.quill.quill.getContents().diff(oldDelta).ops[0].insert

        if (data && data.hasOwnProperty('image')) {
          let imageName = /[^/]*$/.exec(data.image)[0]

          this.images.splice(this.images.indexOf(imageName), 1)

          this.deleteImageRemote(imageName)
        }
      },

      deleteImageRemote (data) {
        let name = typeof data === 'string' ? [data] : data

        axios.delete('/api/image-delete', { data: { name } })
      },

      handleImageAdded (file, Editor, cursorLocation, resetUploader) {
        let formData = new FormData();

        formData.append('image', file)

        axios.post('/api/image-upload', formData)
          .then((result) => {
            let url = '/storage/images/' + result.data

            Editor.insertEmbed(cursorLocation, 'image', url)

            resetUploader()

            this.images.push(result.data)
          })
          .catch((err) => {
            console.log(err)
          })
      }
    },

    mounted () {
      this.$watch('content', val => {
        this.body = val

        if (this.bodyIsEmpty) {
          this.images = []
        }
      })

      window.addEventListener('beforeunload', () => {
        if (this.images.length > 0) {
          this.deleteImageRemote(this.images)
        }
      }, false)
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
