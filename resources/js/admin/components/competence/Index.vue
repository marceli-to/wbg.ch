<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <h1>Kompetenzen</h1>
          <router-link :to="{ name: 'competence-create' }" class="btn-add">
            <span>Hinzufügen</span>
          </router-link>
          <div class="list-items" v-if="competences.length">
            <draggable
              v-model="competences"
              @end="updateOrder"
              ghost-class="draggable-ghost"
              tag="div"
            >
              <div
                :class="[competence.publish == 0 ? 'is-disabled' : '', 'list-item', 'list-item--sortable']"
                v-for="competence in competences"
                :key="competence.id"
              >
                <div class="list-item-body">
                  <h3>{{ competence.title }}</h3>
                  <span class="bubble is-info" v-if="competence.category">Verweis: <strong>{{competence.category.name}}</strong></span>
                </div>
                <div class="list-item-action">
                  <a
                    href="javascript:;"
                    :class="[competence.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                    @click.prevent="toggleStatus(competence.id,$event)"
                  ></a>
                  <router-link
                    :to="{name: 'competence-edit', params: { id: competence.id }}"
                    class="icon-edit icon-mini"
                  ></router-link>
                  <a
                    href="javascript:;"
                    class="icon-copy icon-mini"
                    @click.prevent="clone(competence.id,$event)"
                  ></a>
                  <a
                    href="javascript:;"
                    class="icon-trash icon-mini"
                    @click.prevent="destroy(competence.id,$event)"
                  ></a>
                </div>
              </div>
            </draggable>
          </div>
          <div v-else>
            <p>Es sind keine Kompetenzen vorhanden...</p>
          </div>
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
    draggable,
    PageHeader: PageHeader
  },

  mixins: [Progress],
  
  data() {
    return {
      competences: [],
      debounce: false
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      let uri = "/api/competences/get";
      this.axios.get(uri).then(response => {
        this.competences = response.data.data;
      });
    },

    destroy(id,event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/competence/destroy/${id}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          this.fetch();
          this.$notify({ type: "success", text: "Eintrag gelöscht" });
          this.progress(el);
        });
      }
    },

    clone(id) {
      let uri = `/api/competence/clone/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        this.competences.push(response.data);
        this.$notify({ type: "success", text: "Eintrag kopiert" });
        this.progress(el);
      });
    },

    toggleStatus(id) {
      let uri = `/api/competence/status/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        const index = this.competences.findIndex(x => x.id === id);
        this.competences[index].publish = response.data;
        this.$notify({ type: "success", text: "Status angepasst" });
        this.progress(el);
      });
    },

    updateOrder() {
      let competences = this.competences.map(function(competence, index) {
          competence.order = index;
          return competence;
      });

      if (this.debounce) return;

      this.debounce = setTimeout(function(competences) {
        this.debounce = false 
        let uri = `/api/competence/order`;
        this.axios.post(uri, {competences: competences}).then((response) => {
          this.$router.push({name: 'competences'});
        });
      }.bind(this, competences), 1000);
      this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
    }
  }
};
</script>