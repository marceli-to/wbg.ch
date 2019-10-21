// IndexComponent.vue
<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <a href="javascript:;"
            class="icon-layout is-home"
            @click.prevent="toggleView()">
            <span v-if="layout == 'grid'">Grid</span>
            <span v-if="layout == 'list'">Liste</span>
          </a>
          <grid-selector></grid-selector>
                    
          <div class="grid-rows" v-if="layout == 'grid'">
            <div class="grid-row" v-for="grid in grids" :key="grid.id">
              <a
                href="javascript:;"
                class="btn-trash"
                @click.prevent="deleteRow(grid.id, $event)"
              >Zeile löschen</a>
              <grid-row :layout="grid.layout.key" :gridId="grid.id"></grid-row>
            </div>
          </div>

          <div class="grid-rows" v-if="layout == 'list'">
            <draggable 
              :disabled="false"
              v-model="grids" 
              @end="updateOrder"
              ghost-class="draggable-ghost"
              draggable=".grid-row">
              <div class="grid-row is-list is-draggable" v-for="grid in grids" :key="grid.id">
                <span class="icon-grid-list">
                  <img :src="'/assets/admin/img/icons/grid-layout-' + grid.layout.key + '.svg'" height="172" width="126">
                </span>
              </div>
            </draggable>
          </div>
          <footer class="form-footer">
            <div>
              <a
                href="/"
                class="btn-preview"
                target="_blank"
              >Vorschau</a>
            </div>
          </footer>
          <footer :class="[hasChanges ? '' : 'is-hidden', 'form-footer is-warning']">
            <div>
              <div class="fs-xs">
                Die Seite hat nicht publizierte Änderungen. Damit diese auf der Webseite sichtbar werden, muss das aktuelle Layout publiziert werden.
              </div>
              <div>
                <button
                  type="submit"
                  class="btn-secondary"
                  @click.prevent="publish()"
                >Änderungen publizieren</button>
              </div>
              <div>
                <button
                  type="submit"
                  class="btn-primary"
                  @click.prevent="restore()"
                  >Änderungen verwerfen</button>
              </div>
            </div>
          </footer>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import store from "@/store";
import progress from "@/mixins/progress";
import PageHeader from "@/layout/PageHeader.vue";
import GridRow from "@/components/home/Row.vue";
import GridSelector from "@/components/home/Selector.vue";
import draggable from 'vuedraggable';

export default {
  components: {
    draggable,
    PageHeader: PageHeader,
    GridRow: GridRow,
    GridSelector: GridSelector
  },
  
  mixins: [progress],

  data() {
    return {
      grids: [],
      layout: 'grid',
      debounce: false,
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      let self = this;
      this.axios.get("/api/home/grids").then(response => {
        this.grids = response.data.data;
        response.data.data.forEach(function(row) {
          row.elements.forEach(function(el) {
            if (
              (el.environment == "production" && el.action == "delete") ||
              el.environment == "development"
            ) {
              store.commit("gridChanged");
            }
          });
        });
      });
    },

    publish() {
      if (
        confirm(
          "Bitte publizieren bestätigen."
        )
      ) {
        this.axios.get("/api/home/grids/deploy").then(response => {
          this.$notify({ type: "success", text: "Seite publiziert!" });
          store.commit("gridDeployed");
        });
      }
    },

    restore() {
      if (
        confirm(
          "Bitte zurücksetzen bestätigen."
        )
      ) {
        this.axios.get("/api/home/grids/reset").then(response => {
          this.$notify({type: "success", text: "Seite publiziert!"});
          store.commit("gridDeployed");
          this.$router.go();
        });
      }
    },

    addRow(id) {
      let uri = `/api/home/grid/store/${id}`;
      this.axios.get(uri).then(response => {
        this.grids = response.data.data;
        this.$notify({ type: "success", text: "Zeile hinzugefügt!" });
        this.fetch();
      });
    },

    deleteRow(id, event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/home/grid/delete/${id}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          let row = event.target.parentNode,
            self = this;
          row.classList.add("fade-out");
          setTimeout(function() {
            const index = self.grids.findIndex(x => x.id === id);
            self.grids.splice(index, 1);
            self.$notify({ type: "success", text: "Zeile gelöscht!" });
            self.progress(el);
          }, 200);
        });
      }
    },

    updateOrder() {
      let grids = this.grids.map(function(grid, index) {
          grid.order = index;
          return grid;
      });
      if (this.debounce) return;
      this.debounce = setTimeout(function(books) {
        this.debounce = false 
        let uri = `/api/home/grids/order`;
        this.axios.post(uri, {grids: grids}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, grids), 1000);
    },

    toggleView() {
      this.layout = this.layout == 'grid' ? 'list' : 'grid';
    }
  },

  computed: {
    hasChanges: function() {
      return store.state.hasChanges;
    }
  }
};
</script>

