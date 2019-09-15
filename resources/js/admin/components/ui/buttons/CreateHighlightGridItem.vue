<template>
    <div>
        <a href="javascript:;" class="btn-add-media-highlight" @click.prevent="createMedia(gridId,0);">Hinzufügen</a>
    </div>
</template>
<script>
import grid from '@/mixins/grid';

export default {

    props: {
        gridId: Number,
        gridPosition: Number,
    },

    mixins: [grid],

    methods: {
        createMedia(gridId,position) {
            this.$parent.createMedia(gridId, position);
        },
    }
}
</script>