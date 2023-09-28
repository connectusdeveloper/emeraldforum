<?php

namespace App\Http\Livewire\CustomField;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\CustomField;

class Index extends Component
{
    use Actions;

    public function mount()
    {
        if (auth()->user()->cant('read-custom-fields')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function removeRecord($id)
    {
        if (auth()->user()->cant('delete-custom-fields')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
        $custom_field = CustomField::findOrFail($id);
        if ($custom_field->delete()) {
            $this->notification()->success(
                $title = __('Success!'),
                $description = __(':record has been deleted.', ['record' => _('CustomField')])
            );
        } else {
            $this->notification()->error(
                $title = __('Failed!'),
                $description = __('Failed to delete :record.', ['record' => _('CustomField')])
            );
        }
    }

    public function render()
    {
        return view('livewire.custom_field.index', ['custom_fields' => CustomField::latest()->paginate()])
            ->layoutData(['title' => __('Custom Fields')]);
    }
}
