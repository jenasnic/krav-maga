import tinyMCE, { Editor } from 'tinymce/tinymce';
import './fr_FR';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis.js';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/autoresize';
import 'tinymce/plugins/link';
import 'tinymce/plugins/paste';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/ui/oxide/content.min.css';
import 'tinymce-placeholder-master/placeholder/plugin.min';

import { stripTags } from './strip-tags';

/**
 * Bind individual tinymce form input
 */
export const bindWysiwyg = textarea => {
  tinyMCE.init({
    target: textarea,
    entity_encoding: 'raw',
    menubar: false,
    statusbar: false,
    plugins: 'link lists autoresize paste',
    toolbar: 'bold italic underline strikethrough link bullist forecolor | indent outdent | alignleft aligncenter alignright alignjustify | fontsizeselect | testButton',
    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    language: 'fr_FR',
    content_style: 'body { font-family: \'Montserrat\', sans-serif; } .wysiwyg-test { border: 1px dotted #222222; padding: 10px; margin-bottom: 5px; }',
    color_map: [
      '3D8C36', 'Vert',
      '000000', 'Noir',
      '222222', 'Gris foncé',
      '575757', 'Gris clair',
      'CA971D', 'Jaune',
      'FFFFFF', 'Blanc'
    ],
    paste_preprocess: (plugin, args) => {
      args.content = stripTags(args.content, ['br', 'a', 'b', 'em', 'ol', 'ul', 'li', 'p']);
    },
    browser_spellcheck: true,
    contextmenu: false,
    setup: (editor) => {
      editor.ui.registry.addButton('testButton', {
        text: 'Test',
        icon: 'warning',
        tooltip: 'Fonctionnalité de test',
        onAction: function (_) {
          editor.insertContent('<div class="wysiwyg-test" data-test="test"></div>');
        }
      });
    },
  });
};
