<template>
  <div class="single-article">
    <div class="article-image">
      <img role="link" :title="'Les mer av ' + article.title" @click="readmore()" v-if="getFirstImage" :src="getFirstImage"/>
    </div>
    <div class="article-excerpt">
      <h2 :title="'Les mer av ' + article.title" class="article-title" @click="readmore()">{{article.title}}</h2>
      <p class="article-summary">{{article.summary}}</p>
    </div>
    <a class="readmore-button" :title="'Les mer av ' + article.title" @click="readmore()">Les mer</a>
  </div>
</template>

<script>
export default {
  name: "Article",
  props: ["article"],
  computed: {
    getFirstImage: function () {
      if(this.article.images) {
        let images = JSON.parse(this.article.images);
        return images[0];
      }
      return false;
    }
  },
  methods: {
    readmore: function () {
      this.$emit('readmore', this.article.articleid)
    }
  }
}
</script>