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
              >Medien</a>
            </li>
          </ul>
        </nav>
        <form @submit.prevent="submit">
          <div v-show="tabs.data.active">
            <div class="grid-team">
              <div class="span form-row" :class="errors.firstname ? 'has-error': ''">
                <label>Vorname *</label>
                <input
                  type="text"
                  @focus="removeError('firstname')"
                  name="firstname"
                  v-model="team.firstname"
                >
              </div>
              <div class="span form-row" :class="errors.name ? 'has-error': ''">
                <label>Name *</label>
                <input type="text" @focus="removeError('name')" name="name" v-model="team.name">
              </div>
              <div class="span form-row">
                <label>Position</label>
                <input
                  type="text"
                  name="role"
                  v-model="team.role"
                  placeholder="z.B. Partner"
                >
              </div>
              <div class="span form-row">
                <label>Telefon</label>
                <input
                  type="text"
                  @focus="removeError('phone')"
                  name="phone"
                  v-model="team.phone"
                  placeholder="Format: +41 52 2xx xx xx"
                >
              </div>
              <div class="span form-row" :class="errors.email ? 'has-error': ''">
                <label>E-Mail *</label>
                <input type="text" @focus="removeError('email')" name="email" v-model="team.email">
              </div>
            </div>
          </div>
          <div v-show="tabs.media.active">
            <image-upload
              :labelNew="'Bild hochladen'"
              :labelExisting="'Vorhandenes Bild'"
              :labelRestrictions="'jpg, png | max. 8 MB'"
              :maxFiles="1"
              :maxFilesize="8"
              :asset="team.media"
              :assetType="'image'"
              :acceptedFiles="'.png,.jpg'"
              :uploadUrl="'/api/media/upload'"
            ></image-upload>
          </div>
          <form-buttons :route="'team'"></form-buttons>
        </form>
      </div>
    </main>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import FormButtons from "@/components/ui/buttons/FormButtons.vue";
import ImageUpload from "@/components/ui/ImageUpload.vue";
import Helpers from "@/mixins/helpers";

export default {
  components: {
    FormButtons: FormButtons,
    ImageUpload: ImageUpload
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
        firstname: false,
        email: false
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

      team: {
        firstname: null,
        name: null,
        role: null,
        phone: null,
        email: null,
        media: null
      },
    };
  },

  created() {
    if (this.$props.type == "edit") {
      let uri = `/api/team/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.team = response.data;
      });
    }
  },

  methods: {
    // Validation methods
    validate() {
      if (this.team.name && this.team.firstname && this.team.email) {
        return true;
      }

      if (!this.team.name) {
        this.errors.name = true;
        this.tabs.data.error = true;
      }

      if (!this.team.firstname) {
        this.errors.firstname = true;
        this.tabs.data.error = true;
      }

      if (!this.team.email) {
        this.errors.email = true;
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

    // Add the team
    store() {
      let uri = "/api/team/create";
      this.axios.post(uri, this.team).then(response => {
        this.$router.push({ name: "team" });
      });
    },

    // Update the team
    update() {
      let uri = `/api/team/update/${this.$route.params.id}`;
      this.axios.post(uri, this.team).then(response => {
        this.$router.push({ name: "team" });
      });
    },

    // FileUpload Callback
    afterUpload(file) {
      if (file.status == "error" && file.accepted == false) {
        console.log(file);
        this.$notify({ type: "error", text: "Ungültiges Dateiformat." });
      } else {
        let file_response = JSON.parse(file.xhr.response);
        this.team.media = file_response.name;
      }
    },

    // Delete a single file by its name
    deleteUpload(file) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/team/delete/file/${file}`;
        this.axios.delete(uri).then(response => {
          this.team.media = null;
        });
      }
    }
  },

  computed: {
    title: function() {
      return this.$props.type == "edit"
        ? "Teammitglied bearbeiten"
        : "Teammitglied hinzufügen";
    }
  }
};
</script>