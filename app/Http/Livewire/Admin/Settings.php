<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use WireUi\Traits\Actions;
use Spatie\Sitemap\SitemapGenerator;
use Livewire\{Component, WithFileUploads};
use Illuminate\Support\Facades\{File, Storage};

class Settings extends Component
{
    use Actions;
    use WithFileUploads;

    public $icon;
    public $languages;
    public $logo;
    public $settings;
    protected $rules = [
        'icon' => 'nullable|image|max:200|dimensions:min_width=24,max_width=250,ratio=1/1',
        'logo' => 'nullable|image|max:1000|dimensions:max_width=600,max_height=150',

        'settings'                        => 'required|array',
        'settings.*'                      => 'nullable',
        'settings.facebook_client_id'     => 'required_if:settings.facebook_login,1',
        'settings.facebook_client_secret' => 'required_if:settings.facebook_login,1',
        'settings.github_client_id'       => 'required_if:settings.github_login,1',
        'settings.github_client_secret'   => 'required_if:settings.github_login,1',
        'settings.google_client_id'       => 'required_if:settings.google_login,1',
        'settings.google_client_secret'   => 'required_if:settings.google_login,1',
        'settings.twitter_client_id'      => 'required_if:settings.twitter_login,1',
        'settings.twitter_client_secret'  => 'required_if:settings.twitter_login,1',
    ];

    public function deleteIcon()
    {
        Setting::updateOrCreate(['tec_key' => 'icon'], ['tec_value' => null]);
        Storage::disk('site')->delete($this->settings['icon']);
        $this->settings['icon'] = null;
        cache()->forget('forum_settings');
        $this->notification()->success(
            $title = __('Delete!'),
            $description = __('Icon has been deleted.')
        );
    }

    public function deleteLogo()
    {
        Setting::updateOrCreate(['tec_key' => 'logo'], ['tec_value' => null]);
        Storage::disk('site')->delete($this->settings['logo']);
        $this->settings['logo'] = null;
        cache()->forget('forum_settings');
        $this->notification()->success(
            $title = __('Delete!'),
            $description = __('Logo has been deleted.')
        );
    }

    public function mount()
    {
        if (auth()->user()->cant('settings')) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }

        $this->settings = site_config();
        $this->settings['faqs'] = 1 == $this->settings['faqs'];
        $this->settings['articles'] = 1 == $this->settings['articles'];
        $this->settings['knowledgebase'] = 1 == $this->settings['knowledgebase'];

        $this->settings['contact'] = 1 == ($this->settings['contact'] ?? null);
        $this->settings['github_login'] = 1 == ($this->settings['github_login'] ?? null);
        $this->settings['google_login'] = 1 == ($this->settings['google_login'] ?? null);
        $this->settings['twitter_login'] = 1 == ($this->settings['twitter_login'] ?? null);
        $this->settings['facebook_login'] = 1 == ($this->settings['facebook_login'] ?? null);

        $langFiles = json_decode(file_get_contents(lang_path('languages.json')), true);
        $this->languages = $langFiles['available'];
    }

    public function render()
    {
        return view('livewire.admin.settings')->layoutData(['title' => __('Application Settings')]);
    }

    public function save()
    {
        $this->validate();
        if ($this->logo) {
            $this->settings['logo'] = $this->logo->store('images', 'site');
        }
        if ($this->icon) {
            $this->settings['icon'] = $this->icon->store('images', 'site');
        }
        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(['tec_key' => $key], ['tec_value' => $value]);
        }
        cache()->forget('forum_settings');
        return to_route('settings')->with('message', __('Settings has been successfully saved.'));
    }

    public function sitemap()
    {
        SitemapGenerator::create(url('/'))->writeToFile(public_path('sitemap.xml'));
        $this->emitSelf('sitemapGenerated');
        $this->dispatchBrowserEvent('sitemap-completed');
        $this->notification()->success(
            $title = __('Saved!'),
            $description = __('Sitemap has been successfully saved.')
        );
    }
}
