<template>
  <div>
    <div class="project-selector">
      <div class="select-wrapper">
        <select
          v-model="selectedProject"
          name="project_id"
        >
          <option selected="selected">Projekt wählen...</option>
          <option v-for="p in filtered" :key="p.id" :value="p.id">{{ p.name }}</option>
        </select>
      </div>
      <div v-for="project in filtered" :key="project.id">
        <div v-if="selectedProject == project.id">
          <div class="project-selector__item is-multi" v-if="project.images.length > 0">
            <div class="project-selector__media">
              <figure v-for="image in project.images" :key="image.id">
                <a
                  v-if="image.is_crop"
                  @click.prevent="deleteCroppedImage($event,image.name)"
                  class="btn-trash-mini"
                ></a>
                <a href @click.prevent="storeMedia(image.id)">
                  <img :src="getImageSource(image.name)" height="50" width="50">
                </a>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  
  data() {
    return {
      search: '',
      selectedProject: null
    };
  },

  props: {
    projects: Array
  },

  methods: {
    storeMedia(imageId) {
      this.$parent.storeMedia(imageId);
    },

    deleteCroppedImage(event,image) {
      event.stopPropagation();
      if (confirm('Bitte löschen bestätigen!')) {
        let uri = `/api/project/image/delete/cropped/${image}`, elem = event.target;
        this.axios.delete(uri).then(response => {
            elem.parentNode.remove();
        });
      }
    },

    getImageSource(file) {
      return `/media/${file}/xs`;
    },
  },
  
  computed: {
    filtered() {
      let projects = this.$props.projects;
      if (projects) {
        return projects.filter(project => {
          let images = project.images;
          if (images.length > 0) {
            return project;
          }
        })
      }
    }
  }
};
</script>