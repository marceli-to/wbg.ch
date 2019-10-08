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
import grid from "@/mixins/grid";
import progress from "@/mixins/progress";

export default {
  props: {
    element: Object
  },

  mixins: [grid, progress],

  methods: {
    deleteArticle(event, elementId) {
      if (confirm("Bitte löschen bestätigen!")) {
        this.progress(event.target);
        this.$parent.deleteArticle(elementId);
      }
    },
    getImageSource(file) {
      return `/media/${file}/sm`;
    }
  }
};
</script>