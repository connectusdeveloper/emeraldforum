import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import Tooltip from '@ryangjchandler/alpine-tooltip';

// import hljs from "highlight.js";
import hljs from 'highlight.js/lib/core';
import css from 'highlight.js/lib/languages/css';
import php from 'highlight.js/lib/languages/php';
import xml from 'highlight.js/lib/languages/xml';
import sql from 'highlight.js/lib/languages/sql';
import json from 'highlight.js/lib/languages/json';
import plaintext from 'highlight.js/lib/languages/plaintext';
import javascript from 'highlight.js/lib/languages/javascript';
hljs.registerLanguage('css', css);
hljs.registerLanguage('php', php);
hljs.registerLanguage('xml', xml);
hljs.registerLanguage('sql', sql);
hljs.registerLanguage('json', json);
hljs.registerLanguage('plaintext', plaintext);
hljs.registerLanguage('javascript', javascript);

// import SimpleMDE from "simplemde";
import 'simplemde/dist/simplemde.min.css';
import SimpleMDE from 'simplemde/dist/simplemde.min.js';
import './attachment.js';

window.hljs = hljs;
window.Alpine = Alpine;
window.SimpleMDE = SimpleMDE;

Alpine.plugin(Tooltip);
Alpine.plugin(focus);
Alpine.start();
