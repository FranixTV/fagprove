<template>
  <div>
    <ArticleListing :articles="articles" v-if="!article"/>
    <ArticleReadmore v-if="article" :article="article"/>
    <footer v-if="showFooter">
      <p>Rikart Svendsgård</p>
      <p>Fagprøve</p>
      <p>Seria AS</p>
      <p>© Copyright 2021</p>
    </footer>
  </div>
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
      articles: [],
      showFooter: false
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
          .then((data) => {
            this.articles = data;
            setTimeout(() => {
              this.showFooter = true;
            }, 500);
          });
    }
  }
}
</script>

<style scoped>
footer {
  background-color: #333;
  text-align: center;
  padding: .5em;
  margin-top: 5em;
}

footer p {
  margin: 0.25em;
}

footer p:nth-child(1) {
  font-size: 1.2em;
}
</style>
