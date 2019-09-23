<template>
  <div>
    <div class="form-row">
      <label for="document">{{labelNew}}</label>
      <vue-dropzone
        ref="dropzone"
        id="dropzone"
        :options="dropzoneConfig"
        @vdropzone-complete="afterUpload"
      ></vue-dropzone>
      <span class="bubble is-restriction">{{labelRestrictions}}</span>
    </div>
    <div class="form-row" v-if="assets.length">
      <label>{{labelExisting}}</label>
      <div class="dropzone-existing-assets has-images">
        <div>
            <figure
              :class="[asset.publish == 0 ? 'is-disabled' : '', 'dz-existing-asset is-image']"
              v-for="asset in assets"
              :key="asset.id"
            >
            <a :href="getAssetUri(asset.name)" target="_blank" class="dz-file-preview">
              <img :src="getAssetSource(asset.name)" height="300" width="300">
            </a>
            <div class="dz-toolbar">
              <a
                href="javascript:;"
                :class="[asset.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                @click.prevent="toggleAsset(asset)">
              </a>
              <a
                href="javascript:;"
                class="icon-edit icon-mini"
                @click.prevent="showAssetEdit($event)"
              ></a>
              <a 
                :href="getAssetUri(asset.name)" 
                target="_blank" 
                class="icon-external-link icon-mini">
              </a>
              <a
                href="javascript:;"
                class="icon-trash icon-mini"
                @click.prevent="deleteUpload(asset.name)">
              </a>
            </div>
            <div class="dz-edit-form">
              <a
                href="javascript:;"
                class="dz-icon-hide-form"
                @click.prevent="hideAssetEdit($event)"
              >Schliessen</a>
              <div class="dz-edit-form-row">
                <label>Datei:</label>
                <a 
                  :href="getAssetUri(asset.name)"
                  target="_blank"
                >
                  {{asset.name}}
                </a>
              </div>
              <div class="dz-edit-form-row">
                <label>Alt-Tag:</label>
                <input
                  type="text"
                  v-model="asset.caption"
                  class="is-caption"
                >
              </div>
            </div>
          </figure>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import vue2Dropzone from "vue2-dropzone";
import dropzoneConfig from "@/config/dropzoneconfig.js";

export default {
  components: {
    vueDropzone: vue2Dropzone
  },

  props: {
    labelNew: String,
    labelExisting: String,
    labelRestrictions: String,
    assets: Array,
    assetType: String,
    acceptedFiles: String,
    maxFiles: Number,
    maxFilesize: Number,
    uploadUrl: String
  },

  data() {
    return {
      dropzoneConfig: dropzoneConfig,
      css_classes: {
        visible: 'is-visible'
      }
    };
  },

  created() {
    this.dropzoneConfig.url = this.uploadUrl;
    this.dropzoneConfig.acceptedFiles = this.acceptedFiles;
    this.dropzoneConfig.maxFiles = this.maxFiles;
    this.dropzoneConfig.maxFilesize = this.maxFilesize;
  },

  methods: {

    afterUpload(asset) {
      this.$refs.dropzone.removeFile(asset);
      this.$parent.afterUpload(asset);
    },

    deleteUpload(asset) {
      this.$parent.deleteUpload(asset);
    },

    toggleAsset(asset) {
      this.$parent.toggleAsset(asset);
    },

    showAssetEdit(e) {
      let editForm = e.target.parentNode.nextElementSibling;
      editForm.classList.toggle(this.css_classes.visible);
    },

    hideAssetEdit(e) {
      let editForm = e.target.parentNode;
      editForm.classList.toggle(this.css_classes.visible);
    },

    getAssetUri(asset) {
      return `/media/${asset}/sm`;
    },

    getAssetSource(asset) {
      return `/media/thumbnail/${asset}`;
    }
  }
};
</script>