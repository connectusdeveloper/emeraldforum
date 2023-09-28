{{-- @props(['id' => 'textarea-body', 'disabled' => false, 'property' => '', 'property_of' => 'page', 'model' => '']) --}}

@php
  $exts = str($settings['allowed_files'] ?? 'jpg,jpeg,png,pdf')
      ->explode(',')
      ->all();
  $exts = \Illuminate\Support\Arr::map($exts, fn($e) => '.' . $e);
  $exts = \Illuminate\Support\Arr::join($exts, ',');
@endphp
<div x-data="{ value: @entangle($attributes->wire('model')) }" x-init="tinymce.init({
    target: $refs.tinymce,
    themes: 'modern',
    language: '{{ app()->getLocale() }}',
    @if(app()->getLocale() != 'en')
    language_url: '{{ asset('/tiny/langs/' . app()->getLocale() . '.js') }}',
    @endif
    height: 200,
    menubar: false,
    statusbar: false,
    {{-- element_format: 'html',
    entity_encoding: 'raw',
    forced_root_block: 'div', --}}
    skin: localStorage.getItem('theme') == 'dark' ? 'oxide-dark' : 'oxide',
    content_css: localStorage.getItem('theme') == 'dark' ? 'dark' : 'default',
    plugins: [
        'advlist ',
        'autolink ',
        'lists ',
        'link ',
        'image ',
        'emoticons ',
        'fullscreen ',
        'insertdatetime ',
        'media ',
        'table ',
        'code',
        'codesample',
        'autoresize'
    ],
    image_dimensions: false,
    file_picker_types: 'file image media',
    images_file_types: 'svg,jpeg,jpg,jpe,jfi,jif,jfif,png,gif,bmp,webp',
    @if(1 == ($settings['allowed_upload'] ?? 0))
    file_picker_callback: async function(callback, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        {{-- input.setAttribute('accept', 'image/*'); --}}
        input.setAttribute('accept', '{{ $exts }}');

        input.onchange = function() {
            var file = this.files[0];
            file.name = input.files.item(0).name;
            var reader = new FileReader();
            reader.onload = async function() {
                var id = 'blobid' + (new Date()).getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                if (meta.filetype == 'file') {
                    const data = new FormData();
                    data.append('file', blobInfo.blob(), file.name);

                    let res = await fetch('{{ route('upload') }}', {
                        method: 'post',
                        body: data,
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                    });

                    let result = await res.json();
                    if (res.ok) {
                        callback(result.filename, { text: file.name });
                    } else {
                        const eventNotification = new CustomEvent('wireui:notification', {
                            bubbles: true,
                            detail: {
                                'options': {
                                    'icon': 'error',
                                    'title': '{{ __('Failed!') }}',
                                    'description': result.message
                                }
                            }
                        });
                        document.dispatchEvent(eventNotification);
                    }
                } else {
                    callback(blobInfo.blobUri(), { title: file.name, alt: file.name });
                }
            };
            reader.readAsDataURL(file);
        };

        input.click();
    },
    {{-- automatic_uploads: true, --}}
    @endif
    images_upload_handler: image_upload_handler,
    toolbar: 'code | bold italic underline | alignment bullist numlist | {{ 1 == ($settings['allowed_upload'] ?? 0) ? ' insertfile image media ' : '' }} link | codesample emoticons',
    toolbar_groups: {
        alignment: {
            icon: 'align-left',
            tooltip: 'text align',
            items: 'alignleft aligncenter alignright alignjustify'
        }
    },
    quickbars_image_toolbar: 'alignleft aligncenter alignright | rotateleft rotateright | imageoptions',
    setup: function(editor) {
        editor.on('blur', function(e) {
            value = editor.getContent()
        })

        editor.on('init', function(e) {
            if (value != null) {
                editor.setContent(value)
            }
        })

        function putCursorToEnd() {
            editor.selection.select(editor.getBody(), true);
            editor.selection.collapse(false);
        }

        $watch('value', function(newValue) {
            if (newValue !== editor.getContent()) {
                editor.resetContent(newValue || '');
                putCursorToEnd();
            }
        });

        window.addEventListener('quoteReply', (event) => {
            let el = document.getElementById('reply-body');
            el.blur();
            let value = el.value;
            el.value = '<blockquote>' + event.detail + '</blockquote>\n\n<p></p>' + value;
            editor.setContent(el.value);
            el.dispatchEvent(new Event('change'))
            let enterE = {
                'key': 'Enter',
            };
            el.dispatchEvent(new KeyboardEvent('keypress', enterE));
            el.dispatchEvent(new KeyboardEvent('keydown', enterE));
            el.dispatchEvent(new KeyboardEvent('keyup', enterE));
            setTimeout(() => {
                el.style.height = el.scrollHeight;
                el.scrollTop = el.scrollHeight + 100;
            }, 100);
            el.scrollIntoView();
            el.focus();
            document.getElementById('reply-form-con').scrollIntoView();
            editor.focus();
            editor.selection.select(editor.getBody(), true);
            editor.selection.collapse(false);
        });
    }
})" wire:ignore class="prose dark:prose-invert max-w-none mt-1">
  <div>
    <input x-ref="tinymce" type="textarea" {{ $attributes->whereDoesntStartWith('wire:model') }}>
  </div>
</div>

@once
  @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/skins/ui/oxide-dark/skin.min.css"
      integrity="sha512-OU4OFhVBDpHToj5mu6wwbkxifnsfLKmaUOw8kPn6Rnbe8xeeT0+YTOELDjNwK2HKmQ+emMUAAs76UEx2DKjZuw==" crossorigin="anonymous"
      referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/skins/ui/oxide/skin.min.css"
      integrity="sha512-Oe++X5OvxcChy3xUmEb2orvpqYVoeDtjyC5YpboS25Zi+rrdhqSkZY8gvJ/wnRfS9OlgMRBQGmFY8Jw5qOuo9A==" crossorigin="anonymous"
      referrerpolicy="no-referrer" />
  @endpush
  @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/tinymce.min.js"
      integrity="sha512-in/06qQzsmVw+4UashY2Ta0TE3diKAm8D4aquSWAwVwsmm1wLJZnDRiM6e2lWhX+cSqJXWuodoqUq91LlTo1EA==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/icons/default/icons.min.js"
      integrity="sha512-iZEjj5ZEdiNAMLCFKlXVZkE0rKZ9xRGFtr0aMi8gxbEl1RbMCbpPomRiKurc93QVFdaxcnduQq6562xxqbC6wQ==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/models/dom/model.min.js"
      integrity="sha512-dwXGu5P9lpIb0LFFBzncIwvqcXPSYpv6JRWcoKfNDofrHRH9HT24PWYcCbVlVPe/2A39j2Z7UhgnBfPVltmSZQ==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/advlist/plugin.min.js"
      integrity="sha512-0bu0STJbYWO3NfSzpQNRmS6+BZROaDnqyL5mdfRkw8pDqJdO6fqNCzg573b76d5cO3grqDA5vyNpuf5goqNtQg==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/autolink/plugin.min.js"
      integrity="sha512-vbpqb+QQt0d157kBpooZjWfYIPgvyoiScHSZ1btEhs1heRO/+4TCiIodfe8fmmIoe4QA9mnvcx8jklAGT+P83g==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/autoresize/plugin.min.js"
      integrity="sha512-JqZIPK7Y2fMMM+lmo3oWxfaTkyCOgUbEeWDmTbthFip8THst6zfL7QMd+wswDOSALySDXLYjCwUm5seB9SB3xg==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/charmap/plugin.min.js"
      integrity="sha512-kpE952/TYVsVpqkn5Zxb5RyK1Rh+BaFDEYg5UxPMx86l+uu9zXBo/2hnQaGNtMfDBgtrhXGufdddGQ2puBTLYA==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/code/plugin.min.js"
      integrity="sha512-tJDtK+qxs593Fwwh9YWHWO3DLBPDk3lKlBL8QHB4AYSErXcvQy0StjYkGHDdbgSh1z6hFMvuW9TlQB67fMhXSA==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/codesample/plugin.min.js"
      integrity="sha512-f4kBtvEoWWYga4MkXXchGiD883rVZzHNugI6KtoNGq5Kr/t6lfnoPUI1XiJxv4FvjS2+kFFE1Vj2W6G9JcPSjQ==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/emoticons/js/emojiimages.min.js"
      integrity="sha512-cqo4otyf79ihBk6bytEIA3wH+CrtLtJMWwwFRMoxN9BLcBfqxQP9mQVSxJz4ojPOZryQ0xDwUvEEWJygKo8c7w==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/emoticons/js/emojis.min.js"
      integrity="sha512-5NJEoT6d7p+dhnqoCaA/ydy0t8k2kBssXSErCkzG4HiuRPWPfiVg+bGP0XPdpxWRv9ooHCrYYLbiBRKoFRbvCw==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/emoticons/plugin.min.js"
      integrity="sha512-iiNrST57JAj6IurUsykjDpeBf/BfvZtJXYWHvQxyxZufofIqoeh27uHu2FjaQVZXeChGtApXmS72aENfQN1dbA==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/fullscreen/plugin.min.js"
      integrity="sha512-S/cVdQ8cEgqrGaaNi/iYKi99il4tshKqVYR4ABt8qfIPFXH2yGvDQDQowoyKioWf7/ByNaVz7PJLIYGk4kk70w==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/image/plugin.min.js"
      integrity="sha512-4Sn2g9aWMgbsoUTlbPxa6Yr26e2qs1oPuEcaUc57Cw1T3AhSMOYmUafw6tTYSj6lud5rn/1gSROzvURQ9JMDNg==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/advlist/plugin.min.js"
      integrity="sha512-0bu0STJbYWO3NfSzpQNRmS6+BZROaDnqyL5mdfRkw8pDqJdO6fqNCzg573b76d5cO3grqDA5vyNpuf5goqNtQg==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/pagebreak/plugin.min.js"
      integrity="sha512-klN0QZYfErTH5yvX8Uai6uYVW0+OVkgh4wrQYrf8O935X596tKQCECHXe0Kgx5dqmJuDms1rDiVBhirfDypeEg==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/plugins/table/plugin.min.js"
      integrity="sha512-qWlFuIFILzqtngGfST5A0wXYoyerFCCHLu2Zf3Fa0HhRc4vOz/uVBfaJwonytkqN+pUlu/BwLMbI77zfXA1jyg==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.5.1/plugins/media/plugin.min.js"
      integrity="sha512-nYkXNx8+9NXpQbCEZuBdFhgN51DS4jj0+I8w9aRF6SZHCqZpoLrBWa+lRAL3DlQtrJdDgdVOdq+Frvmq+ZsYzg==" crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
    <script>
      const image_upload_handler = (blobInfo, progress) => new Promise(async (resolve, reject) => {
        const data = new FormData();
        data.append('file', blobInfo.blob(), blobInfo.filename());
        let res = await fetch('{{ route('upload') }}', {
          method: 'post',
          body: data,
          credentials: "include",
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': '{{ csrf_token() }}',
          },
        });

        let result = await res.json();
        if (res.ok) {
          resolve(result.filename);
        }

        reject(result.message);
      });
    </script>
  @endpush
@endonce
