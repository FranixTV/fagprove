<template>
  <div class="article-list">
    <template v-for="article of articles" :key="article.id">
      <Article :article="article" @readmore="toggleReadmore"/>
    </template>
  </div>
</template>

<script>
import Article from './Article.vue'

export default {
  name: "ArticleListing",
  components: {
      Article
  },
  data() {
      return {
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
    toggleReadmore: function (articleId) {
      let articleProxy = this.articles.find(article => article.articleid === articleId);
      let article = JSON.parse(JSON.stringify(articleProxy));
      this.$parent.article = article;
    }
  }
}
</script>

<style scoped>
@import "../assets/ArticleListing.css";
</style>