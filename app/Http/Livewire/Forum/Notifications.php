<?php

namespace App\Http\Livewire\Forum;

use WireUi\Traits\Actions;
use Livewire\{Component, WithPagination};

class Notifications extends Component
{
    use Actions;
    use WithPagination;

    public $showUnread = false;
    public $unreadCount = 0;

    public function mount()
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function remove($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        $notification?->delete();
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
        $this->notification()->success(
            $title = __('Success!'),
            $description = __('Notification has been successfully deleted.')
        );
    }

    public function removeAll()
    {
        $this->unreadCount = 0;
        auth()->user()->notifications()->delete();
        return to_route('notifications')->with('message', __('Notification has been successfully deleted.'));
    }

    public function render()
    {
        $user = auth()->user();
        $this->emit('page-changed');
        cache()->forget('user_notifications');
        return view('livewire.forum.notifications', [
            'unread_count'  => $user->unreadNotifications()->count(),
            'notifications' => $user->{$this->showUnread ? 'unreadNotifications' : 'notifications'}()->latest()->paginate(20),
        ])->layout('layouts.public');
    }

    public function unread($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        $notification?->markAsUnread();
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
        $this->notification()->success(
            $title = __('Success!'),
            $description = __('Notification has been successfully marked as unread.')
        );
    }

    public function update($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        $notification?->markAsRead();
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
        $this->notification()->success(
            $title = __('Success!'),
            $description = __('Notification has been successfully marked as read.')
        );
    }

    public function updateAll()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return to_route('notifications')->with('message', __('Notifications has been successfully marked as read.'));
    }

    public function view($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        $notification?->markAsRead();
        return redirect()->to($notification->data['link']);
    }
}
