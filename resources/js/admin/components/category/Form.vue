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
                v-model="category.name"
              >
            </div>
          </div>
          <form-buttons :route="'categories'"></form-buttons>
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
      },

      // tabs
      tabs: {
        data: {
          active: true,
          error: false
        }
      },

      category: {
        name: null,
      },
    };
  },

  created() {
    if (this.$props.type == "edit") {
      let uri = `/api/category/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.category = response.data;
      });
    }
  },

  methods: {
    // Validation methods
    validate() {
      if (this.category.name) {
        return true;
      }

      if (!this.category.name) {
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
      let uri = "/api/category/create";
      this.axios.post(uri, this.category).then(response => {
        this.$router.push({ name: "categories" });
      });
    },

    // Update the client
    update() {
      let uri = `/api/category/update/${this.$route.params.id}`;
      this.axios.post(uri, this.category).then(response => {
        this.$router.push({ name: "categories" });
      });
    },
  },

  computed: {
    title: function() {
      return this.$props.type == "edit"
        ? "Kategorie bearbeiten"
        : "Kategorie hinzufügen";
    }
  }
};
</script>