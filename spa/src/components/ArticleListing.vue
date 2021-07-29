<template>
  <div class="article-list-outer">
    <input class="article-search" type="text" name="freeSearch" placeholder="Frisøk..." v-model="textSearch"/>
    <div class="article-list" v-if="listArticles">
      <template v-for="article of listArticles" :key="article.id">
        <Article :article="article" @readmore="toggleReadmore"/>
      </template>
    </div>
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
        articles: null,
        textSearch: ""
      }
  },
  mounted() {
    this.getArticles();
  },
  computed: {
    listArticles: function () {
      if(!this.textSearch) {
        return this.articles;
      }
      let filter = article => article.title.toLowerCase().includes(this.textSearch.toLowerCase()) || (article.summary && article.summary.toLowerCase().includes(this.textSearch.toLowerCase()));
      return this.articles.filter(filter);
    }
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
  },
}
</script>

<style scoped>
@import "../assets/ArticleListing.css";
</style>