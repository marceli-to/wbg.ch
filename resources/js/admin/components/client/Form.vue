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
            <div class="form-row" :class="errors.name ? 'has-error': ''">
              <label>Name *</label>
              <input
                type="text"
                @focus="removeError('name')"
                name="name"
                v-model="client.name"
              >
            </div>
            <div class="form-row">
              <label>Ort</label>
              <input 
                type="text" 
                name="name" 
                v-model="client.location"
              >
            </div>
            <div class="form-row">
              <label>Verlinktes Projekt</label>
              <div class="select-wrapper">
                <select
                  class="is-wide"
                  v-model="client.project_id"
                  name="project_id"
                >
                <option value="NULL">Bitte wählen...</option>
                <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }} – {{ p.category.name }}</option>
                </select>
              </div>
            </div>
          </div>
          <form-buttons :route="'clients'"></form-buttons>
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
    FormButtons: FormButtons,
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
        project_id: false
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

      client: {
        name: null,
        location: null,
        project_id: null,
      },

      projects: null,
    };
  },

  created() {
    if (this.$props.type == "edit") {
      let uri = `/api/client/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.client = response.data;
      });
    }
    
    this.axios.get("/api/projects/fetch/1/asc").then(response => {
      this.projects = response.data.data;
    });
  },

  methods: {
    // Validation methods
    validate() {
      if (this.client.name) {
        return true;
      }

      if (!this.client.name) {
        this.errors.name = true;
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
      let uri = "/api/client/create";
      this.axios.post(uri, this.client).then(response => {
        this.$router.push({ name: "clients" });
      });
    },

    // Update the client
    update() {
      let uri = `/api/client/update/${this.$route.params.id}`;
      this.axios.post(uri, this.client).then(response => {
        this.$router.push({ name: "clients" });
      });
    },
  },

  computed: {
    title: function() {
      return this.$props.type == "edit"
        ? "Kunde bearbeiten"
        : "Kunde hinzufügen";
    }
  }
};
</script>