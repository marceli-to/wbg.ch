<template>
  <div>
    <h1>Projektbild auswählen</h1>
    <div class="project-selector">
      <div v-for="project in filtered" :key="project.id">
        <div class="project-selector__item is-multi" v-if="project.images.length > 0">
          <h2>{{ project.name }}</h2>
          <div class="project-selector__media">
            <figure v-for="image in project.images" :key="image.id">
              <a
                v-if="image.is_crop"
                @click.prevent="destroyImage($event,image.name)"
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
</template>
<script>
export default {
  
  data() {
    return {
      search: '',
    };
  },

  props: {
    projects: Array
  },

  methods: {
    storeMedia(imageId) {
      this.$parent.storeMedia(imageId);
    },

    destroyImage(event,image) {
      event.stopPropagation();
      if (confirm('Bitte löschen bestätigen!')) {
        let uri = `/api/project/image/delete/${image}`,
            elem = event.target;
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