<template>
  <div class="box-wrapper">
    <figure :style="{ backgroundImage: 'url(' + getPreviewImage(img.name) + ')' }">
      <a
        href="javascript:;"
        class="btn-trash"
        @click.prevent="deleteMedia(element.id, $event)">
        Löschen
      </a>
      <a 
        href="javascript:;" 
        class="btn-crop" 
        @click.prevent="toggleOverlay()">
        Crop
      </a>
      <figcaption v-if="element.caption">
        <strong>{{element.caption}}</strong>
      </figcaption>
    </figure>

    <div :class="[hasOverlay ? 'is-visible': '', 'overlay-crop']">
      <a href="javascript:;"
          @click.prevent="toggleOverlay()"
          class="icon-close-overlay">
      </a>
      <div>
        <span class="cropper-info">Neue Grösse:<br>{{ cropW }}px x {{ cropH }}px</span>
        <cropper
          :src="img.uri"
          :stencilProps="{
            aspectRatio: this.$props.ratioW/this.$props.ratioH,
          }"
          @change="change"
        ></cropper>
        <div class="form-buttons">
          <a href="javascript:;" class="btn-secondary" @click.prevent="crop($event)">Speichern</a>
          <a href @click.prevent="toggleOverlay()">Abbrechen</a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import grid from "@/mixins/grid";
import progress from "@/mixins/progress";
import { Cropper } from "vue-advanced-cropper";

export default {
  components: {
    Cropper
  },

  props: {
    element: Object,
    ratioW: Number,
    ratioH: Number
  },
  
  mixins: [grid, progress],

  data() {
    return {
      hasOverlay: false,
      coords: {},
      img: {
        uri: null,
        name: null
      },

      cropW: null,
      cropH: null,
    };
  },

  created() {
    this.img.uri = `/media/${this.$props.element.image}/xl`;
    this.img.name = this.$props.element.image;
  },

  methods: {
    change({ coordinates, canvas }) {
      this.coords.h = coordinates.height;
      this.coords.w = coordinates.width;
      this.coords.y = coordinates.top;
      this.coords.x = coordinates.left;

      this.cropW = Math.floor(coordinates.width);
      this.cropH = Math.floor(coordinates.height);
    },

    crop(event) {
      let btn = event.target;

      let data = {
        data: {
          gridElementId: this.$props.element.id,
          isHomeGrid: true,
          imageId: this.$props.element.imageId,
          image: this.img.name,
          coords: {
            h: this.coords.h,
            w: this.coords.w,
            x: this.coords.x,
            y: this.coords.y
          }
        }
      };
    
      let uri = "/api/project/image/crop";
      let el = this.progress(event.target);
      this.axios.post(uri, data).then(response => {
        this.img.name = response.data.name;
        this.img.uri  = `/media/${this.img.name}/xl`;
        this.axios.get(`/media/${response.data.name}/xl`).then(response => {
          this.progress(el);
          this.toggleOverlay();
        });
      });
    },

    deleteMedia(elementId,event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let el = this.progress(event.target);
        this.$parent.deleteMedia(elementId);
      }
    },

    getPreviewImage(image) {
      return `/media/${image}/lg`;
    },

    toggleOverlay() {
      let html = document.querySelector("html");
      html.classList.toggle("has-overlay");
      this.hasOverlay = this.hasOverlay ? false : true;
    }
  },
};
</script>
<style scoped>
.cropper {
  max-height: 700px;
  background: transparent;
  padding-top: 80px;
}
.cropper-info {
  display:block; 
  margin-bottom: 10px; 
  text-align: left;
}
</style>
