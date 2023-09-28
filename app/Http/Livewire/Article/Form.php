<?php

namespace App\Http\Livewire\Article;

use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Storage;
use Livewire\{Component, WithFileUploads};
use App\Models\{Article, CustomField, Role};

class Form extends Component
{
    use Actions;
    use WithFileUploads;

    public Article $article;
    public $custom_fields;
    public $extra_attributes;
    public $image;
    public $settings;

    public function deleteImage()
    {
        try {
            Storage::disk(env('ATTACHMENT_DISK', 'site'))->delete($this->article->image);
        } catch(\Exception $e) {
            logger('Failed to delete article image.', ['error' => $e->getMessage()]);
        }
        $this->article->image = null;
        $this->notification()->success(
            $title = __('Delete!'),
            $description = __('Image has been deleted.')
        );
    }

    public function mount(?Article $article)
    {
        $this->article = $article;
        $this->settings = site_config();
        if (!$this->article->id) {
            if (auth()->user()->cant('create-articles')) {
                return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
            }
            $this->article->active = true;
        } elseif (auth()->user()->cant('update-articles')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $this->custom_fields = CustomField::ofModel('Article')->get();
        $this->extra_attributes = $this->article->extra_attributes->toArray();
        foreach ($this->custom_fields as $custom_field) {
            $this->extra_attributes[$custom_field->name] ??= null;
        }
    }

    public function render()
    {
        return view('livewire.article.form', ['roles' => Role::all()])
            ->layoutData(['title' => ($this->article ?? null) ? __('Edit Article') : __('New Article')]);
    }

    public function save()
    {
        $this->validate();
        if ($this->image) {
            $disk = env('ATTACHMENT_DISK', 'site');
            $this->article->image = Storage::disk($disk)->url(
                'site' == $disk ?
                $this->image->store('articles/' . auth()->id(), 'site') :
                $this->image->storePublicly('articles/' . auth()->id(), 's3')
            );
        }
        if (auth()->user()->cant('group-permissions')) {
            $this->article->group = null;
        }
        $this->article->extra_attributes->set($this->extra_attributes);
        $this->article->save();
        return to_route('articles')->with('message', __('Article has been successfully saved.'));
    }

    protected function rules()
    {
        return [
            'image' => 'nullable|max:' . ($this->settings['upload_size'] ?? '2048') . '|mimes:jpg,jpeg,png,svg',

            'article.title'       => 'required|min:5|max:60',
            'article.slug'        => 'nullable|alpha_dash|max:60|unique:articles,slug,' . $this->article->id,
            'article.description' => 'nullable|string|max:160',
            'article.body'        => 'required|min:20',
            'article.order_no'    => 'nullable|integer',
            'article.active'      => 'nullable|boolean',
            'article.noindex'     => 'nullable|boolean',
            'article.nofollow'    => 'nullable|boolean',
            'article.group'       => 'nullable',
            'extra_attributes.*'  => [function ($attribute, $value, $fail) {
                $attribute = explode('[', explode('.', $attribute)[1])[0];
                $field = $this->custom_fields->where('name', $attribute)->first();
                if ($field?->required && empty($value)) {
                    $fail(__('validation.required', ['attribute' => str($attribute)->lower()]));
                }
            }],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'article.title'       => __('title'),
            'article.slug'        => __('slug'),
            'article.description' => __('description'),
            'article.body'        => __('body'),
            'article.order_no'    => __('order no'),
            'article.active'      => __('active'),
            'article.noindex'     => __('noindex'),
            'article.nofollow'    => __('nofollow'),
        ];
    }
}
