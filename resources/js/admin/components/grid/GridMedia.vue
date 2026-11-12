<template>
  <div class="box-wrapper">
    <figure :style="{ backgroundImage: `url(${media.size(element.image, 'lg')})` }">
      <a href="#" :class="['btn-trash', isHome ? '' : 'is-mini']" @click.prevent="$emit('delete', $event)">Löschen</a>
      <a href="#" class="btn-crop" @click.prevent="overlay.show()">Crop</a>
      <figcaption v-if="element.caption">
        <strong>{{ element.caption }}</strong>
      </figcaption>
    </figure>

    <Teleport to="body">
      <div class="overlay-crop is-visible" v-if="overlay.visible.value">
        <a href="#" @click.prevent="overlay.hide()" class="icon-close-overlay" aria-label="Schliessen"></a>
        <div>
          <span class="cropper-info">Neue Grösse:<br /><template v-if="cropW">{{ cropW }}px x {{ cropH }}px</template></span>
          <cropper
            class="cropper"
            :src="media.source(element.image)"
            :stencil-props="{ aspectRatio: ratio[0] / ratio[1] }"
            @change="change"
          />
          <div class="form-buttons">
            <a href="#" class="btn-secondary" @click.prevent="crop($event)">Speichern</a>
            <a href="#" @click.prevent="overlay.hide()">Abbrechen</a>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import api from '@/lib/api';
import { media, withLoading } from '@/lib/helpers';
import { success } from '@/lib/notify';
import { useOverlay } from '@/composables/useOverlay';

const props = defineProps({
  element: { type: Object, required: true },
  ratio: { type: Array, required: true }, // [w, h]
  isHome: { type: Boolean, default: false },
});
const emit = defineEmits(['delete', 'cropped']);

const overlay = useOverlay();
const coords = ref(null);
const cropW = ref(null);
const cropH = ref(null);

function change({ coordinates }) {
  coords.value = { w: coordinates.width, h: coordinates.height, x: coordinates.left, y: coordinates.top };
  cropW.value = Math.floor(coordinates.width);
  cropH.value = Math.floor(coordinates.height);
}

function crop(event) {
  if (!coords.value) return;
  return withLoading(event, async () => {
    await api.post('project-images/crop', {
      image: props.element.image,
      imageId: props.element.imageId,
      gridElementId: props.element.id,
      isHomeGrid: props.isHome,
      coords: coords.value,
    });
    overlay.hide();
    success('Bild zugeschnitten');
    emit('cropped');
  });
}
</script>
