<template>
  <div :class="singleArticleClass" @click="readmore()">
    <div class="article-image">
      <img tabindex="0" role="link" :alt="article.title" :title="'Les mer av ' + article.title" @load="emitLoad"
           @click="readmore()" v-on:keyup.enter="readmore()" v-if="getFirstImage" :src="getFirstImage"/>
    </div>
    <div class="article-excerpt">
      <h2 tabindex="0" :title="'Les mer av ' + article.title" class="article-title" @click="readmore()" v-on:keyup.enter="readmore()">{{article.title}}</h2>
      <p class="article-summary">{{article.summary}}</p>
    </div>
    <a tabindex="0" class="readmore-button" :title="'Les mer av ' + article.title" @click="readmore()">Les mer</a>
  </div>
</template>

<script>
export default {
  name: "Article",
  props: ["article", "showArticle"],
  computed: {
    getFirstImage: function () {
      if(this.article.images) {
        let images = JSON.parse(this.article.images);
        return images[0];
      }
      return false;
    },
    singleArticleClass: function () {
      return this.showArticle ? "single-article loaded" : "single-article notloaded";
    }
  },
  methods: {
    readmore: function () {
      this.$emit('readmore', this.article.articleid)
    },
    emitLoad: function () {
      this.$emit('imageLoad')
    }
  }
}
</script>