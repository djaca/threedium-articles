<template>
  <div>
    <table class="table">
      <thead>
      <tr>
        <th scope="col">Title</th>
        <th scope="col">Published</th>
        <th scope="col">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(article, index) in articles" :key="index">
        <td><a :href="'/articles/'+article.id" v-text="article.title"></a></td>
        <td v-text="article.created_at"></td>
        <td>
          <a href="#" class="btn btn-sm btn-outline-info">Edit</a>
          <button
            class="btn btn-sm btn-outline-danger"
            @click="doRemove(article.id)"
          >
            Delete
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <paginate
      v-model="page"
      :page-count="pageCount"
      :margin-pages="2"
      :page-range="5"
      :container-class="'pagination justify-content-center'"
      :page-class="'page-item'"
      :prev-class="'page-item'"
      :next-class="'page-item'"
      :page-link-class="'page-link'"
      :prev-link-class="'page-link'"
      :next-link-class="'page-link'"
    />

    <v-loader :loading="loading" class="w-100 d-flex justify-content-center mt-5"></v-loader>
  </div>
</template>

<script>
  import VLoader from './VLoader'
  import Paginate from 'vuejs-paginate'

  export default {
    name: 'VTable',

    components: {
      VLoader, Paginate
    },

    data () {
      return {
        loading: false,
        page: 1,
        pageCount: 0,
        articles: []
      }
    },

    watch: {
      page () {
        this.getArticles()
      }
    },

    methods: {
      getArticles () {
        this.loading = true

        axios.get(`/api/articles?author=${App.user.id}&page=${this.page}&per_page=10`)
          .then(({data}) => {
            this.articles = data.data

            this.pageCount = Math.ceil(data.total / data.per_page)
          })
          .catch(e => console.log(e))
          .finally(() => {
            this.loading = false
          })
      },

      doRemove (id) {
        axios.delete(`/api/articles/${id}`)
          .then(({ data }) => {
            flash(data.message, data.status)

            this.articles.splice(this.articles.findIndex(article => article.id === id), 1)
          })
          .catch(err => console.log(err))
      }
    },

    mounted () {
      this.getArticles()
    }
  }
</script>

<style scoped>

</style>
