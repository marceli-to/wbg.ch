<template>
  <div>
    <div class="form-row" v-if="asset == null">
      <label for="document">{{labelNew}}</label>
      <vue-dropzone
        ref="dropzone"
        id="dropzone"
        :options="dropzoneConfig"
        @vdropzone-complete="afterUpload"
      ></vue-dropzone>
      <span class="dz-restrictions">{{labelRestrictions}}</span>
    </div>
    <div class="form-row" v-if="asset">
      <label>{{labelExisting}}</label>
      <div class="dropzone-existing-assets">
        <div>
          <figure :class="[assetType == 'image' ? 'is-image' : '', 'dz-existing-asset']">
            <a :href="getAssetUri(asset)" target="_blank" class="dz-file-preview">
              <img :src="getAssetSource(asset)" height="300" width="300">
            </a>
            <div class="dz-toolbar">
              <a :href="getAssetUri(asset)" target="_blank" class="icon-external-link icon-mini"></a>
              <a
                href="javascript:;"
                class="icon-trash icon-mini"
                @click.prevent="deleteUpload(asset)"
              ></a>
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
    asset: String,
    assetType: String,
    acceptedFiles: String,
    maxFiles: Number,
    maxFilesize: Number,
    uploadUrl: String
  },

  data() {
    return {
      dropzoneConfig: dropzoneConfig
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

    getAssetUri(asset) {
      return `/media/${asset}/sm`;
    },

    getAssetSource(asset) {
      return `/media/thumbnail/${asset}`;
    }
  }
};
</script>