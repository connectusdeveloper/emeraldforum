<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public function mount()
    {
        if (auth()->user()->cant('read-categories')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-categories')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $category = Category::findOrFail($id);
        if ($category->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('Category')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('Category')])
            );
        }
    }

    public function render()
    {
        return view('livewire.category.index', ['categories' => Category::latest()->paginate()])
            ->layoutData(['title' => __('Categories')]);
    }
}
