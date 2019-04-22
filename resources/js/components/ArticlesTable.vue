<template>
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
      <td v-text="article.title"></td>
      <td v-text="article.created_at"></td>
      <td>
        <a href="#" class="btn btn-sm btn-outline-info">Edit</a>
        <button class="btn btn-sm btn-outline-danger">Delete</button>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script>
  export default {
    name: 'ArticlesTable',

    data () {
      return {
        articles: []
      }
    },
  }
</script>

<style scoped>

</style>
