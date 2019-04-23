<template>
  <form @submit.prevent>
    <div class="form-group">
      <label for="title">Title</label>
      <input
        v-model="title"
        type="text"
        :class="['form-control', { 'is-invalid': errors.hasOwnProperty('title') }]"
        id="title"
      >
        <span class="invalid-feedback" v-if="errors.hasOwnProperty('title')">
          {{ errors['title'][0] }}
        </span>
    </div>

    <div class="form-group">
      <label for="image-upload">Main image</label>

      <div class="custom-file" id="image-upload">
        <input
          :class="['custom-file-input form-control', { 'is-invalid': errors.hasOwnProperty('image') }]"
          type="file"
          id="customFile"
          @input="imageSelected"
        >
        <label class="custom-file-label" for="customFile" v-text="imgName">Choose file</label>
      </div>

      <span
        :class="['invalid-feedback', { 'd-block': errors.hasOwnProperty('image') }]"
        v-if="errors.hasOwnProperty('image')"
      >
        {{ errors['image'][0] }}
      </span>
    </div>

    <div class="form-group">
      <label for="excerpt">Excerpt</label>
      <textarea
        v-model="excerpt"
        :class="['form-control', { 'is-invalid': errors.hasOwnProperty('excerpt') }]"
        id="excerpt"
        rows="5"></textarea>

      <span class="invalid-feedback" v-if="errors.hasOwnProperty('body')">
          {{ errors['body'][0] }}
        </span>
    </div>

    <wysiwyg
      :content="body"
      @text-changed="updateBody"
      :error="errors.hasOwnProperty('body') ? errors['body'][0] : null"
    />

    <div class="form-group row mb-0 mt-4">
      <div class="col-md-6">
        <button
          type="submit"
          class="btn btn-primary"
          @click="submit"
          >
            Submit
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import Wysiwyg from './Wysiwyg'

  export default {
    components: {
      Wysiwyg
    },

    props: ['article'],

    data () {
      return {
        title: '',
        body: '',
        excerpt: '',
        img: '',
        errors: {}
      }
    },

    computed: {
      isEditing () {
        return !!this.article.id
      },

      imgName () {
        return this.img ? this.img.name : 'Choose file'
      }
    },

    mounted () {
      if (this.isEditing) {
        this.title = this.article.title
        this.excerpt = this.article.excerpt
        this.body = this.article.body
      }
    },

    methods: {
      updateBody (val) {
        this.body = val
      },

      imageSelected (e) {
        if (! e.target.files.length) return

        this.img = e.target.files[0]
      },

      submit () {
        const formData = new FormData()

        formData.append('title', this.title)
        formData.append('body', this.body)
        formData.append('excerpt', this.excerpt)
        formData.append('image', this.img)

        if (this.isEditing) {
          formData.append('_method', 'PATCH')
        }

        axios({
          data: formData,
          method: 'POST',
          url: this.isEditing ? `/api/articles/${this.article.id}` : '/api/articles',
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
          .then(({ data }) => {
            flash(data.message, data.status)

            if (!this.isEditing) {
              window.location.href = `/articles/${data.article.id}`
            }
          })
          .catch(({ response }) => {
            if (response.status === 422) {
              this.errors = response.data.errors

              return
            }

            console.log(response)
          })
      }
    }
  }
</script>
