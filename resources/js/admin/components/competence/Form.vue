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
                  <option selected="selected">Bitte wählen...</option>
                  <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
              </div>
            </div>
          </div>
          <form-buttons :route="'competences'"></form-buttons>
        </form>
      </div>
    </main>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import FormButtons from "@/components/ui/buttons/FormButtons.vue";
import Helpers from "@/mixins/helpers";

export default {
  components: {
    FormButtons: FormButtons
  },

  props: {
    type: String
  },

  mixins: [Helpers],

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
        }
      },

      competence: {
        title: null,
        description: null,
        category_id: null
      },

      categories: []
    };
  },

  created() {
    if (this.$props.type == "edit") {
      let uri = `/api/competence/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.competence = response.data;
      });
    }

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

    // Add the client
    store() {
      let uri = "/api/competence/create";
      this.axios.post(uri, this.competence).then(response => {
        this.$router.push({ name: "competences" });
      });
    },

    // Update the client
    update() {
      let uri = `/api/competence/update/${this.$route.params.id}`;
      this.axios.post(uri, this.competence).then(response => {
        this.$router.push({ name: "competences" });
      });
    }
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