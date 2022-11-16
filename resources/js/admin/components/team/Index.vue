<template>
  <div>
    <page-header/>
    <notifications classes="notification"/>
    <div class="container">
      <main class="content" role="main">
        <div>
          <h1>Team</h1>
          <router-link :to="{ name: 'team-create' }" class="btn-add">
            <span>Hinzufügen</span>
          </router-link>
          <div class="list-item-cards is-team" v-if="team.length">
            <draggable
              v-model="team"
              @end="updateOrder"
              ghost-class="draggable-ghost"
              draggable=".list-item-card"
            >
              <div
                :class="[t.publish == 0 ? 'is-disabled' : '', 'list-item-card']"
                v-for="t in team"
                :key="t.id"
              >
                <div>
                  <span>
                    <strong>{{ t.firstname }} {{ t.name }}</strong>
                    <em v-if="t.role">, {{ t.role }}</em>
                  </span>
                  <span v-if="t.email">{{ t.email }}</span>
                  <span v-if="t.phone">{{ t.phone }}</span>
                </div>
                <div class="list-item-card__action">
                  <a
                    href="javascript:;"
                    :class="[t.publish == 1 ? 'icon-eye' : 'icon-eye-off', 'icon-mini']"
                    @click.prevent="toggleStatus(t.id,$event)"
                  ></a>
                  <router-link
                    :to="{name: 'team-edit', params: { id: t.id }}"
                    class="icon-edit icon-mini"
                  ></router-link>
                  <a href="javascript:;" class="icon-copy icon-mini" @click.prevent="clone(t.id,$event)"></a>
                  <a
                    href="javascript:;"
                    class="icon-trash icon-mini"
                    @click.prevent="destroy(t.id,$event)"
                  ></a>
                </div>
              </div>
            </draggable>
          </div>
          <div v-else>
            <p>Es sind keine Teammitglieder vorhanden...</p>
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
      team: [],
      debounce: false
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      let uri = "/api/team/get";
      this.axios.get(uri).then(response => {
        this.team = response.data.data;
      });
    },

    destroy(id, event) {
      if (confirm("Bitte löschen bestätigen!")) {
        let uri = `/api/team/destroy/${id}`;
        let el = this.progress(event.target);
        this.axios.delete(uri).then(response => {
          this.fetch();
          this.$notify({ type: "success", text: "Eintrag gelöscht" });
          this.progress(el);
        });
      }
    },

    clone(id, event) {
      let uri = `/api/team/clone/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        this.team.push(response.data);
        this.$notify({ type: "success", text: "Eintrag kopiert" });
        this.progress(el);
      });
    },

    toggleStatus(id, event) {
      let uri = `/api/team/status/${id}`;
      let el = this.progress(event.target);
      this.axios.get(uri).then(response => {
        const index = this.team.findIndex(x => x.id === id);
        this.team[index].publish = response.data;
        this.$notify({ type: "success", text: "Status angepasst" });
        this.progress(el);
      });
    },

    updateOrder() {
      let team = this.team.map(function(t, index) {
        t.order = index;
        return t;
      });

      if (this.debounce) return;

      this.debounce = setTimeout(
        function(team) {
          this.debounce = false;
          let uri = `/api/team/order`;
          this.axios.post(uri, { team: team }).then(response => {
            this.$router.push({ name: "team" });
          });
        }.bind(this, team),
        1000
      );
      this.$notify({ type: "success", text: "Reihenfolge angepasst" });
    }
  }
};
</script>