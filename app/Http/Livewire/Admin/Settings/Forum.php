<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;
use WireUi\Traits\Actions;

class Forum extends Component
{
    use Actions;

    public $settings;

    protected $rules = [
        'settings.editor'           => 'required',
        'settings.review_option'    => 'nullable',
        'settings.voting'           => 'nullable',
        'settings.guest_reply'      => 'nullable',
        'settings.allow_delete'     => 'nullable',
        'settings.flag_option'      => 'nullable',
        'settings.hide_flagged'     => 'nullable',
        'settings.signature'        => 'nullable',
        'settings.registration'     => 'nullable',
        'settings.notifications'    => 'nullable',
        'settings.trending_threads' => 'nullable',
        'settings.top_members'      => 'nullable',
        'settings.banned_words'     => 'nullable',
        'settings.tags_cloud'       => 'nullable',
        'settings.tags_cloud'       => 'nullable',
        'settings.auto_load_video'  => 'nullable',
        'settings.disk'             => 'nullable|in:local,s3',
        'settings.upload_size'      => 'nullable|numeric',
    ];

    public function mount()
    {
        if (auth()->user()->cant('settings')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $settings = site_config();
        $this->settings = [
            'editor'           => $settings['editor'] ?? '',
            'review_option'    => $settings['review_option'] ?? '',
            'voting'           => $settings['voting'] ?? '',
            'guest_reply'      => $settings['guest_reply'] ?? '',
            'allow_delete'     => $settings['allow_delete'] ?? '',
            'flag_option'      => $settings['flag_option'] ?? '',
            'hide_flagged'     => $settings['hide_flagged'] ?? '',
            'signature'        => $settings['signature'] ?? '',
            'registration'     => $settings['registration'] ?? '',
            'notifications'    => $settings['notifications'] ?? '',
            'trending_threads' => $settings['trending_threads'] ?? '',
            'top_members'      => $settings['top_members'] ?? '',
            'banned_words'     => $settings['banned_words'] ?? '',
            'tags_cloud'       => $settings['tags_cloud'] ?? '',
            'auto_load_video'  => $settings['auto_load_video'] ?? '',
            'disk'             => $settings['disk'] ?? 'local',
            'upload_size'      => $settings['upload_size'] ?? '2048',
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings.forum')->layoutData(['title' => __('Application Settings')]);
    }

    public function save()
    {
        $this->validate();
        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(['tec_key' => $key], ['tec_value' => $value]);
        }
        cache()->forget('forum_settings');

        return to_route('settings.forum')->with('message', __('Settings has been successfully saved.'));
    }

    protected function validationAttributes()
    {
        return [
            'settings.editor'           => __('editor'),
            'settings.review_option'    => __('review option'),
            'settings.voting'           => __('voting'),
            'settings.guest_reply'      => __('guest reply'),
            'settings.allow_delete'     => __('allow delete'),
            'settings.flag_option'      => __('flag option'),
            'settings.hide_flagged'     => __('hide flagged'),
            'settings.signature'        => __('signature'),
            'settings.registration'     => __('registration'),
            'settings.notifications'    => __('notifications'),
            'settings.trending_threads' => __('trending threads'),
            'settings.top_members'      => __('who to follow'),
            'settings.banned_words'     => __('banned words'),
            'settings.tags_cloud'       => __('tags cloud'),
            'settings.disk'             => __('file system'),
            'settings.upload_size'      => __('upload size'),
            'settings.auto_load_video'  => __('auto load videos'),
        ];
    }
}
