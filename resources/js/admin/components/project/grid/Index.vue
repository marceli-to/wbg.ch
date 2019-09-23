<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <h1>Layout für Projekt «{{pageTitle}}»</h1>
          <grid-selector></grid-selector>
          <div class="grid-rows">
            <draggable 
              v-model="grids" 
              @end="updateOrder"
              ghost-class="draggable-ghost"
              draggable=".grid-row">
              <div class="grid-row grid-row--draggable" v-for="grid in grids" :key="grid.id">
                <a
                  href="javascript:;"
                  class="btn-trash"
                  @click.prevent="destroy(grid.id,$event)"
                >Zeile löschen</a>
                <grid-row :layout="grid.layout.key" :gridId="grid.id" :projectId="projectId"></grid-row>
              </div>
            </draggable>
          </div>
          <footer class="form-footer">
            <div>
                <router-link :to="{name: 'projects'}" class="btn-secondary" style="margin-left:0">Zurück</router-link>
            </div>
          </footer>
        </div>
      </main>
    </div>
  </div>
</template>
<script>
import PageHeader from "@/layout/PageHeader.vue";
import draggable from 'vuedraggable';
import GridRow from "@/components/project/grid/Row.vue";
import GridSelector from "@/components/project/grid/Selector.vue";

export default {
  components: {
    draggable,
    PageHeader: PageHeader,
    GridRow: GridRow,
    GridSelector: GridSelector
  },

  data() {
    return {
      grids: [],
      pageTitle: null,
      projectId: null,
      debounce: false,
    };
  },

  created() {
    this.projectId = parseInt(this.$route.params.id);
    this.fetch();
  },

  mounted() {
    let uri = `/api/project/get/${parseInt(this.$route.params.id)}`;
    this.axios.get(uri).then(response => {
      let p = response.data;
      this.pageTitle = `${p.name}`;
    });
  },

  methods: {

    fetch() {
      let uri = `/api/project/grids/${this.projectId}`;
      this.axios.get(uri).then(response => {
        this.grids = response.data.data;
      });
    },

    store(gridId) {
      let uri = `/api/project/grid/store/${this.projectId}/${gridId}`;
      this.axios.get(uri).then(response => {
        this.fetch();
      });
    },

    updateOrder() {
      let grids = this.grids.map(function(grid, index) {
          grid.order = index;
          return grid;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function(books) {
        this.debounce = false 
        let uri = `/api/project/grids/order`;
        this.axios.post(uri, {grids: grids}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, grids), 1000);
    },

    destroy(gridId, event) {
      let uri = `/api/project/grid/delete/${gridId}`;
      this.axios.delete(uri).then(response => {
        let row = event.target.parentNode, self = this;
        row.classList.add('fade-out');
        setTimeout(function(){
          const index = self.grids.findIndex(x => x.id === gridId);
          self.grids.splice(index, 1);
          self.$notify({type: "success", text: "Zeile gelöscht!"});
        }, 200);
      });
    }
  },

  computed: {
    // title: function() {
    //   return this.project.year;
    // }
  }
};
</script>

