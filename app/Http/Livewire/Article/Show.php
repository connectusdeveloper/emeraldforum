<?php

namespace App\Http\Livewire\Article;

use App\Models\Article;
use Livewire\Component;

class Show extends Component
{
    public Article $article;

    public function mount(?Article $article)
    {
        // if (auth()->user()->cant('read-articles')) {
        //     return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        // }
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.article.show')->layout('layouts.public');
    }
}
