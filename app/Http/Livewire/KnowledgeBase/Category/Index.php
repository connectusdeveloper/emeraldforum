<?php

namespace App\Http\Livewire\KnowledgeBase\Category;

use Livewire\Component;
use App\Models\KBCategory;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public function mount()
    {
        if (auth()->user()->cant('read-knowledgebase')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-knowledgebase')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $faq_category = KBCategory::findOrFail($id);
        if ($faq_category->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('Knowledge Base Category')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('Knowledge Base Category')])
            );
        }
    }

    public function render()
    {
        return view('livewire.knowledgebase.category.index', ['k_b_categories' => KBCategory::latest()->paginate()])
            ->layoutData(['title' => __('Knowledge base categories')]);
    }
}
