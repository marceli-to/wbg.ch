<template>
  <div class="container">
    <notifications classes="notification"/>
    <main class="content" role="main">
      <div>
        <h1>{{title}}</h1>
        <form @submit.prevent="submit">
          <div class="form-row" :class="errors.title ? 'has-error': ''">
            <label>Titel *</label>
            <input type="text" @focus="removeError('title')" v-model="job.title">
          </div>
          <div class="form-row">
            <label>Text</label>
            <tinymce-editor
              api-key="vuaywur9klvlt3excnrd9xki1a5lj25v18b2j0d0nu5tbwro"
              :init="tinyConfig"
              v-model="job.text"
            ></tinymce-editor>
          </div>
          <form-buttons :route="'jobs'"></form-buttons>
        </form>
      </div>
    </main>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import FormButtons from "@/components/ui/buttons/FormButtons.vue";
import tinyConfig from "@/config/tinyconfig.js";
import Editor from "@tinymce/tinymce-vue";
import Helpers from "@/mixins/helpers";
import Progress from "@/mixins/progress";

export default {
  components: {
    FormButtons: FormButtons,
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
        title: false
      },

      // model
      job: {
        title: null,
        text: null,
      },

      // tinymce config
      tinyConfig: tinyConfig

    };
  },

  created() {
    if (this.$props.type == "edit") {
      let uri = `/api/job/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.job = response.data;
      });
    }
  },

  methods: {
    // Validation methods
    validate() {
      if (this.job.title) {
        return true;
      }

      if (!this.job.title) {
        this.errors.title = true;
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

    // Add the job
    store() {
      let uri = "/api/job/create";
      this.axios.post(uri, this.job).then(response => {
        this.$router.push({ name: "jobs" });
      });
    },

    // Update the job
    update() {
      let uri = `/api/job/update/${this.$route.params.id}`;
      this.axios.post(uri, this.job).then(response => {
        this.$router.push({ name: "jobs" });
      });
    },
  },

  computed: {
    title: function() {
      return this.$props.type == "edit" 
      ? "Job bearbeiten" 
      : "Job hinzufügen";
    }
  }
};
</script>