<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <h1>Projekte</h1>
          <router-link :to="{ name: 'project-create' }" class="btn-add">
            <span>Hinzufügen</span>
          </router-link>
          <div class="list-items" v-if="projects.length">
            <div v-for="(categories,index) in grouped" :key="index">
              <div class="list-items">
                <div class="list-item-group">
                  <div v-for="(project, idx) in categories" :key="`${idx}-${project.id}`">
                    <h2 v-if="idx == 0">{{ project.category.name }}</h2>
                  </div>
                  <draggable
                    v-model="grouped[index]"
                    @end="updateOrder(index)"
                    ghost-class="draggable-ghost"
                    tag="div"
                  >
                    <div v-for="project in categories" :key="project.id">
                      <div
                        :class="[project.publish == 0 ? 'is-disabled' : '', 'list-item', 'list-item--project is-sortable']"
                        data-icons="5"
                      >
                        <div class="list-item-body">
                          <h3>{{ project.name }}</h3>
                          <span>Auftraggeber: {{project.principal}}</span>
                          <span>Kunde: {{project.client.name}}</span>
                        </div>
                        <div class="list-item-action" data-icons="5">
                          <router-link
                            :to="{name: 'project-grids', params: { id: project.id }}"
                            :class="[project.images.length > 0 ? '' : 'is-disabled', 'icon-grid icon-mini']"
                            title="Layout"
                          ></router-link>
                          <a
                            href="javascript:;"
                            :class="[project.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                            @click.prevent="toggleStatus(project.id,$event)"
                          ></a>
                          <router-link
                            :to="{name: 'project-edit', params: { id: project.id }}"
                            class="icon-edit icon-mini"
                          ></router-link>
                          <a
                            href="javascript:;"
                            class="icon-copy icon-mini"
                            @click.prevent="clone(project.id,$event)"
                          ></a>
                          <a
                            href="javascript:;"
                            class="icon-trash icon-mini"
                            @click.prevent="destroy(project.id,$event)"
                          ></a>
                        </div>
                      </div>
                    </div>
                  </draggable>
                </div>
              </div>
            </div>
          </div>
          <div v-else>
            <p>Es sind keine Projekte vorhanden...</p>
          </div>
          <footer class="form-footer">
            <div>
              <div class="search-wrapper">
                <a
                  href="javascript:;"
                  class="icon-delete"
                  v-if="search"
                  @click.prevent="clearSearch()"
                ></a>
                <input
                  type="text"
                  class="search"
                  v-model="search"
                  placeholder="Filter nach Projektname, Auftraggeber, Kunde oder Kategorie"
                >
              </div>
            </div>
          </footer>
        </div>
      </main>
    </div>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import draggable from "vuedraggable";
import Progress from "@/mixins/progress";

export default {
  components: {
    PageHeader: PageHeader,
    draggable
  },

  mixins: [Progress],

  data() {
    return {
      projects: [],
      groupedProjects: [],
      filteredProjects: [],
      search: "",
      debounce: false
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      let uri = "/api/projects/get";
      this.axios.get(uri).then(response => {
        this.projects = response.data.data;
      });
    },

    destroy(id,event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/project/destroy/${id}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          this.fetch();
          this.$notify({ type: "success", text: "Eintrag gelöscht" });
          this.progress(el);
        });
      }
    },

    clone(id,event) {
      let uri = `/api/project/clone/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        this.fetch();
        this.$notify({ type: "success", text: "Eintrag kopiert" });
        this.progress(el);
      });
    },

    toggleStatus(id,event) {
      let uri = `/api/project/status/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        const index = this.projects.findIndex(x => x.id === id);
        this.projects[index].publish = response.data;
        this.$notify({ type: "success", text: "Status angepasst" });
        this.progress(el);
      });
    },

    updateOrder(groupIndex) {
      let projects = this.groupedProjects[groupIndex].map(function(
        project,
        index
      ) {
        project.order = index;
        return project;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(
        function(projects) {
          this.debounce = false;
          let uri = `/api/project/order`;
          this.axios.post(uri, { projects: projects }).then(response => {
            this.fetch();
          });
        }.bind(this, projects),
        500
      );
      this.$notify({ type: "success", text: "Reihenfolge angepasst" });
    },

    clearSearch() {
      this.search = "";
    }
  },

  computed: {
    grouped() {
      let filteredProjects = this.projects;
      let filter = c =>
        c.name.toLowerCase().includes(this.search.toLowerCase()) ||
        c.principal.toLowerCase().includes(this.search.toLowerCase()) ||
        c.client.name.toLowerCase().includes(this.search.toLowerCase()) ||
        c.category.name.toLowerCase().includes(this.search.toLowerCase());

      if (this.search) {
        filteredProjects = this.projects.filter(filter);
      }

      this.groupedProjects = _.groupBy(filteredProjects, "category_id");

      return this.groupedProjects;
    }
  }
};
</script>