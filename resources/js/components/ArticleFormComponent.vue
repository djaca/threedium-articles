<template>
  <form @submit.prevent>
    <div class="form-group">
      <label for="title">Title</label>
      <input
        v-model="formData.title"
        type="text"
        :class="['form-control', { 'is-invalid': errors.hasOwnProperty('title') }]"
        id="title"
      >
        <span class="invalid-feedback" v-if="errors.hasOwnProperty('title')">
          {{ errors['title'][0] }}
        </span>
    </div>

    <div class="form-group">
      <label for="body">Body</label>
      <textarea
        v-model="formData.body"
        :class="['form-control', { 'is-invalid': errors.hasOwnProperty('body') }]"
        id="body"
        rows="3"
      ></textarea>
      <span class="invalid-feedback" v-if="errors.hasOwnProperty('body')">
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
  export default {
    data () {
      return {
        formData: {
          title: '',
          body: ''
        },
        errors: {}
      }
    },

    computed: {
      btnDisabled () {
        return this.formData.title === '' || this.formData.body === ''
      }
    },

    methods: {
      submit () {
        axios.post('/articles', this.formData)
          .then(({ data }) => {
            flash(data.message, data.status)

            this.errors = {}
            this.formData.title = ''
            this.formData.body = ''
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
