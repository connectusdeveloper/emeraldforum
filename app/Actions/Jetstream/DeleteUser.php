<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    public function delete($user)
    {
        if (demo()) {
            return redirect()->to(url()->previous())->with('error', __('This feature is disabled on demo.'));
        }

        $user->votes()->delete();
        $user->badges()->detach();
        $user->deleteProfilePhoto();
        $user->userMeta()->delete();
        $user->favorites()->detach();
        $user->tokens->each->delete();
        $user->replies->each->delete();
        $user->threads->each->delete();
        $user->delete();
    }
}
