<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;
use WireUi\Traits\Actions;

class SocialLinks extends Component
{
    use Actions;

    public $settings;
    protected $rules = [
        'settings.facebook_link'  => 'nullable|string',
        'settings.github_link'    => 'nullable|string',
        'settings.twitter_link'   => 'nullable|string',
        'settings.instagram_link' => 'nullable|string',
        'settings.linkedin_link'  => 'nullable|string',
        'settings.dribbble_link'  => 'nullable|string',
    ];

    public function mount()
    {
        if (auth()->user()->cant('settings')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $settings = site_config();
        $this->settings = [
            'facebook_link'  => $settings['facebook_link'] ?? '',
            'github_link'    => $settings['github_link'] ?? '',
            'twitter_link'   => $settings['twitter_link'] ?? '',
            'instagram_link' => $settings['instagram_link'] ?? '',
            'linkedin_link'  => $settings['linkedin_link'] ?? '',
            'dribbble_link'  => $settings['dribbble_link'] ?? '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings.social-links')->layoutData(['title' => __('Application Settings')]);
    }

    public function save()
    {
        $this->validate();
        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(['tec_key' => $key], ['tec_value' => $value]);
        }
        cache()->forget('forum_settings');
        return to_route('settings.social.links')->with('message', __('Settings has been successfully saved.'));
    }

    protected function validationAttributes()
    {
        return [
            'settings.facebook_link'  => __('facebook link'),
            'settings.github_link'    => __('github link'),
            'settings.twitter_link'   => __('twitter link'),
            'settings.instagram_link' => __('instagram link'),
            'settings.linkedin_link'  => __('linkedin link'),
            'settings.dribbble_link'  => __('dribbble link'),
        ];
    }
}
