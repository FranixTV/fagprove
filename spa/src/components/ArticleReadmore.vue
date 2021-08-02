<template>
  <div class="article-readmore-outer">
    <button class="close-readmore" @click="closeReadmore">Lukk</button>
    <div class="article-readmore">
      <div class="readmore-image" v-if="hasImages">
        <template v-for="image of images" :key="image">
          <img :src="image"/>
        </template>
      </div>
      <div class="readmore-container">
        <h2 class="readmore-title">{{article.title}}</h2>
        <p class="readmore-summary">{{article.summary}}</p>
        <div class="readmore-content" v-html="article.content"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ArticleReadmore",
  props: ["article"],
  data() {
    return {
      images: null,
      hasImages: false
    }
  },
  created() {
    if(this.article.images) {
      this.images = JSON.parse(this.article.images);
      this.hasImages = true;
    }
  },
  methods: {
    closeReadmore: function () {
      this.$parent.article = null;
    },

  }
}
</script>

<style scoped>
@import "../assets/ArticleReadmore.css";
</style>