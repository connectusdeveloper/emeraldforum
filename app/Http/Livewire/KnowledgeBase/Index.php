<?php

namespace App\Http\Livewire\KnowledgeBase;

use WireUi\Traits\Actions;
use App\Models\KnowledgeBase;
use Livewire\{Component, WithPagination};

class Index extends Component
{
    use Actions;
    use WithPagination;

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
        $faq = KnowledgeBase::findOrFail($id);
        if ($faq->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('Knowledge Base')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('Knowledge Base')])
            );
        }
    }

    public function render()
    {
        return view('livewire.knowledgebase.index', ['knowledge_base' => KnowledgeBase::latest()->paginate()]);
    }
}
