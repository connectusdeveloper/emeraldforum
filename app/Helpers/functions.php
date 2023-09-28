<?php

// Site Config
if (! function_exists('site_config')) {
    function site_config($key = null)
    {
        $settings = cache()->remember(
            'forum_settings',
            60,
            fn () => \App\Models\Setting::all(['tec_key', 'tec_value'])->mapWithKeys(fn ($item) => [$item['tec_key'] => $item['tec_value']])
        );

        return $key ? ($settings[$key] ?? null) : $settings;
    }
}

if (! function_exists('get_image_mime_type')) {
    function get_image_mime_type($ext)
    {
        return [
            'png'  => 'image/png',
            'jpg'  => 'image/jpg',
            'jpeg' => 'image/jpg',
            'gif'  => 'image/gif',
            'ico'  => 'image/ico',
            'svg'  => 'image/svg+xml',
        ][$ext] ?? null;
    }
}

// Log Activity
if (! function_exists('log_activity')) {
    function log_activity($activity, $properties = null, $model = null)
    {
        return activity()->performedOn($model)->withProperties($properties)->log($activity);
    }
}

// Get translation
if (! function_exists('__choice')) {
    function __choice($key, array $replace = [], $number = null)
    {
        return trans_choice($key, $number, $replace);
    }
}

// Check banned words
if (! function_exists('check_banned_words')) {
    function check_banned_words($data, $form = false)
    {
        $banned_words = array_map('trim', explode(',', site_config('banned_words') ?? ''));
        if (! empty($banned_words)) {
            if ($form) {
                foreach ($banned_words as $word) {
                    $data['body'] = str($data['body'])->replace($word, mask_str($word));
                }
            } else {
                foreach ($banned_words as $word) {
                    $data->title = str($data->title)->replace($word, mask_str($word));
                    $data->description = str($data->description)->replace($word, mask_str($word));
                    $data->body = str($data->body)->replace($word, mask_str($word));
                }
            }
        }

        return $data;
    }
}

// Mask string
if (! function_exists('mask_str')) {
    function mask_str($str, $replace = '&ast;')
    {
        $len = strlen($str);

        return $len >= 3 ? substr($str, 0, 1) . str_repeat($replace, $len - 2) . substr($str, $len - 1, 1) : '**';
    }
}

// Get id with parents
if (! function_exists('get_id_with_parents')) {
    function get_id_with_parents($category_id, $categories = [], $model = \App\Models\Category::class, $cId = 'category_id')
    {
        $category = $model::find($category_id);
        $categories[] = $category->id;
        if ($category->{$cId}) {
            $categories = get_id_with_parents($category->{$cId}, $categories, $model);
        }

        return $categories;
    }
}

// Parse markdown
if (! function_exists('parse_markdown')) {
    function parse_markdown(string $markdown)
    {
        $dom = new DomDocument();
        // $dom->formatOutput = true;
        $editor = site_config('editor');
        $auto_load = site_config('auto_load_video');

        libxml_use_internal_errors(true);
        $str = '<meta content="text/html; charset=utf-8" http-equiv="Content-Type">' . $editor == 'markdown' ? str($markdown)->markdown() : $markdown;
        $dom->loadHTML($str);
        libxml_clear_errors();

        $links = $dom->getElementsByTagName('a');
        $domain = parse_url(url('/'), PHP_URL_HOST);

        foreach ($links as $link) {
            $urlHost = parse_url($link->getAttribute('href'), PHP_URL_HOST);
            if (null !== $urlHost && $urlHost !== $domain) {
                $link->setAttribute('rel', 'noopener noreferrer nofollow');
                $link->setAttribute('target', '_blank');
            }
        }

        $iframe_tags = $dom->getElementsByTagName('iframe');
        foreach ($iframe_tags as $iframe_tag) {
            if ($auto_load) {
                $div = $iframe_tag->parentNode->appendChild($dom->createElement('div'));
                $div->setAttribute('class', 'video');
                $divInner = $dom->createDocumentFragment();
                $divInner->appendXML($iframe_tag->ownerDocument->saveXML($iframe_tag));
                $div->appendChild($divInner);
                $iframe_tag->parentNode->replaceChild($div, $iframe_tag);
            } else {
                $replace = ('<div x-data="{show: false, src: \'' . $iframe_tag->getAttribute('src') . '\', showVideo() { this.$refs.video_container.querySelector(\'iframe\').src = this.src; this.show = true; } }" class="video"><div x-show="!show" class="cc_video-overlay"><div class="cc_video-overlay_container"><div class="cc_video-overlay_text">By loading the video, you accept third party privacy policy.</div><button x-on:click="showVideo()">load video</button></div></div><div x-show="show" class="video_container" x-ref="video_container"> ' . str($iframe_tag->ownerDocument->saveXML($iframe_tag))->replace($iframe_tag->getAttribute('src'), '') . ' </div></div>');
                $div = $iframe_tag->parentNode->appendChild($dom->createElement('div'));
                $divInner = $dom->createDocumentFragment();
                $divInner->appendXML($replace);
                $div->appendChild($divInner);
                $iframe_tag->parentNode->replaceChild($div, $iframe_tag);
            }
        }

        $script_tags = $dom->getElementsByTagName('script');
        foreach ($script_tags as $script_tag) {
            $script_tag->parentNode->appendChild($dom->createElement('pre', str($script_tag->ownerDocument->saveXML($script_tag))
                ->replace('<![CDATA[', '')->replace(']]>', '')
            ));
            $script_tag->parentNode->removeChild($script_tag);
        }

        $xpath = new DOMXPath($dom);
        foreach (['onload', 'onerror', 'onclick'] as $attr) {
            $nodes = $xpath->query('//*[@' . $attr . ']');  // Find elements with a $attr attribute
            foreach ($nodes as $node) {              // Iterate over found elements
                $node->removeAttribute($attr);    // Remove $attr attribute
            }
        }

        $body = str($dom->saveHTML($dom->documentElement->lastChild))->replace('<body>', '')->replace('</body>', '')->replace('<script>', htmlspecialchars('<script>'))->replace('</script>', htmlspecialchars('</script>'))->replace('javascript:', 'javascript\:');

        return str($body)->toHtmlString();
    }
}

// Body to description
if (! function_exists('body_to_desc')) {
    function body_to_desc(string $markdown)
    {
        return str(strip_tags(str($markdown)->markdown()))->limit(160);
    }
}

// Short Number
if (! function_exists('shortNumber')) {
    function shortNumber($number = 0)
    {
        return \App\Helpers\NumberFormat::readable($number);
    }
}

// Is user under review
if (! function_exists('require_approval')) {
    function require_approval()
    {
        $user = auth()->user();
        $review_option = site_config('review_option') ?? 0;
        if ($user && $review_option) {
            if ($user->hasRole('super') || $user->can('approve-threads')) {
                return false;
            }
            if (-1 == $review_option) {
                return true;
            }

            return $user->threads()->count() <= $review_option;
        }

        return false;
    }
}

// Is Demo Enabled
if (! function_exists('random_username')) {
    function random_username($string, $ers = null)
    {
        $username = vsprintf('%s%s%d', [...sscanf(strtolower($string), '%s %2s'), random_int(0, 1000)]) . ($ers ? '_' . $ers : '');
        if (\App\Models\User::where('username', $username)->exists()) {
            random_username($string, str()->random(6));
        }

        return $username;
    }
}

// Is Demo Enabled
if (! function_exists('demo')) {
    function demo()
    {
        return env('DEMO', false);
    }
}

// Is Demo Enabled
if (! function_exists('storage_url')) {
    function storage_url($path, $disk = 'site')
    {
        return \Illuminate\Support\Facades\Storage::disk($disk)->url($path);
    }
}
