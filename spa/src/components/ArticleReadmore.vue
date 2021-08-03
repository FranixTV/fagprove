<template>
  <div class="article-readmore-outer">
    <i class="fas fa-arrow-left close-readmore" @click="closeReadmore"></i>
    <div class="article-readmore">
      <div class="readmore-image" v-if="hasImages">
        <template v-for="image of images" :key="image">
          <img :src="image"/>
        </template>
      </div>
      <div class="readmore-container">
        <h2 class="readmore-title">{{article.title}}</h2>
        <p class="readmore-summary">{{article.summary}}</p>
        <div class="readmore-details">
          <span class="readmore-author"><i class="fas fa-user"></i> {{article.username}}</span>
          <span class="readmore-created"><i class="fas fa-clock"></i> {{formattedDate}}</span>
        </div>
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
  computed: {
    formattedDate: function () {
      let date = new Date(this.article.created)
      var options = { year: 'numeric', month: 'long', day: 'numeric' };
      return date.toLocaleDateString("no-NO", options);
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