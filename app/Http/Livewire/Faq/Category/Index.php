<?php

namespace App\Http\Livewire\Faq\Category;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\FaqCategory;

class Index extends Component
{
    use Actions;

    public function mount()
    {
        if (auth()->user()->cant('read-faqs')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-faqs')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $faq_category = FaqCategory::findOrFail($id);
        if ($faq_category->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('FAQ Category')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('FAQ Category')])
            );
        }
    }

    public function render()
    {
        return view('livewire.faq.category.index', ['faq_categories' => FaqCategory::latest()->paginate()])
            ->layoutData(['title' => __('FAQ Categories')]);
    }
}
