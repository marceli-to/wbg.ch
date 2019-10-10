<template>
  <div class="container">
    <notifications classes="notification"/>
    <main class="content" role="main">
      <div>
        <h1>{{title}}</h1>
        <form @submit.prevent="submit">
            <div class="form-row" :class="errors.key ? 'has-error': ''">
              <label>Seite</label>
              <div class="select-wrapper">
                <select
                  v-model="content.key"
                  name="key"
                  @focus="removeError('key')"
                >
                  <option selected="selected">Bitte wählen...</option>
                  <option v-for="k in keys" :key="k.key" :value="k.key">{{ k.page }}</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <label>Text</label>
              <tinymce-editor
                api-key="vuaywur9klvlt3excnrd9xki1a5lj25v18b2j0d0nu5tbwro"
                :init="tinyConfig"
                v-model="content.text"
              ></tinymce-editor>
            </div>
          <form-buttons :route="'contents'"></form-buttons>
        </form>
      </div>
    </main>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import FormButtons from "@/components/ui/buttons/FormButtons.vue";
import ImageUpload from "@/components/ui/ImageUpload.vue";
import tinyConfig from "@/config/tinyconfig.js";
import Editor from "@tinymce/tinymce-vue";
import Helpers from "@/mixins/helpers";
import Progress from "@/mixins/progress";

export default {
  components: {
    ImageUpload: ImageUpload,
    tinymceEditor: Editor,
    FormButtons: FormButtons
  },

  props: {
    type: String
  },

  mixins: [Helpers, Progress],

  data() {
    return {
      // fields to validate
      errors: {
        key: false,
        text: false
      },

      content: {
        key: null,
        text: null
      },

      keys: null,

      // tinymce config
      tinyConfig: tinyConfig
    };
  },

  created() {
    if (this.$props.type == "edit") {
      let uri = `/api/content/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.content = response.data;
      });
    }

    // Overwrite tinymce config
    this.tinyConfig.height = "360px";

    // get content keys from config
    let uri = `/api/content/get/keys`;
    this.axios.get(uri).then(response => {
      this.keys = response.data;
    });
  },

  methods: {

    // Validation methods
    validate() {
      if (this.content.key && this.content.text) {
        return true;
      }

      if (!this.content.key) {
        this.errors.key = true;
      }

      if (!this.content.text) {
        this.errors.text = true;
      }
      return false;
    },

    // Submit method
    submit() {
      if (!this.validate()) {
        this.validationError();
        return;
      }

      if (this.$props.type == "edit") {
        this.update();
      }

      if (this.$props.type == "create") {
        this.store();
      }
    },

    // Add the content
    store() {
      let uri = "/api/content/create";
      this.axios.post(uri, this.content).then(response => {
        this.$router.push({ name: "contents" });
      });
    },

    // Update the content
    update() {
      let uri = `/api/content/update/${this.$route.params.id}`;
      this.axios.post(uri, this.content).then(response => {
        this.$router.push({ name: "contents" });
      });
    },

    // Image Upload Callback
    afterImageUpload(file) {
      if (file.status == "error" && file.accepted == false) {
        this.$notify({ type: "error", text: "Ungültiges Dateiformat." });
      } else {
        let file_response = JSON.parse(file.xhr.response);
        this.content.media = file_response.name;
      }
    },

    // Delete a single file by name
    deleteImageUpload(file,event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/content/delete/file/${file}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          this.content.media = null;
          this.progress(el);
        });
      }
    },
  },

  computed: {
    title: function() {
      return this.$props.type == "edit" ? "Text bearbeiten" : "Text hinzufügen";
    }
  }
};
</script>