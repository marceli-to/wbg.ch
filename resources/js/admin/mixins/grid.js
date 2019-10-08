import store from "@/store";
import GridMediaSelector from '@/components/home/MediaSelector.vue';
import GridArticleSelector from '@/components/home/ArticleSelector.vue';

export default {

  components: {
    GridMediaSelector: GridMediaSelector,
    GridArticleSelector: GridArticleSelector,
  },

  data() {
    return {
      // grid data
      elements: [],
      projects: [],
      news: [],

      // overlay
      hasOverlay: false,
      showMedia: false,
      showNews: false,

      // temp. data
      tmpGridId: 0,
      tmpPosition: 0,

      selector: '',
    }
  },

  methods: {

    createArticle(gridId, position) {
      this.axios.get('/api/news/get').then(response => {
        this.news = response.data.data;
        this.toggleOverlay();
        this.showNews = true;
        this.selector = 'GridArticleSelector';
        this.tmpGridId = gridId;
        this.tmpPosition = position;
      });
    },

    createMedia(gridId, position) {
      this.axios.get('/api/projects/fetch/1/asc').then(response => {
        this.projects = response.data.data;
        this.toggleOverlay();
        this.showMedia = true;
        this.selector = 'GridMediaSelector';
        this.tmpGridId = gridId;
        this.tmpPosition = position;
      });
    },

    storeArticle(newsId) {
      let uri = '/api/home/grid/element/store';
      let data = {
        'grid_id': this.tmpGridId,
        'position': this.tmpPosition,
        'news_id': newsId
      };
      this.axios.post(uri, data).then((response) => {
        this.toggleOverlay();
        this.$notify({type: 'success', text: 'Element hinzugefügt!' });
        this.fetchElements();
        store.commit('gridChanged');
      });
    },

    storeMedia(imageId) {
      let uri = '/api/home/grid/element/store';
      let data = {
        'grid_id': this.tmpGridId,
        'position': this.tmpPosition,
        'project_image_id': imageId
      };

      this.axios.post(uri, data).then((response) => {
        this.toggleOverlay();
        this.$notify({type: 'success', text: 'Bild hinzugefügt!'});
        this.fetchElements();
        store.commit('gridChanged');
      });
    },

    deleteArticle(gridElementId) {
      let uri = `/api/home/grid/element/delete/${gridElementId}`;
      this.axios.delete(uri).then(response => {
        this.$notify({type: 'success', text: 'Element gelöscht!'});
        this.fetchElements();
        store.commit('gridChanged');
      });
    },

    deleteMedia(gridElementId) {
      let uri = `/api/home/grid/element/delete/${gridElementId}`, self = this;
      this.axios.delete(uri).then(response => {
        this.$notify({type: 'success', text: 'Bild gelöscht!'});
        this.fetchElements();
        store.commit('gridChanged');
      });
    },

    // Helper methods
    getPreviewImage(file) {
      return `/media/${file}/sm`;
    },

    toggleOverlay() {

      // toggle class on html to prevent double scrollbars
      let html = document.querySelector('html');
      html.classList.toggle('has-overlay');

      // toggle the overlay itself
      this.hasOverlay = this.hasOverlay ? false : true;

      // reset news/projects
      if (!this.hasOverlay) {
        this.showNews = false;
        this.showMedia = false;
      }
    },
  }
};