<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;
use WireUi\Traits\Actions;

class Ad extends Component
{
    use Actions;

    public $settings;
    protected $rules = [
        'settings.top_ad_code'     => 'nullable|string',
        'settings.sidebar_ad_code' => 'nullable|string',
        'settings.bottom_ad_code'  => 'nullable|string',
        'settings.footer_code'     => 'nullable|string',
    ];

    public function mount()
    {
        if (auth()->user()->cant('settings')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $settings = site_config();
        $this->settings = [
            'top_ad_code'     => $settings['top_ad_code'] ?? '',
            'sidebar_ad_code' => $settings['sidebar_ad_code'] ?? '',
            'bottom_ad_code'  => $settings['bottom_ad_code'] ?? '',
            'footer_code'     => $settings['footer_code'] ?? '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings.ad')->layoutData(['title' => __('Application Settings')]);
    }

    public function save()
    {
        $this->validate();
        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(['tec_key' => $key], ['tec_value' => $value]);
        }
        cache()->forget('forum_settings');
        return to_route('settings.ad')->with('message', __('Settings has been successfully saved.'));
    }

    protected function validationAttributes()
    {
        return [
            'settings.top_ad_code'     => __('top ad code'),
            'settings.sidebar_ad_code' => __('sidebar ad code'),
            'settings.bottom_ad_code'  => __('bottom ad code'),
            'settings.footer_code'     => __('footer code'),
        ];
    }
}
