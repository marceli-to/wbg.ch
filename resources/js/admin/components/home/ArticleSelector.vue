<template>
  <div>
    <h1>Artikel auswählen</h1>
    <div class="article-selector">
      <div v-for="n in news" :key="n.id" @click.prevent="storeArticle(n.id)">
        <div class="article-selector__item">
          <div class="article-selector__text">
              <h2>{{ n.title }}</h2>
              <div class="item-text">{{ n.text }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  
  data() {
    return {
    }
  },

  props: {
    news: Array
  },

  methods: {
    storeArticle(newsId) {
      this.$parent.storeArticle(newsId);
    },
  },
  
  computed: {
    filtered() {
      return this.$props.news.filter(n => {
        return n;
      })
    }
  }
};
</script>