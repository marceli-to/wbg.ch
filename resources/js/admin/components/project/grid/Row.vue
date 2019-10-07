<template>
  <div>
    <div class="ratio-boxes">
      <div v-if="layout == '1fr'">
        <div class="box-1fr">
          <div class="box__c">
            <div v-if="elements[0] && elements[0].position == '0'">
              <grid-media :ratioW="86" :ratioH="56" :element="elements[0]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="0"></button-add>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr-wide'">
        <div class="box-1fr">
          <div class="box__d">
            <div v-if="elements[0] && elements[0].position == '0'">
              <grid-media :ratioW="86" :ratioH="28" :element="elements[0]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="0"></button-add>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr-portrait'">
        <div class="box-1fr">
          <div class="box__e">
            <div v-if="elements[0] && elements[0].position == '0'">
              <grid-media :ratioW="86" :ratioH="112" :element="elements[0]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="0"></button-add>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '2x1fr-wide'">
        <div class="box-2x1fr">
          <div class="box__a">
            <div v-if="elements[0] && elements[0].position == '0'">
              <grid-media :ratioW="43" :ratioH="28" :element="elements[0]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="0"></button-add>
            </div>
          </div>
          <div class="box__a">
            <div v-if="elements[1] && elements[1].position == '1'">
              <grid-media :ratioW="43" :ratioH="28" :element="elements[1]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="1"></button-add>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '2x1fr'">
        <div class="box-2x1fr">
          <div class="box__b">
            <div v-if="elements[0] && elements[0].position == '0'">
              <grid-media :ratioW="43" :ratioH="56" :element="elements[0]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="0"></button-add>
            </div>
          </div>
          <div class="box__b">
            <div v-if="elements[1] && elements[1].position == '1'">
              <grid-media :ratioW="43" :ratioH="56" :element="elements[1]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="1"></button-add>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '2x1fr-logo'">
        <div class="box-2x1fr">
          <div class="box__a is-logo">
            <div v-if="elements[0] && elements[0].position == '0'">
              <grid-media :ratioW="43" :ratioH="28" :element="elements[0]"></grid-media>
            </div>
            <div v-else>
              <button-add :ratioW="43" :ratioH="28" :gridId="gridId" :gridPosition="0"></button-add>
            </div>
          </div>
          <div class="box__a is-logo">
            <div v-if="elements[1] && elements[1].position == '1'">
              <grid-media :element="elements[1]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="1"></button-add>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr-1fr_stacked'">
        <div class="box-2x1fr">
          <div class="box__b">
            <div v-if="elements[0] && elements[0].position == '0'">
              <grid-media :ratioW="43" :ratioH="56" :element="elements[0]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="0"></button-add>
            </div>
          </div>
          <div class="span">
            <div class="grid-stacked">
              <div class="box__a">
                <div v-if="elements[1] && elements[1].position == '1'">
                  <grid-media :ratioW="43" :ratioH="28" :element="elements[1]"></grid-media>
                </div>
                <div v-else>
                  <button-add :gridId="gridId" :gridPosition="1"></button-add>
                </div>
              </div>
              <div class="box__a">
                <div v-if="elements[2] && elements[2].position == '2'">
                  <grid-media :ratioW="43" :ratioH="28" :element="elements[2]"></grid-media>
                </div>
                <div v-else>
                  <button-add :gridId="gridId" :gridPosition="2"></button-add>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr_stacked-1fr'">
        <div class="box-2x1fr">
          <div class="span">
            <div class="grid-stacked">
              <div class="box__a">
                <div v-if="elements[0] && elements[0].position == '0'">
                  <grid-media :ratioW="43" :ratioH="28" :element="elements[0]"></grid-media>
                </div>
                <div v-else>
                  <button-add :gridId="gridId" :gridPosition="0"></button-add>
                </div>
              </div>
              <div class="box__a">
                <div v-if="elements[1] && elements[1].position == '1'">
                  <grid-media :ratioW="43" :ratioH="28" :element="elements[1]"></grid-media>
                </div>
                <div v-else>
                  <button-add :gridId="gridId" :gridPosition="1"></button-add>
                </div>
              </div>
            </div>
          </div>
          <div class="box__b">
            <div v-if="elements[2] && elements[2].position == '2'">
              <grid-media :ratioW="43" :ratioH="56" :element="elements[2]"></grid-media>
            </div>
            <div v-else>
              <button-add :gridId="gridId" :gridPosition="2"></button-add>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div :class="[hasOverlay ? 'is-visible': '', 'overlay']">
      <a href="javascript:;" @click.prevent="toggleOverlay()" class="icon-close-overlay"></a>
      <div>
        <h1>Projektbild auswählen</h1>
        <div class="project-selector">
          <div class="project-selector__item">
            <div class="project-selector__media">
              <figure v-for="image in images" :key="image.id">
                <a
                  v-if="image.is_crop"
                  @click.prevent="destroyImage($event,image.name)"
                  class="btn-trash-mini"
                ></a>
                <a href @click.prevent="storeImage(image.id)">
                  <img :src="getAssetSource(image.name)" height="50" width="50">
                </a>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import GridMedia from "@/components/project/grid/Media.vue";
import ButtonAdd from "@/components/project/grid/ButtonAdd.vue";

export default {
  components: {
    ButtonAdd: ButtonAdd,
    GridMedia: GridMedia
  },

  data() {
    return {
      hasOverlay: false,

      tmpGridId: null,
      tmpPosition: null,

      // Project images
      images: [],

      // Grid elements
      elements: []
    };
  },

  props: {
    layout: String,
    gridId: Number,
    projectId: Number
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      let uri = `/api/project/grid/images/${this.$props.gridId}`;
      this.axios.get(uri).then(response => {
        let els = [];
        if (response.data.data) {
          response.data.data.forEach(e => {
            if (e.id) {
              let el = {
                id: e.id,
                position: e.position,
                imageId: e.image.id,
                image: e.image.name,
                caption: e.image.caption
              };
              els[e.position] = el;
            }
          });

          if (els.length > 0) {
            this.elements = els;
          } else if (els.length == 0) {
            this.elements = [];
          }
        }
      });
    },

    showImages(gridId, position) {
      let uri = `/api/project/image/get/${this.$props.projectId}`;
      this.axios.get(uri).then(response => {
        this.images = response.data.data;
        this.toggleOverlay();
        this.tmpGridId = gridId;
        this.tmpPosition = position;
      });
    },

    storeImage(imageId) {
      let data = {
        position: this.tmpPosition,
        grid_id: this.tmpGridId,
        project_image_id: imageId,
        project_id: this.$props.projectId
      };

      let uri = "/api/project/grid/image/store";
      this.axios.post(uri, data).then(response => {
        this.toggleOverlay();
        this.$notify({ type: "success", text: "Bild hinzugefügt" });
        this.fetch();
      });
    },

    deleteImage(id, btn) {
      let uri = `/api/project/grid/image/delete/${id}`;
      btn.classList.add("is-loading");
      this.axios.delete(uri).then(response => {
        btn.classList.remove("is-loading");
        this.$notify({ type: "success", text: "Bild gelöscht" });
        this.fetch();
      });
    },

    destroyImage(event, image) {
      event.stopPropagation();
      if(confirm('Bitte löschen bestätigen!')) {
        let uri = `/api/project/image/delete/${image}`;
        this.axios.delete(uri).then(response => {
          const index = this.images.findIndex(x => x.name === image);
          this.images.splice(index, 1);
        });
      }
    },

    getAssetSource(asset) {
      return `/media/${asset}/xs`;
    },

    toggleOverlay() {
      let html = document.querySelector("html");
      html.classList.toggle("has-overlay");
      this.hasOverlay = this.hasOverlay ? false : true;
    }
  }
};
</script>