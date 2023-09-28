<?php

namespace App\Http\Livewire\Article;

use App\Models\Article;
use Livewire\Component;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public function mount()
    {
        if (auth()->user()->cant('read-articles')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-articles')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $article = Article::findOrFail($id);
        if ($article->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('Article')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('Article')])
            );
        }
    }

    public function render()
    {
        return view('livewire.article.index', ['articles' => Article::latest()->paginate()]);
    }
}
