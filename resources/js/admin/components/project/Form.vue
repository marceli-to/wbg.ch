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
            <li v-if="this.$props.type == 'edit'">
              <a
                href="javascript:;"
                @click="changeTab('relations')"
                :class="tabs.relations.active ? 'is-active' : ''"
              >Relationen</a>
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
            <div class="form-row" v-show="project.category_id == 3">
              <label>Subkategorie (Panoptikum)</label>
              <div class="select-wrapper is-wide">
                <select
                  v-model="project.subcategory_id"
                  name="subcategory_id"
                >
                  <option value="NULL" selected="selected">Bitte wählen...</option>
                  <option v-for="(item, key) in subcategories" :key="key" :value="key">{{ item }}</option>
                </select>
              </div>
            </div>
          </div>
          <div v-show="tabs.media.active">
            <div style="position:relative">
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
                :hasCroppedPreview='true'
                :hasStar='true'
                :hasUrl='true'
              ></multi-image-upload>
            </div>
          </div>
          <div v-show="tabs.relations.active">
            <div class="project-relations">
              <label>Relation hinzufügen</label>
              <div class="project-relations__grid">
                <div class="select-wrapper">
                  <select v-model="relation" name="related_project_id">
                    <option selected="selected">Bitte wählen...</option>
                    <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }}</option>
                  </select>
                </div>
                <div>
                  <a href="" @click.prevent="addRelation()" class="btn-primary">Hinzufügen</a>
                </div>
              </div>
            </div>
            <div class="list-items" v-if="project.relations.length">
              <label>Vorhandene Relationen</label>
              <div
                :class="[relation.publish == 0 ? 'is-disabled' : '', 'list-item']"
                v-for="relation in project.relations"
                :key="relation.id">
                  <div class="list-item-body">
                    {{relation.related.name}}
                  </div>
                  <div class="list-item-action" data-icons="1">
                    <a href="javascript:;" @click.prevent="destroyRelation(relation.id, $event)" class="icon-trash icon-mini"></a>
                  </div>
              </div>
            </div>
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
import Progress from "@/mixins/progress";

export default {
  components: {
    FormButtons: FormButtons,
    MultiImageUpload: MultiImageUpload,
    tinymceEditor: Editor,
  },
  
  props: {
    type: String
  },

  mixins: [Helpers, Progress],

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
        },
        relations: {
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
        subcategory_id: null,
        client_id: null,
        publish: null,
        images: [],
        relations: []
      },

      categories: [],
      clients: [],
      projects: [],
      subcategories: [],

      relation: null,

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
        // show only original images
        this.project.images = this.project.original_images;
      });
    }

    // Get categories for dropdown
    let categoryUri = `/api/categories/get`;
    this.axios.get(categoryUri).then(response => {
      this.categories = response.data.data;
    });

    // Get subcategories for dropdown
    let subCategoryUri = `/api/subcategories/get`;
    this.axios.get(subCategoryUri).then(response => {
      this.subcategories = response.data;
    });

    // Get clients for dropdown
    let clientsUri = `/api/clients/get`;
    this.axios.get(clientsUri).then(response => {
      this.clients = response.data.data;
    });

    // Get projects for dropdown
    let projectsUri = `/api/projects/fetch/1`
    this.axios.get(projectsUri).then(response => {
      this.projects = response.data.data;
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
        this.$notify({type: "error", text: "Dateiformat ungültig oder Datei zu gross"});
      }
      else {
        let file_response = JSON.parse(file.xhr.response);
        file_response.id = null;
        file_response.caption = null;
        file_response.order = -1;
        file_response.publish = 1;
        file_response.is_preview = 0;
        file_response.url = null;
        this.project.images.push(file_response);
      }
    },

    // Delete a single file by its name
    deleteUpload(file,event) {
      if(confirm('Bitte löschen bestätigen!')) {
        let uri = `/api/project/image/delete/${file}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          const index = this.project.images.findIndex(x => x.name === file);
          this.project.images.splice(index, 1);
          this.progress(el);
        });
      }
    },

    toggleAsset(asset,event) {
      if (asset.id === null) {
        const index = this.project.images.findIndex(x => x.name === asset.name);
        this.project.images[index].publish = asset.publish == 1 ? 0 : 1;
      }
      else {
        let uri = `/api/project/image/status/${asset.id}`;
        let el = this.progress(event.target);
        this.axios.get(uri).then(response => {
          const index = this.project.images.findIndex(x => x.id === asset.id);
          this.project.images[index].publish = response.data;
          this.progress(el);
        });
      }
    },

    togglePreview(asset,event) {
      if (asset.id == null) {
        const index = this.project.images.findIndex(x => x.name === asset.name);
        this.project.images[index].is_preview = asset.is_preview == 1 ? 0 : 1;
      }
      else {
        let uri = `/api/project/image/preview/${asset.id}`;
        let el = this.progress(event.target);
        this.axios.get(uri).then(response => {
          const index = this.project.images.findIndex(x => x.id === asset.id);
          this.project.images[index].is_preview = response.data;
          this.progress(el);
        });
      }
    },

    addRelation() {
      let uri = "/api/project/relation/create";
      let data = {
        project_id: this.project.id,
        related_project_id: this.relation
      }
      this.axios.post(uri, data).then(response => {
        this.relation = null;
        this.getRelations(this.project.id);
      });
    },

    destroyRelation(id,event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/project/relation/destroy/${id}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          this.$notify({ type: "success", text: "Eintrag gelöscht" });
          this.progress(el);
          this.getRelations(this.project.id);
        });
      }
    },

    getRelations(id) {
      let uri = `/api/project/relations/get/${id}`
      this.axios.get(uri).then(response => {
        console.log(response.data.data);
        this.project.relations = response.data.data;
      });
    }
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