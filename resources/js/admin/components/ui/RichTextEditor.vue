<template>
  <div class="editor" :class="{ 'has-focus': editor?.isFocused }" :style="{ '--editor-height': height }">
    <div class="editor__toolbar" v-if="editor" role="toolbar" aria-label="Textformatierung">
      <div class="editor__group">
        <button type="button" title="Rückgängig" aria-label="Rückgängig" :disabled="!editor.can().undo()" @click="run((c) => c.undo())">
          <svg viewBox="0 0 24 24"><path d="M9 14 4 9l5-5" /><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11" /></svg>
        </button>
        <button type="button" title="Wiederholen" aria-label="Wiederholen" :disabled="!editor.can().redo()" @click="run((c) => c.redo())">
          <svg viewBox="0 0 24 24"><path d="m15 14 5-5-5-5" /><path d="M20 9H9.5a5.5 5.5 0 0 0 0 11H13" /></svg>
        </button>
      </div>
      <div class="editor__group">
        <button type="button" title="Fett" aria-label="Fett" :class="{ 'is-active': editor.isActive('bold') }" @click="run((c) => c.toggleBold())">
          <strong>B</strong>
        </button>
      </div>
      <div class="editor__group">
        <button type="button" title="Aufzählung" aria-label="Aufzählung" :class="{ 'is-active': editor.isActive('bulletList') }" @click="run((c) => c.toggleBulletList())">
          <svg viewBox="0 0 24 24"><path d="M9 6h11M9 12h11M9 18h11" /><circle cx="4.5" cy="6" r="1" /><circle cx="4.5" cy="12" r="1" /><circle cx="4.5" cy="18" r="1" /></svg>
        </button>
      </div>
      <div class="editor__group">
        <button type="button" title="Link" aria-label="Link" :class="{ 'is-active': editor.isActive('link') || linkForm }" @click="openLink">
          <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" /><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" /></svg>
        </button>
      </div>
      <div class="editor__group">
        <button type="button" title="Hochgestellt" aria-label="Hochgestellt" :class="{ 'is-active': editor.isActive('superscript') }" @click="run((c) => c.toggleSuperscript())">
          x<sup>2</sup>
        </button>
      </div>
      <div class="editor__group">
        <button type="button" title="Formatierung entfernen" aria-label="Formatierung entfernen" @click="run((c) => c.unsetAllMarks().clearNodes())">
          <svg viewBox="0 0 24 24"><path d="M4 7V4h16v3M9 20h6M12 4l-3 16" /><path d="m16 15 5 5m0-5-5 5" /></svg>
        </button>
      </div>
      <div class="editor__group">
        <select class="editor__styles" aria-label="Formate" :value="currentBlock" @change="setBlock($event.target.value)">
          <option value="paragraph">Absatz</option>
          <option value="h1">Überschrift 1</option>
          <option value="h2">Überschrift 2</option>
        </select>
        <button
          type="button"
          class="is-text"
          title="Worttrennung deaktivieren (markierten Text nicht umbrechen)"
          :class="{ 'is-active': editor.isActive('nowrap') }"
          @click="run((c) => c.toggleMark('nowrap'))"
        >Keine Worttrennung</button>
      </div>
    </div>

    <div class="editor__link" v-if="linkForm">
      <input
        ref="linkInput"
        type="text"
        v-model="linkForm.href"
        placeholder="https://… oder name@domain.ch"
        aria-label="Link-Adresse"
        @keydown.enter.prevent="applyLink"
        @keydown.esc.prevent="closeLink"
      />
      <label class="editor__checkbox">
        <input type="checkbox" v-model="linkForm.blank" /> In neuem Fenster öffnen
      </label>
      <button type="button" class="btn-secondary" @click="applyLink">Übernehmen</button>
      <a href="#" v-if="editor.isActive('link')" @click.prevent="removeLink">Link entfernen</a>
      <a href="#" @click.prevent="closeLink">Abbrechen</a>
    </div>

    <editor-content :editor="editor" class="editor__content" />
  </div>
</template>
<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import { useEditor, EditorContent, Mark } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Superscript from '@tiptap/extension-superscript';

/**
 * Rich text editor (replaces TinyMCE). Mirrors the old toolbar:
 * undo redo | bold | bullist | link | superscript | removeformat | styles
 * (Überschrift 1/2, "Worttrennung deaktivieren" = <span style="white-space: nowrap;">).
 * Pasted content is inserted as plain text (TinyMCE paste_as_text).
 */
const props = defineProps({
  modelValue: { type: String, default: '' },
  height: { type: String, default: '240px' },
});
const emit = defineEmits(['update:modelValue', 'focus']);

const NoWrap = Mark.create({
  name: 'nowrap',
  parseHTML() {
    return [{ tag: 'span', getAttrs: (el) => (el.style.whiteSpace === 'nowrap' ? null : false) }];
  },
  renderHTML() {
    return ['span', { style: 'white-space: nowrap;' }, 0];
  },
});

const editor = useEditor({
  content: props.modelValue || '',
  extensions: [
    StarterKit.configure({
      heading: { levels: [1, 2] },
      blockquote: false,
      code: false,
      codeBlock: false,
      horizontalRule: false,
      orderedList: false,
      strike: false,
      underline: false,
      trailingNode: false,
      link: {
        openOnClick: false,
        autolink: false,
        linkOnPaste: false,
        HTMLAttributes: { target: null, rel: null, class: null },
      },
    }),
    Superscript,
    NoWrap,
  ],
  editorProps: {
    // paste as plain text; the synthetic event of pasteText has no clipboardData
    handlePaste(view, event) {
      const data = event.clipboardData;
      if (data && data.types.includes('text/html')) {
        view.pasteText(data.getData('text/plain'));
        return true;
      }
      return false;
    },
  },
  onUpdate: ({ editor }) => emit('update:modelValue', output(editor)),
  onFocus: () => emit('focus'),
});

function output(ed) {
  if (ed.isEmpty) return '';
  // Tiptap wraps list item text in <p>; unwrap single paragraphs so the
  // markup matches what TinyMCE produced (<li>text</li>)
  return ed.getHTML().replace(/<li><p>((?:(?!<\/?p>)[\s\S])*)<\/p><\/li>/g, '<li>$1</li>');
}

watch(
  () => props.modelValue,
  (value) => {
    if (!editor.value) return;
    if ((value || '') !== output(editor.value)) {
      editor.value.commands.setContent(value || '', { emitUpdate: false });
    }
  },
);

onBeforeUnmount(() => editor.value?.destroy());

function run(fn) {
  fn(editor.value.chain().focus()).run();
}

const currentBlock = computed(() => {
  if (!editor.value) return 'paragraph';
  if (editor.value.isActive('heading', { level: 1 })) return 'h1';
  if (editor.value.isActive('heading', { level: 2 })) return 'h2';
  return 'paragraph';
});

function setBlock(value) {
  if (value === 'h1') run((c) => c.setHeading({ level: 1 }));
  else if (value === 'h2') run((c) => c.setHeading({ level: 2 }));
  else run((c) => c.setParagraph());
}

// Link form
const linkForm = ref(null);
const linkInput = ref(null);

function openLink() {
  const attrs = editor.value.getAttributes('link');
  linkForm.value = { href: attrs.href || '', blank: attrs.target === '_blank' };
  nextTick(() => linkInput.value?.focus());
}

function closeLink() {
  linkForm.value = null;
  editor.value.commands.focus();
}

function normalizeHref(href) {
  href = href.trim();
  if (!href) return '';
  if (/^(https?:|mailto:|tel:|\/|#)/i.test(href)) return href;
  if (href.includes('@')) return `mailto:${href}`;
  return `https://${href}`;
}

function applyLink() {
  const href = normalizeHref(linkForm.value.href);
  if (!href) {
    removeLink();
    return;
  }
  const attrs = { href, target: linkForm.value.blank ? '_blank' : null, rel: linkForm.value.blank ? 'noopener' : null };
  const chain = editor.value.chain().focus().extendMarkRange('link');
  if (editor.value.state.selection.empty && !editor.value.isActive('link')) {
    // nothing selected: insert the address as linked text
    chain.insertContent({ type: 'text', text: linkForm.value.href.trim(), marks: [{ type: 'link', attrs }] }).run();
  } else {
    chain.setLink(attrs).run();
  }
  linkForm.value = null;
}

function removeLink() {
  editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
  linkForm.value = null;
}
</script>
