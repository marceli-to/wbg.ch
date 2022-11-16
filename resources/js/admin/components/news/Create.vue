<template>
  <div>
    <page-header />
    <news-form type="create"></news-form>
  </div>
</template>
<script>
import PageHeader from '@/layout/PageHeader.vue';
import NewsForm from '@/components/news/form.vue';
  export default {
    components: {
      PageHeader: PageHeader,
      NewsForm: NewsForm
    }
  }
</script>