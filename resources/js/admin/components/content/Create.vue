<template>
  <div>
    <page-header />
    <content-form type="create"></content-form>
  </div>
</template>
<script>
import PageHeader from '@/layout/PageHeader.vue';
import ContentForm from '@/components/content/form.vue';
export default {
  components: {
    PageHeader: PageHeader,
    ContentForm: ContentForm
  }
}
</script>