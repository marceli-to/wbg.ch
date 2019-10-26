<template>
  <div class="box-wrapper">
    <article>
        <a
          href="javascript:;"
          class="btn-trash"
          @click.prevent="deleteArticle($event, element.id)"
        >Löschen</a>
      <div>
        <div><strong>{{element.title}}</strong></div>
        <div>{{ element.text | truncate(200, '...') }}</div>
        <div class="article__link" v-if="element.link">
          <a :href="element.link" target="_blank">{{element.linkText}} ({{element.link}})</a>
        </div>
      </div>
    </article>
  </div>
</template>
<script>
export default {
  props: {
    element: Object
  },

  methods: {
    deleteArticle(event, elementId) {
      let btn = event.target;
      btn.classList.add("is-loading");
      this.$parent.deleteArticle(elementId);
    },
    getImageSource(file) {
      return `/media/${file}/sm`;
    }
  }
};
</script>