<template>
  <ArticleListing v-if="!article"/>
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
      article: null
    }
  }
}
</script>

<style>
</style>
