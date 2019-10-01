<template>
  <div class="container">
    <notifications classes="notification"/>
    <main class="content" role="main">
      <div>
        <h1>{{title}}</h1>
        <form @submit.prevent="submit">
          <div class="form-row" :class="errors.title ? 'has-error': ''">
            <label>Titel *</label>
            <input type="text" @focus="removeError('title')" v-model="news.title">
          </div>
          <div class="form-row">
            <label>Text</label>
            <textarea v-model="news.text" rows="5"></textarea>
          </div>
          <div class="form-row">
            <label>
              Link
              <a
                :href="previewLink"
                target="_blank"
                class="icon-external-link is-sm icon-mini"
                v-if="previewLink"
              ></a>
            </label>
            <input
              type="text"
              name="url"
              v-model="news.link"
              placeholder="https://test.ch/"
              @blur="fixUri()"
            >
          </div>
          <div class="form-row">
            <label>Link Text</label>
            <input type="text" v-model="news.linkText">
          </div>
          <form-buttons :route="'news'"></form-buttons>
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
import Progress from "@/mixins/progress";

export default {
  components: {
    ImageUpload: ImageUpload,
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
        title: false
      },

      // model
      news: {
        title: null,
        text: null,
        link: null,
        linkText: null,
      },

      previewLink: null,

    };
  },

  created() {
    if (this.$props.type == "edit") {
      let uri = `/api/news/edit/${this.$route.params.id}`;
      this.axios.get(uri).then(response => {
        this.news = response.data;
        if (this.news.link) {
          this.fixUri(this.news.link);
        }
      });
    }
  },

  methods: {
    // Validation methods
    validate() {
      if (this.news.title) {
        return true;
      }

      if (!this.news.title) {
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

    // Add the news
    store() {
      let uri = "/api/news/create";
      this.axios.post(uri, this.news).then(response => {
        this.$router.push({ name: "news" });
      });
    },

    // Update the news
    update() {
      let uri = `/api/news/update/${this.$route.params.id}`;
      this.axios.post(uri, this.news).then(response => {
        this.$router.push({ name: "news" });
      });
    },

    fixUri() {
      // check for '@'
      let index = 0,
        pattern = /^((http|https|ftp):\/\/)/;

      if (this.news.link.length < 1) {
        this.news.link = null;
        this.previewLink = null;
        return;
      }

      if ((index = this.news.link.indexOf("@")) !== -1) {
        this.previewLink = "mailto:" + this.news.link;
      } else {
        if (!pattern.test(this.news.link)) {
          this.previewLink = "http://" + this.news.link;
          this.news.link = "http://" + this.news.link;
        } else {
          this.previewLink = this.news.link;
          this.news.link = this.news.link;
        }
      }
    }
  },

  computed: {
    title: function() {
      return this.$props.type == "edit" 
      ? "News bearbeiten" 
      : "News hinzufügen";
    }
  }
};
</script>