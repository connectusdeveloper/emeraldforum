<?php

namespace App\Http\Livewire\Article;

use App\Models\{Article, KBCategory};
use Livewire\{Component, WithPagination};

class PublicList extends Component
{
    use WithPagination;

    public function mount()
    {
        if (!(site_config('articles') ?? null)) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function render()
    {
        return view('livewire.article.list', ['articles' => Article::active()->forUser()->latest()->paginate()])->layout('layouts.public', ['title' => __('All Articles')]);
    }
}
