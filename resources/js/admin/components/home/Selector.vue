<template>
  <div>
    <div class="grid-layout-selector">
      <a href class="btn-toggle-layout" @click.prevent="toggleSelect()">Layout wählen</a>
      <ul :class="showSelect ? 'is-visible': ''" ref="dropdown">
        <li v-for="layout in layouts" :key="layout.id">
          <a href @click.prevent="addRow(layout.id)">
            <img :src="getMediaSource(layout.key)" height="30" width="100">
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      layouts: [],
      showSelect: false
    };
  },

  created() {
    this.axios.get("/api/home/grid/layout/fetch").then(response => {
      this.layouts = response.data.data;
    });
  },

  methods: {
    getMediaSource(key) {
      return `/assets/admin/img/icons/grid-layout-${key}.svg`;
    },

    toggleSelect() {
      this.showSelect = this.showSelect ? false : true;
    },

    addRow(id) {
      this.showSelect = false;
      this.$parent.addRow(id);
    }
  }
};
</script>
