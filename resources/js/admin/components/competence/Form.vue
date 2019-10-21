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
            <div class="form-row" :class="errors.title ? 'has-error': ''">
              <label>Titel *</label>
              <input
                type="text"
                @focus="removeError('title')"
                name="name"
                v-model="competence.title"
              >
            </div>
            <div class="form-row" :class="errors.description ? 'has-error': ''">
              <label>Beschreibung</label>
              <textarea
                @focus="removeError('description')"
                v-model="competence.description"
                :class="errors.description ? 'has-error': ''"
                rows="15"
              ></textarea>
            </div>
            <div class="form-row" :class="errors.category_id ? 'has-error': ''">
              <label>Kategorie (Verweis)</label>
              <div class="select-wrapper">
                <select
                  v-model="competence.category_id"
                  name="category_id"
                  @focus="removeError('category_id')"
                >
                  <option value="NULL" selected="selected">Bitte wählen...</option>
                  <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
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
              :assets="competence.media"
              :assetType="'image'"
              :acceptedFiles="'.png,.jpg'"
              :uploadUrl="'/api/media/upload'"
            ></multi-image-upload>
          </div>
          <form-buttons :route="'competences'"></form-buttons>
        </form>
      </div>
    </main>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import MultiImageUpload from "@/components/ui/MultiImageUpload.vue";
import FormButtons from "@/components/ui/buttons/FormButtons.vue";
import Helpers from "@/mixins/helpers";
import Progress from "@/mixins/progress";

export default {
  components: {
    FormButtons: FormButtons,
    MultiImageUpload: MultiImageUpload
  },

  props: {
    type: String
  },

  mixins: [Helpers, Progress],

  data() {
    return {
      // fields to validate
      errors: {
        title: false
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

      competence: {
        title: null,
        description: null,
        category_id: null,
        media: []
      },

      categories: []
    };
  },

  created() {

    // Get record while in edit mode
    if (this.$props.type == "edit") {
      let uri = `/api/competence/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.competence = response.data;
      });
    }

    // Get categories for dropdown
    let uri = `/api/categories/get`;
    this.axios.get(uri).then(response => {
      this.categories = response.data.data;
    });
  },

  methods: {
    // Validation methods
    validate() {
      if (this.competence.title) {
        return true;
      }

      if (!this.competence.title) {
        this.errors.title = true;
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
      let uri = "/api/competence/create";
      this.axios.post(uri, this.competence).then(response => {
        this.$router.push({ name: "competences" });
      });
    },

    // Update the data
    update() {
      let uri = `/api/competence/update/${this.$route.params.id}`;
      this.axios.post(uri, this.competence).then(response => {
        this.$router.push({ name: "competences" });
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
        this.competence.media.push(file_response);
      }
    },

    // Delete a single file by its name
    deleteUpload(file,event) {
      if(confirm('Bitte löschen bestätigen!')) {
        let uri = `/api/competence/media/delete/${file}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          this.competence.media.splice(this.competence.media.indexOf(file), 1);
          this.progress(el);
        });
      }
    },

    toggleAsset(asset,event) {
      if (asset.id === null) {
          const index = this.competence.media.findIndex(x => x.name === asset.name);
          this.competence.media[index].publish = asset.publish == 1 ? 0 : 1;
      }
      else {
        let uri = `/api/competence/media/status/${asset.id}`;
        let el = this.progress(event.target);
        this.axios.get(uri).then(response => {
          const index = this.competence.media.findIndex(x => x.id === asset.id);
          this.competence.media[index].publish = response.data;
          this.progress(el);
        });
      }
    },
  },

  computed: {
    title: function() {
      return this.$props.type == "edit"
        ? "Kompetenz bearbeiten"
        : "Kompetenz hinzufügen";
    }
  }
};
</script>