<template>
  <div>
    <page-header />
    <competence-form type="edit"></competence-form>
  </div>
</template>
<script>
import PageHeader from '@/layout/PageHeader.vue';
import competenceForm from '@/components/competence/form.vue';
  export default {
    components: {
      PageHeader: PageHeader,
      competenceForm: competenceForm
    }
  }
</script>