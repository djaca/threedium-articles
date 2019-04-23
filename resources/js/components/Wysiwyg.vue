<template>
  <textarea :name="name"></textarea>
</template>

<script>
  import summernote from 'summernote/dist/summernote-bs4.min'

  export default {
    name: 'Wysiwyg',

    props: {
      value: {
        required: true,
      },

      name: {
        type: String,
        required: true,
      },

      height: {
        type: String,
        default: '500'
      }
    },

    mounted() {
      let config = {
        height: this.height,
        codemirror: { // codemirror options
          theme: 'monokai'
        }
      };

      let vm = this;

      config.callbacks = {
        onInit: function () {
          $(vm.$el).summernote("code", vm.value);
        },
        onChange: function (contents) {
          vm.$emit('input', $(vm.$el).summernote('code'));
        },
        onBlur: function () {
          vm.$emit('input', $(vm.$el).summernote('code'));
        },
        onImageUpload: function(files, ) {
          vm.sendFile(files[0], $(this));
        },
        onMediaDelete : function($target) {
          $target.remove();

          let name = [/[^/]*$/.exec($target[0].src)[0]]

          axios.delete('/api/image-delete', { data: { name } })
        }
      };

      $(this.$el).summernote(config);
    },

    methods: {
      sendFile (file, editor) {
        let data = new FormData();
        data.append("image", file);

        axios.post('/api/image-upload', data)
          .then((result) => {
            let url = '/storage/images/' + result.data

            editor.summernote('insertImage', url);
          })
          .catch((err) => {
            console.log(err)
          })
      }
    }
  }
</script>

<style scoped>
  @import "~summernote/dist/summernote-bs4.css";
</style>
