<template>
  <div class="article-list-outer">
    <div class="filters-container">
      <input aria-label="Frisøk på artikler" class="article-search" type="search" name="freeSearch" placeholder="Frisøk..." v-model="textSearch"/>
      <select aria-label="Sorter artikler basert på alder" name="ageFilter" class="article-age-filter" v-model="ageFilter">
        <option value="DESC">Nyest - Eldst</option>
        <option value="ASC">Eldst - Nyest</option>
      </select>
    </div>
    <div class="article-list" v-if="articles">
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
  props: ["articles"],
  data() {
      return {
        textSearch: "",
        ageFilter: "DESC"
      }
  },
  computed: {
    listArticles: function () {
      let showArticles = this.articles;
      if(this.textSearch) {
        let filter = article => article.title.toLowerCase().includes(this.textSearch.toLowerCase()) || (article.summary && article.summary.toLowerCase().includes(this.textSearch.toLowerCase())) || (article.content && article.content.toLowerCase().includes(this.textSearch.toLowerCase()));
        showArticles = showArticles.filter(filter);
      }
      if(this.ageFilter === "ASC") {
        showArticles = showArticles.sort((article1, article2) => article1.articleid - article2.articleid)
      } else {
        showArticles = showArticles.sort((article1, article2) => article2.articleid - article1.articleid)
      }
      return showArticles;
    }
  },
  methods: {
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