<template>
  <div class="article-list-outer">
    <div class="filters-container">
      <label for="freeSearch">Frisøk</label>
      <input aria-label="Frisøk på artikler" id="freeSearch" class="article-search" type="search" name="freeSearch" v-model="textSearch"/>

      <label for="ageSearch">Sortering</label>
      <select id="ageSearch" aria-label="Sorter artikler basert på alder" name="ageFilter" class="article-age-filter" v-model="ageFilter">
        <option value="DESC">Nyest - Eldst</option>
        <option value="ASC">Eldst - Nyest</option>
      </select>
    </div>
    <div class="article-list" v-if="articles">
      <template v-for="article of listArticles" :key="article.id">
        <Article :showArticle="showArticle" :article="article" @readmore="toggleReadmore" @imageLoad="imageLoaded"/>
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
        ageFilter: "DESC",
        loaded: 0,
        fallback: false
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
    },
    showArticle: function () {
      return (this.loaded === this.articles.length) || this.fallback;
    }
  },
  mounted() {
    setTimeout(() => {
      this.fallback = true;
    }, 500);
  },
  methods: {
    toggleReadmore: function (articleId) {
      let articleProxy = this.articles.find(article => article.articleid === articleId);
      let article = JSON.parse(JSON.stringify(articleProxy));
      this.$parent.article = article;
    },
    imageLoaded: function () {
      this.loaded++;
    }
  },
}
</script>

<style scoped>
@import "../assets/ArticleListing.css";
</style>