<template>
  <div>
    <div class="ratio-boxes">
      <div v-if="layout == '1fr'">
        <div class="box-1fr">
          <div class="box__c">
            <div v-if="elements[0] && elements[0].position == '0'">
              <div v-if="elements[0].isMedia">
                <grid-media :ratioW="86" :ratioH="56" :element="elements[0]"></grid-media>
              </div>
              <div v-if="elements[0].isArticle">
                <grid-article :element="elements[0]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="0"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="0"></button-add-news>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr-wide'">
        <div class="box-1fr">
          <div class="box__d">
            <div v-if="elements[0] && elements[0].position == '0'">
              <div v-if="elements[0].isMedia">
                <grid-media :ratioW="86" :ratioH="28" :element="elements[0]"></grid-media>
              </div>
              <div v-if="elements[0].isArticle">
                <grid-article :element="elements[0]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="0"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="0"></button-add-news>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr-portrait'">
        <div class="box-1fr">
          <div class="box__e">
            <div v-if="elements[0] && elements[0].position == '0'">
              <div v-if="elements[0].isMedia">
                <grid-media :ratioW="86" :ratioH="112" :element="elements[0]"></grid-media>
              </div>
              <div v-if="elements[0].isArticle">
                <grid-article :element="elements[0]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="0"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="0"></button-add-news>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '2x1fr-wide'">
        <div class="box-2x1fr">
          <div class="box__a">
            <div v-if="elements[0] && elements[0].position == '0'">
              <div v-if="elements[0].isMedia">
                <grid-media :ratioW="43" :ratioH="28" :element="elements[0]"></grid-media>
              </div>
              <div v-if="elements[0].isArticle">
                <grid-article :element="elements[0]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="0"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="0"></button-add-news>
              </div>
            </div>
          </div>
          <div class="box__a">
            <div v-if="elements[1] && elements[1].position == '1'">
              <div v-if="elements[1].isMedia">
                <grid-media :ratioW="43" :ratioH="28" :element="elements[1]"></grid-media>
              </div>
              <div v-if="elements[1].isArticle">
                <grid-article :element="elements[1]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="1"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="1"></button-add-news>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '2x1fr'">
        <div class="box-2x1fr">
          <div class="box__b">
            <div v-if="elements[0] && elements[0].position == '0'">
              <div v-if="elements[0].isMedia">
                <grid-media :ratioW="43" :ratioH="56" :element="elements[0]"></grid-media>
              </div>
              <div v-if="elements[0].isArticle">
                <grid-article :element="elements[0]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="0"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="0"></button-add-news>
              </div>
            </div>
          </div>
          <div class="box__b">
            <div v-if="elements[1] && elements[1].position == '1'">
              <div v-if="elements[1].isMedia">
                <grid-media :ratioW="43" :ratioH="56" :element="elements[1]"></grid-media>
              </div>
              <div v-if="elements[1].isArticle">
                <grid-article :element="elements[1]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="1"></button-add-image>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr-1fr_stacked'">
        <div class="box-2x1fr">
          <div class="box__b">
            <div v-if="elements[0] && elements[0].position == '0'">
              <div v-if="elements[0].isMedia">
                <grid-media :ratioW="43" :ratioH="56" :element="elements[0]"></grid-media>
              </div>
              <div v-if="elements[0].isArticle">
                <grid-article :element="elements[0]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="0"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="0"></button-add-news>
              </div>
            </div>
          </div>
          <div class="span">
            <div class="grid-stacked">
              <div class="box__a">
                <div v-if="elements[1] && elements[1].position == '1'">
                  <div v-if="elements[1].isMedia">
                    <grid-media :ratioW="43" :ratioH="28" :element="elements[1]"></grid-media>
                  </div>
                  <div v-if="elements[1].isArticle">
                    <grid-article :element="elements[1]"></grid-article>
                  </div>
                </div>
                <div v-else>
                  <div class="box-buttons">
                    <button-add-image :gridId="gridId" :gridPosition="1"></button-add-image>
                    <button-add-news :gridId="gridId" :gridPosition="1"></button-add-news>
                  </div>
                </div>
              </div>
              <div class="box__a">
                <div v-if="elements[2] && elements[2].position == '2'">
                  <div v-if="elements[2].isMedia">
                    <grid-media :ratioW="43" :ratioH="28" :element="elements[2]"></grid-media>
                  </div>
                  <div v-if="elements[2].isArticle">
                    <grid-article :element="elements[2]"></grid-article>
                  </div>
                </div>
                <div v-else>
                  <div class="box-buttons">
                    <button-add-image :gridId="gridId" :gridPosition="2"></button-add-image>
                    <button-add-news :gridId="gridId" :gridPosition="2"></button-add-news>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="layout == '1fr_stacked-1fr'">
        <div class="box-2x1fr">
          <div class="span">
            <div class="grid-stacked">
              <div class="box__a">
                <div v-if="elements[0] && elements[0].position == '0'">
                  <div v-if="elements[1].isMedia">
                    <grid-media :ratioW="43" :ratioH="28" :element="elements[0]"></grid-media>
                  </div>
                  <div v-if="elements[0].isArticle">
                    <grid-article :element="elements[0]"></grid-article>
                  </div>
                </div>
                <div v-else>
                  <div class="box-buttons">
                    <button-add-image :gridId="gridId" :gridPosition="0"></button-add-image>
                    <button-add-news :gridId="gridId" :gridPosition="0"></button-add-news>
                  </div>
                </div>
              </div>
              <div class="box__a">
                <div v-if="elements[1] && elements[1].position == '1'">
                  <div v-if="elements[1].isMedia">
                    <grid-media :ratioW="43" :ratioH="28" :element="elements[1]"></grid-media>
                  </div>
                  <div v-if="elements[1].isArticle">
                    <grid-article :element="elements[1]"></grid-article>
                  </div>
                </div>
                <div v-else>
                  <div class="box-buttons">
                    <button-add-image :gridId="gridId" :gridPosition="1"></button-add-image>
                    <button-add-news :gridId="gridId" :gridPosition="1"></button-add-news>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box__b">
            <div v-if="elements[2] && elements[2].position == '2'">
              <div v-if="elements[2].isMedia">
                <grid-media :ratioW="43" :ratioH="56" :element="elements[2]"></grid-media>
              </div>
              <div v-if="elements[2].isArticle">
                <grid-article :element="elements[2]"></grid-article>
              </div>
            </div>
            <div v-else>
              <div class="box-buttons">
                <button-add-image :gridId="gridId" :gridPosition="2"></button-add-image>
                <button-add-news :gridId="gridId" :gridPosition="2"></button-add-news>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div :class="[hasOverlay ? 'is-visible': '', 'overlay']">
      <a
        href="javascript:;"
        @click.prevent="toggleOverlay()"
        class="icon-close-overlay"
      ></a>
      <div>
        <div v-show="showMedia">
          <grid-media-selector :projects="projects" v-bind:is="selector"></grid-media-selector>
        </div>
        <div v-show="showNews">
          <grid-article-selector :news="news" v-bind:is="selector"></grid-article-selector>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import grid from "@/mixins/grid";
import GridMedia from "@/components/home/Media.vue";
import GridArticle from "@/components/home/Article.vue";
import ButtonAddImage from "@/components/home/buttons/ButtonAddImage.vue";
import ButtonAddNews from "@/components/home/buttons/ButtonAddNews.vue";

export default {
  components: {
    GridMedia: GridMedia,
    GridArticle: GridArticle,
    ButtonAddImage: ButtonAddImage,
    ButtonAddNews: ButtonAddNews,
  },

  props: {
    layout: String,
    gridId: Number
  },

  mixins: [grid],

  created() {
    this.fetchElements();
  },

  methods: {
    fetchElements() {
      let uri = `/api/home/grid/element/get/${this.$props.gridId}`;
      this.axios.get(uri).then(response => {
        let els = [];
        if (response.data.data) {
          response.data.data.forEach(e => {
            if (e.news_id) {
              let el = {
                id: e.id,
                position: e.position,
                isArticle: true,
                articleId: e.news.id,
                title: e.news.title,
                text: e.news.text,
                link: e.news.link || null,
                linkText: e.news.linkText || null
              };
              els[e.position] = el;
            }

            if (e.project_image_id) {
              let img = e.projectimage;
              let el = {
                id: e.id,
                position: e.position,
                isMedia: true,
                projectId: img.project.id || null,
                image: img.name || null,
                imageId: img.id,
                caption: `${img.project.name})`,
              };
              els[e.position] = el;
            }
          });
          this.elements = els;
        }
      });
    },
  }
};
</script>