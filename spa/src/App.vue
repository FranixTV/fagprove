<template>
  <ArticleListing :articles="articles" v-if="!article"/>
  <ArticleReadmore v-if="article" :article="article"/>
</template>

<script>
import ArticleListing from './components/ArticleListing.vue'
import ArticleReadmore from './components/ArticleReadmore.vue'

export default {
  name: 'App',
  components: {
    ArticleListing,
    ArticleReadmore
  },
  data() {
    return {
      article: null,
      articles: null
    }
  },
  mounted() {
    this.getArticles();
  },
  methods: {
    getArticles: function () {
      fetch('http://localhost:8001/API/articles', {
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        }})
          .then(response => response.json())
          .then(data => this.articles = data);
    },
  }
}
</script>

<style>
</style>
