<template>
  <div>
    <div class="card" v-if="articles.length > 0">
      <div class="card-body">
        <div v-for="(article, index) in articles" :key="index">
          <div class="mb-3">
            <h1 v-text="article.title"></h1>
            <h6 v-if="article.author">by <a href="#" @click.prevent="filterBy(article.author)">{{ article.author.name }}</a></h6>
          </div>
          <div v-html="article.body"></div>
          <div>
            <span class="badge-pill badge-secondary">December 4, 2019</span>
          </div>

          <hr v-if="index !== articles.length - 1">
        </div>

        <div v-if="pagination" v-show="!loading" class="mt-5">
          <button
            v-show="pagination.next_page_url"
            type="button"
            class="btn btn-lg btn-block btn-outline-secondary"
            @click="getArticles(pagination.next_page_url)"
          >
            Load more
          </button>
        </div>
      </div>
    </div>

    <v-loader :loading="loading" class="d-flex justify-content-center mt-5"></v-loader>
  </div>
</template>

<script>
  import VLoader from './VLoader'

  export default {
    name: 'ArticlesComponent',

    components: {
      VLoader
    },

    mounted () {
      this.getArticles()
    },

    data () {
      return {
        loading: false,
        articles: [],
        pagination: null
      }
    },

    methods: {
      getArticles (uri) {
        this.loading = true

        let url = uri || '/api/articles'

        axios.get(url)
          .then(({ data }) => {
            data.data.forEach(article => {
              this.articles.push(article)
            })

            this.setPagination(data)
          })
          .catch(e => console.log(e))
          .finally(() => {
            this.loading = false
          })
      },

      filterBy (author) {
        this.articles = []

        this.pagination = null

        this.getArticles(`/api/articles?author=${author.id}`)
      },

      setPagination (data) {
        this.pagination = {
          current_page: data.current_page,
          last_page: data.last_page,
          next_page_url: data.next_page_url,
          prev_page_url: data.prev_page_url
        }
      }
    },
  }
</script>
