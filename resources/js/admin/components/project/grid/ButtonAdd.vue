<template>
  <div class="box-buttons">
    <div>
      <a
        href="javascript:;"
        class="btn-add-media"
        @click.prevent="showImages(gridId,gridPosition);"
      >Bild auswählen</a>
    </div>
  </div>
</template>
<script>
export default {
  
  props: {
    gridId: Number,
    gridPosition: Number,
  },

  methods: {
    showImages(gridId, position) {
      this.$parent.showImages(gridId, position);
    },
  }
};
</script>