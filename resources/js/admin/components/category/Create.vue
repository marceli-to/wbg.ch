<template>
  <div>
    <page-header />
    <category-form type="create"></category-form>
  </div>
</template>
<script>
import PageHeader from '@/layout/PageHeader.vue';
import categoryForm from '@/components/category/form.vue';
  export default {
    components: {
      PageHeader: PageHeader,
      categoryForm: categoryForm
    }
  }
</script>