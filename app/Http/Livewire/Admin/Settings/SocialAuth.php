<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;
use WireUi\Traits\Actions;

class SocialAuth extends Component
{
    use Actions;

    public $settings;
    protected $rules = [
        'settings.facebook_login'         => 'nullable|boolean',
        'settings.github_login'           => 'nullable|boolean',
        'settings.google_login'           => 'nullable|boolean',
        'settings.twitter_login'          => 'nullable|boolean',
        'settings.facebook_client_id'     => 'required_if:settings.facebook_login,1',
        'settings.facebook_client_secret' => 'required_if:settings.facebook_login,1',
        'settings.github_client_id'       => 'required_if:settings.github_login,1',
        'settings.github_client_secret'   => 'required_if:settings.github_login,1',
        'settings.google_client_id'       => 'required_if:settings.google_login,1',
        'settings.google_client_secret'   => 'required_if:settings.google_login,1',
        'settings.twitter_client_id'      => 'required_if:settings.twitter_login,1',
        'settings.twitter_client_secret'  => 'required_if:settings.twitter_login,1',
    ];

    public function mount()
    {
        if (auth()->user()->cant('settings')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $this->settings = site_config();
        $this->settings['github_login'] = 1 == ($this->settings['github_login'] ?? null);
        $this->settings['google_login'] = 1 == ($this->settings['google_login'] ?? null);
        $this->settings['twitter_login'] = 1 == ($this->settings['twitter_login'] ?? null);
        $this->settings['facebook_login'] = 1 == ($this->settings['facebook_login'] ?? null);

        $settings = site_config();
        $this->settings = [
            'facebook_login'         => 1 == ($settings['facebook_login'] ?? null),
            'github_login'           => 1 == ($settings['github_login'] ?? null),
            'google_login'           => 1 == ($settings['google_login'] ?? null),
            'twitter_login'          => 1 == ($settings['twitter_login'] ?? null),
            'facebook_client_id'     => $settings['facebook_client_id'] ?? '',
            'facebook_client_secret' => $settings['facebook_client_secret'] ?? '',
            'github_client_id'       => $settings['github_client_id'] ?? '',
            'github_client_secret'   => $settings['github_client_secret'] ?? '',
            'google_client_id'       => $settings['google_client_id'] ?? '',
            'google_client_secret'   => $settings['google_client_secret'] ?? '',
            'twitter_client_id'      => $settings['twitter_client_id'] ?? '',
            'twitter_client_secret'  => $settings['twitter_client_secret'] ?? '',
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings.social-auth')->layoutData(['title' => __('Application Settings')]);
    }

    public function save()
    {
        $this->validate();
        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(['tec_key' => $key], ['tec_value' => $value]);
        }
        cache()->forget('forum_settings');
        return to_route('settings.social.auth')->with('message', __('Settings has been successfully saved.'));
    }

    protected function validationAttributes()
    {
        return [
            'settings.facebook_login'         => __('facebook auth'),
            'settings.github_login'           => __('github auth'),
            'settings.google_login'           => __('google auth'),
            'settings.twitter_login'          => __('twitter auth'),
            'settings.facebook_client_id'     => __('client id'),
            'settings.facebook_client_secret' => __('client secret'),
            'settings.github_client_id'       => __('client id'),
            'settings.github_client_secret'   => __('client secret'),
            'settings.google_client_id'       => __('client id'),
            'settings.google_client_secret'   => __('client secret'),
            'settings.twitter_client_id'      => __('client id'),
            'settings.twitter_client_secret'  => __('client secret'),
        ];
    }
}
