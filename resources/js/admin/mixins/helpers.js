export default {

  methods: {

    // Show asset edit form
    showAssetEdit(e) {
      let editForm = e.target.parentNode.nextElementSibling;
      editForm.classList.toggle('is-visible');
    },

    // Hide asset edit form
    hideAssetEdit(e) {
      let editForm = e.target.parentNode;
      editForm.classList.toggle('is-visible');
    },

    // Change tabs
    changeTab(tab) {
      // set all tabs inactive and remove errors if any
      for (let prop in this.tabs) {
        this.tabs[prop].active = false;
        this.tabs[prop].error = false;
      }

      // set active tab
      this.tabs[tab].active = true;
    },

    // Show the validation errors
    validationError() {
      this.$notify({
        type: 'error',
        text: 'Bitte markierte Felder prüfen!'
      });
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    },

    // Remove error classes
    removeError(field, language) {
      if (language) {
        this.errors[field][language] = false;
      } 
      else {
        this.errors[field] = false;
      }
    },
  }
};
