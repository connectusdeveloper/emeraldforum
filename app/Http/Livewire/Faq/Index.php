<?php

namespace App\Http\Livewire\Faq;

use App\Models\Faq;
use WireUi\Traits\Actions;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use Actions;
    use WithPagination;

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
        $faq = Faq::findOrFail($id);
        if ($faq->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('FAQ')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('FAQ')])
            );
        }
    }

    public function render()
    {
        return view('livewire.faq.index', ['faqs' => Faq::latest()->paginate()]);
    }
}
