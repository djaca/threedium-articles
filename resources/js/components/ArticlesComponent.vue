<template>
  <div>
    <div class="card" v-if="articles.length > 0">
      <div class="card-body">
        <div v-for="(article, index) in articles" :key="index">
          <div class="mb-3">
            <h1 v-text="article.title"></h1>
            <h6 v-if="article.author">by <a href="#" @click.prevent="filterBy(article.author.id)">{{ article.author.name }}</a> on <span class="badge-pill badge-light" style="font-size: 0.8rem">December 4, 2019</span></h6>
          </div>

          <div
            class="img"
            @click="showArticle(article)"
            :style="{ 'background-image': 'url(' + article.image + ')' }"
            v-if="article.image"
          >
          </div>

          <div v-html="article.excerpt" class="my-2"></div>

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

  function getQueryParameters (str) {
    return (str || document.location.search).replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
  }

  export default {
    name: 'ArticlesComponent',

    components: {
      VLoader
    },

    mounted () {
      window.onpopstate = () => {
        this.articles = []

        this.pagination = null

        this.getArticles()
      };

      let urlParams = getQueryParameters(window.location.search)

      if (urlParams.hasOwnProperty('author')) {
        return this.fetchArticlesFor(urlParams.author)
      }

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
          .catch(({ response }) => {
            this.articles = []

            this.pagination = null

            if (response.status === 404) {
              flash('Can`t find author', 'danger')

              window.history.replaceState({}, null, '/articles')

              this.getArticles()
            }
          })
          .finally(() => {
            this.loading = false
          })
      },

      filterBy (id) {
        this.articles = []

        this.pagination = null

        this.fetchArticlesFor(id)

        history.pushState({}, null, `${window.location.href}?author=${id}`);
      },

      fetchArticlesFor(id) {
        return this.getArticles(`/api/articles?author=${id}`)
      },

      setPagination (data) {
        this.pagination = {
          current_page: data.current_page,
          last_page: data.last_page,
          next_page_url: data.next_page_url,
          prev_page_url: data.prev_page_url
        }
      },

      showArticle (article) {
        window.location.href = `/articles/${article.id}`
      }
    },
  }
</script>

<style scoped>
  .card {
    font-size: 1rem;
  }

  .img {
    height: 200px;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    cursor: pointer;
  }
</style>
