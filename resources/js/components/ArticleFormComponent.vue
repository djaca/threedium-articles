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
          :class="['custom-file-input form-control', { 'is-invalid': errors.hasOwnProperty('title') }]"
          type="file"
          id="customFile"
          @input="imageSelected"
        >
        <label class="custom-file-label" for="customFile" v-text="imgName">Choose file</label>
      </div>

      <span
        :class="['invalid-feedback', { 'd-block': errors.hasOwnProperty('body') }]"
        v-if="errors.hasOwnProperty('body')"
      >
        {{ errors['body'][0] }}
      </span>
    </div>

    <div class="form-group">
      <vue-trix
        :class="{ 'is-invalid': errors.hasOwnProperty('body') }"
        v-model="body"
        placeholder="Enter content"
      />
      <span
        :class="['invalid-feedback', { 'd-block': errors.hasOwnProperty('body') }]"
        v-if="errors.hasOwnProperty('body')"
      >
        {{ errors['body'][0] }}
      </span>
    </div>

    <div class="form-group row mb-0">
      <div class="col-md-6">
        <button
          type="submit"
          class="btn btn-primary"
          :disabled="btnDisabled"
          @click="submit"
          >
            Submit
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import VueTrix from "vue-trix"

  export default {
    components: {
      VueTrix
    },

    data () {
      return {
        title: '',
        body: '',
        img: null,
        errors: {}
      }
    },

    computed: {
      btnDisabled () {
        return this.title === '' || this.body === '' || !this.img
      },

      imgName () {
        return this.img ? this.img.name : 'Choose file'
      }
    },

    methods: {
      imageSelected (e) {
        if (! e.target.files.length) return

        this.img = e.target.files[0]
      },

      submit () {
        const formData = new FormData()

        formData.append('title', this.title)
        formData.append('body', this.body)
        formData.append('image', this.img)

        axios.post('/api/articles', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
          .then(({ data }) => {
            flash(data.message, data.status)

            this.errors = {}
            this.title = ''
            this.body = ''
            this.img = null
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

<style>
  .trix-content{
    height: 350px;
    overflow-y: auto;
  }

  .is-invalid > .trix-content {
    border-color: #e3342f;
  }
</style>
