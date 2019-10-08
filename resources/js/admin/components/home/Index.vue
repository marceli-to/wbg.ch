// IndexComponent.vue
<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <grid-selector></grid-selector>
          <div class="grid-rows">
            <div class="grid-row" v-for="grid in grids" :key="grid.id">
              <a
                href="javascript:;"
                class="btn-trash"
                @click.prevent="deleteRow(grid.id, $event)"
              >Zeile löschen</a>
              <grid-row :layout="grid.layout.key" :gridId="grid.id"></grid-row>
            </div>
          </div>
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

export default {
  components: {
    PageHeader: PageHeader,
    GridRow: GridRow,
    GridSelector: GridSelector
  },
  
  mixins: [progress],

  data() {
    return {
      grids: [],
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
    }
  },

  computed: {
    hasChanges: function() {
      return store.state.hasChanges;
    }
  }
};
</script>

