<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <h1>Kunden</h1>
          <router-link :to="{ name: 'client-create' }" class="btn-add">
            <span>Hinzufügen</span>
          </router-link>
          <div class="list-items" v-if="clients.length">
            <div
              :class="[client.publish == 0 ? 'is-disabled' : '', 'list-item']"
              v-for="client in filteredList"
              :key="client.id"
            >
              <div class="list-item-body">
                <strong>{{ client.name }}</strong>, {{ client.location }}
              </div>
              <div class="list-item-action">
                <a
                  href="javascript:;"
                  :class="[client.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                  @click.prevent="toggleStatus(client.id)"
                ></a>
                <router-link
                  :to="{name: 'client-edit', params: { id: client.id }}"
                  class="icon-edit icon-mini"
                ></router-link>
                <a
                  href="javascript:;"
                  class="icon-copy icon-mini"
                  @click.prevent="clone(client.id)"
                ></a>
                <a
                  href="javascript:;"
                  class="icon-trash icon-mini"
                  @click.prevent="destroy(client.id)"
                ></a>
              </div>
            </div>
          </div>
          <div v-else>
            <p>Es sind keine Kunden vorhanden...</p>
          </div>
          <footer class="form-footer">
            <div>
              <input type="text" class="search" v-model="search" placeholder="Filter nach Name oder Ort">
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
export default {
  components: {
    draggable,
    PageHeader: PageHeader
  },

  data() {
    return {
      clients: [],
      search: '',
      debounce: false
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      let uri = "/api/clients/get";
      this.axios.get(uri).then(response => {
        this.clients = response.data.data;
      });
    },

    destroy(id) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/client/destroy/${id}`;
        this.axios.delete(uri).then(response => {
          this.fetch();
          this.$notify({ type: "success", text: "Eintrag gelöscht" });
        });
      }
    },

    clone(id) {
      let uri = `/api/client/clone/${id}`;
      this.axios.get(uri).then(response => {
        this.clients.push(response.data);
        this.$notify({ type: "success", text: "Eintrag kopiert" });
      });
    },

    toggleStatus(id) {
      let uri = `/api/client/status/${id}`;
      this.axios.get(uri).then(response => {
        const index = this.clients.findIndex(x => x.id === id);
        this.clients[index].publish = response.data;
        this.$notify({ type: "success", text: "Status angepasst" });
      });
    }
  },
  computed: {
    filteredList() {
      return this.clients.filter(client => {
        let name = client.name, location = client.location;
        if (
            name.toLowerCase().includes(this.search.toLowerCase()) || 
            location.toLowerCase().includes(this.search.toLowerCase())
        ) {
          return client;
        }
      })
    }
  }
};
</script>