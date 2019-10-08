<template>
  <div>
    <a
      href="javascript:;"
      class="btn-add-media"
      @click.prevent="createMedia(gridId,gridPosition);"
    >Bild auswählen</a>
  </div>
</template>
<script>
export default {
  props: {
    gridId: Number,
    gridPosition: Number,
  },

  methods: {
    createMedia(gridId,position) {
      this.$parent.createMedia(gridId, position);
    },
  }
};
</script>