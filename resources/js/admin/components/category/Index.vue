<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <h1>Kategorien</h1>
          <router-link :to="{ name: 'category-create' }" class="btn-add">
            <span>Hinzufügen</span>
          </router-link>
          <div class="list-items" v-if="categories.length">
            <draggable
              v-model="categories"
              @end="updateOrder"
              ghost-class="draggable-ghost"
              tag="div"
            >
              <div
                :class="[category.publish == 0 ? 'is-disabled' : '', 'list-item', 'is-sortable']"
                v-for="category in categories"
                :key="category.id"
              >
                <div class="list-item-body">
                  <h3>{{ category.name }}</h3>
                </div>
                <div class="list-item-action">
                  <a
                    href="javascript:;"
                    :class="[category.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                    @click.prevent="toggleStatus(category.id,$event)"
                  ></a>
                  <router-link
                    :to="{name: 'category-edit', params: { id: category.id }}"
                    class="icon-edit icon-mini"
                  ></router-link>
                  <a
                    href="javascript:;"
                    class="icon-copy icon-mini"
                    @click.prevent="clone(category.id,$event)"
                  ></a>
                  <a
                    href="javascript:;"
                    class="icon-trash icon-mini"
                    @click.prevent="destroy(category.id,$event)"
                  ></a>
                </div>
              </div>
            </draggable>
          </div>
          <div v-else>
            <p>Es sind keine Kategorien vorhanden...</p>
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
      categories: [],
      debounce: false
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      let uri = "/api/categories/get";
      this.axios.get(uri).then(response => {
        this.categories = response.data.data;
      });
    },

    destroy(id,event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/category/destroy/${id}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          this.fetch();
          this.$notify({ type: "success", text: "Eintrag gelöscht" });
          this.progress(el)
        });
      }
    },

    clone(id,event) {
      let uri = `/api/category/clone/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        this.categories.push(response.data);
        this.$notify({ type: "success", text: "Eintrag kopiert" });
        this.progress(el);
      });
    },

    toggleStatus(id,event) {
      let uri = `/api/category/status/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        const index = this.categories.findIndex(x => x.id === id);
        this.categories[index].publish = response.data;
        this.$notify({ type: "success", text: "Status angepasst" });
        this.progress(el);
      });
    },

    updateOrder() {
      let categories = this.categories.map(function(category, index) {
          category.order = index;
          return category;
      });

      if (this.debounce) return;

      this.debounce = setTimeout(function(categories) {
        this.debounce = false 
        let uri = `/api/category/order`;
        this.axios.post(uri, {categories: categories}).then((response) => {
          this.$router.push({name: 'categories'});
        });
      }.bind(this, categories), 1000);
      this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
    }
  }
};
</script>