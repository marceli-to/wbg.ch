<template>
  <div class="container">
    <notifications classes="notification"/>
    <main class="content" role="main">
      <div>
        <h1>{{title}}</h1>
        <nav class="tabs">
          <ul>
            <li>
              <a
                href="javascript:;"
                @click="changeTab('data')"
                :class="[tabs.data.active ? 'is-active' : '', tabs.data.error ? 'has-error' : '']"
              >Daten</a>
            </li>
            <li>
              <a
                href="javascript:;"
                @click="changeTab('media')"
                :class="tabs.media.active ? 'is-active' : ''"
              >Bilder</a>
            </li>
          </ul>
        </nav>
        <form @submit.prevent="submit">
          <div v-show="tabs.data.active">
            <div class="form-row" :class="errors.name ? 'has-error': ''">
              <label>Name *</label>
              <input
                type="text"
                @focus="removeError('name')"
                name="name"
                v-model="project.name"
              >
            </div>
            <div class="form-row" :class="errors.principal ? 'has-error': ''">
              <label>Auftraggeber</label>
              <input
                type="text"
                @focus="removeError('principal')"
                name="name"
                v-model="project.principal"
              >
            </div>
            <div class="form-row" :class="errors.description ? 'has-error': ''">
              <label>Beschreibung</label>
              <!-- <textarea
                @focus="removeError('description')"
                v-model="project.description"
                :class="errors.description ? 'has-error': ''"
                rows="15"
              ></textarea> -->
              <tinymce-editor
                api-key="vuaywur9klvlt3excnrd9xki1a5lj25v18b2j0d0nu5tbwro"
                :init="tinyConfig"
                v-model="project.description"
              ></tinymce-editor>
            </div>
            <div class="form-row" :class="errors.meta_description ? 'has-error': ''">
              <label>SEO-Text</label>
              <textarea
                @focus="removeError('meta_description')"
                v-model="project.meta_description"
                :class="errors.meta_description ? 'has-error': ''"
                rows="15"
              ></textarea>
            </div>
            <div class="grid-form">
              <div class="form-row" :class="errors.category_id ? 'has-error': ''">
                <label>Kategorie</label>
                <div class="select-wrapper is-wide">
                  <select
                    v-model="project.category_id"
                    name="category_id"
                    @focus="removeError('category_id')"
                  >
                    <option selected="selected">Bitte wählen...</option>
                    <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                  </select>
                </div>
              </div>
              <div class="form-row" :class="errors.client_id ? 'has-error': ''">
                <label>Kunde</label>
                <div class="select-wrapper is-wide">
                  <select
                    v-model="project.client_id"
                    name="category_id"
                    @focus="removeError('client_id')"
                  >
                    <option selected="selected">Bitte wählen...</option>
                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div v-show="tabs.media.active">
            <multi-image-upload
              :labelNew="'Bilder hochladen'"
              :labelExisting="'Vorhandene Bilder'"
              :labelRestrictions="'jpg, png | max. 8 MB'"
              :maxFiles="99"
              :maxFilesize="8"
              :assets="project.images"
              :assetType="'image'"
              :acceptedFiles="'.png,.jpg'"
              :uploadUrl="'/api/media/upload'"
            ></multi-image-upload>
          </div>
          <form-buttons :route="'projects'"></form-buttons>
        </form>
      </div>
    </main>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import MultiImageUpload from "@/components/ui/MultiImageUpload.vue";
import FormButtons from "@/components/ui/buttons/FormButtons.vue";
import tinyConfig from "@/config/tinyconfig.js";
import Editor from "@tinymce/tinymce-vue";
import Helpers from "@/mixins/helpers";

export default {
  components: {
    FormButtons: FormButtons,
    MultiImageUpload: MultiImageUpload,
    tinymceEditor: Editor,
  },

  props: {
    type: String
  },

  mixins: [Helpers],

  data() {
    return {
      // fields to validate
      errors: {
        name: false,
        category_id: false,
        client_id: false,
      },

      // tabs
      tabs: {
        data: {
          active: true,
          error: false
        },
        media: {
          active: false,
          error: false
        }
      },

      project: {
        name: null,
        principal: null,
        description: null,
        meta_description: null,
        category_id: null,
        client_id: null,
        publish: null,
        images: []
      },

      categories: [],
      clients: [],

      // tinymce config
      tinyConfig: tinyConfig

    };
  },

  created() {

    // Get record while in edit mode
    if (this.$props.type == "edit") {
      let uri = `/api/project/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.project = response.data;
      });
    }

    // Get categories for dropdown
    let categoryUri = `/api/categories/get`;
    this.axios.get(categoryUri).then(response => {
      this.categories = response.data.data;
    });

    // Get clients for dropdown
    let clientsUri = `/api/clients/get`;
    this.axios.get(clientsUri).then(response => {
      this.clients = response.data.data;
    });
  },

  methods: {
    // Validation methods
    validate() {
      if (this.project.name && this.project.category_id && this.project.client_id) {
        return true;
      }

      if (!this.project.name) {
        this.errors.name = true;
        this.tabs.data.error = true;
      }

      if (!this.project.category_id) {
        this.errors.category_id = true;
        this.tabs.data.error = true;
      }
    
      if (!this.project.client_id) {
        this.errors.client_id = true;
        this.tabs.data.error = true;
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

    // Store the data
    store() {
      let uri = "/api/project/create";
      this.axios.post(uri, this.project).then(response => {
        this.$router.push({ name: "projects" });
      });
    },

    // Update the data
    update() {
      let uri = `/api/project/update/${this.$route.params.id}`;
      this.axios.post(uri, this.project).then(response => {
        this.$router.push({ name: "projects" });
      });
    },

    // Upload & asset methods
    afterUpload(file) {
      if (file.status == "error" && file.accepted == false) {
        this.$notify({type: "error", text: "Ungültiges Dateiformat."});
      } 
      else {
        let file_response = JSON.parse(file.xhr.response);
        file_response.id = null;
        file_response.caption = null;
        file_response.order = -1;
        file_response.publish = 1;
        this.project.images.push(file_response);
      }
    },

    // Delete a single file by its name
    deleteUpload(file) {
      if(confirm('Bitte löschen bestätigen!')) {
        let uri = `/api/project/image/delete/${file}`;
        this.axios.delete(uri).then(response => {
          this.project.images.splice(this.project.images.indexOf(file), 1);
        });
      }
    },

    toggleAsset(asset) {
      if (asset.id === null) {
          const index = this.project.images.findIndex(x => x.name === asset.name);
          this.project.images[index].publish = asset.publish == 1 ? 0 : 1;
      }
      else {
        let uri = `/api/project/image/status/${asset.id}`;
        this.axios.get(uri).then(response => {
          const index = this.project.images.findIndex(x => x.id === asset.id);
          this.project.images[index].publish = response.data;
        });
      }
    },
  },

  computed: {
    title: function() {
      return this.$props.type == "edit"
        ? "Projekt bearbeiten"
        : "Projekt hinzufügen";
    }
  }
};
</script>