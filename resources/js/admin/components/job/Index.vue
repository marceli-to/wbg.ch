<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <h1>Job</h1>
          <router-link :to="{ name: 'job-create' }" class="btn-add">
            <span>Hinzufügen</span>
          </router-link>
          <div class="list-items" v-if="job.length">
            <draggable
              v-model="job"
              @end="updateOrder"
              ghost-class="draggable-ghost"
              tag="div"
            >
              <div
                :class="[j.publish == 0 ? 'is-disabled' : '', 'list-item', 'is-sortable']"
                v-for="j in job"
                :key="j.id"
              >
                <div class="list-item-body">
                  <h3>{{ j.title }}</h3>
                </div>
                <div class="list-item-action">
                  <a
                    href="javascript:;"
                    :class="[j.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                    @click.prevent="toggleStatus(j.id,$event)"
                  ></a>
                  <router-link
                    :to="{name: 'job-edit', params: { id: j.id }}"
                    class="icon-edit icon-mini"
                  ></router-link>
                  <a href="javascript:;" class="icon-copy icon-mini" @click.prevent="clone(j.id,$event)"></a>
                  <a href="javascript:;" class="icon-trash icon-mini" @click.prevent="destroy(j.id,$event)"></a>
                </div>
              </div>
            </draggable>
          </div>
          <div v-else>
            <p>Es sind keine Artikel vorhanden...</p>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import Progress from "@/mixins/progress";
import draggable from "vuedraggable";


export default {
  components: {
    PageHeader: PageHeader,
    draggable
  },

  mixins: [Progress],

  data() {
    return {
      job: [],
      debounce: false
    };
  },

  created() {
    let uri = "/api/jobs/get";
    this.axios.get(uri).then(response => {
      this.job = response.data.data;
    });
  },

  methods: {
    destroy(id,event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/job/destroy/${id}`,
          self = this;
        let el = this.progress(event.target);
        this.axios
          .delete(uri)
          .then(response => {
            this.job.splice(this.job.indexOf(id), 1);
            self.$notify({ type: "success", text: "Eintrag gelöscht" });
            self.progress(el);
          })
          .catch(function(error) {
            self.$notify({ type: "error", text: error.response.data });
            self.progress(el);
          });
      }
    },

    clone(id,event) {
      let uri = `/api/job/clone/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        this.job.push(response.data);
        this.$notify({ type: "success", text: "Eintrag kopiert" });
        this.progress(el);
      });
    },

    toggleStatus(id,event) {
      let uri = `/api/job/status/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        const index = this.job.findIndex(x => x.id === id);
        this.job[index].publish = response.data;
        this.$notify({ type: "success", text: "Status angepasst" });
        this.progress(el);
      });
    },

    updateOrder() {
      let jobs = this.job.map(function(job, index) {
          job.order = index;
          return job;
      });

      if (this.debounce) return;

      this.debounce = setTimeout(function(jobs) {
        this.debounce = false 
        let uri = `/api/job/order`;
        this.axios.post(uri, {jobs: jobs}).then((response) => {
          //this.$router.push({name: 'competences'});
        });
      }.bind(this, jobs), 1000);
      this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
    }
  }
};
</script>