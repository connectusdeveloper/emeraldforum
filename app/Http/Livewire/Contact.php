<?php

namespace App\Http\Livewire;

use Livewire\Component;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Mail;

class Contact extends Component
{
    use Actions;

    public $form = [];

    public function mount()
    {
        if (! (site_config('contact') ?? null)) {
            return redirect()->to(url()->previous())->with('error', __('You do not have permissions to perform this action.'));
        }
    }

    public function render()
    {
        return view('contact.form')->layout('layouts.public');
    }

    public function send()
    {
        $this->validate();
        Mail::send('contact.email', [
            'name'  => $this->form['name'],
            'email' => $this->form['email'],
            'body'  => $this->form['message'],
            'phone' => $this->form['phone'] ?? null,
        ], function ($message) {
            $settings = site_config();
            $message
                ->replyTo($this->form['email'])
                // ->from($settings['contact_email'])
                ->to($settings['contact_email'] ?? 'noreply@example.com', $settings['site_name'] ?? config('app.name'))
                ->subject($this->form['subject']);
        });
        $this->notification()->success(
            $title = __('Sent!'),
            $description = __('You message has been sent.')
        );
    }

    protected function rules()
    {
        return [
            'form.name'    => 'required|max:60',
            'form.email'   => 'required|email',
            'form.phone'   => 'nullable',
            'form.subject' => 'required',
            'form.message' => 'required',
        ];
    }
}
