<?php

use App\Http\{Controllers, Livewire};
use Illuminate\Support\Facades\{Artisan, Route};

Route::middleware('installed')->group(function () {
    // Run migrations
    Route::get('/run/migration', function () {
        Artisan::call('migrate --force');
        return view('message')->with('message', Artisan::output());
    })->middleware(['throttle:commands']);

    Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
        Route::middleware('verified')->group(function () {
            Route::get('/threads/new', Livewire\Forum\Form::class)->name('threads.create');
            Route::get('/threads/{thread}/edit', Livewire\Forum\Form::class)->name('threads.edit');

            Route::post('/upload', [Controllers\UploadController::class, '__invoke'])->name('upload');
            Route::prefix('/search')->group(function () {
                Route::get('/users', [Controllers\SearchController::class, 'users'])->name('search.users');
            });

            Route::get('/badges', Livewire\Badge\Index::class)->name('badges');
            Route::get('/badges/create', Livewire\Badge\Form::class)->name('badges.create');
            Route::get('/badges/{badge}/edit', Livewire\Badge\Form::class)->name('badges.edit');

            Route::get('/categories', Livewire\Category\Index::class)->name('categories');
            Route::get('/categories/create', Livewire\Category\Form::class)->name('categories.create');
            Route::get('/categories/{category}/edit', Livewire\Category\Form::class)->name('categories.edit');

            Route::get('/faqs/manage', Livewire\Faq\Index::class)->name('faqs');
            Route::get('/faqs/create', Livewire\Faq\Form::class)->name('faqs.create');
            Route::get('/faqs/{faq}/edit', Livewire\Faq\Form::class)->name('faqs.edit');
            Route::get('/faq/categories', Livewire\Faq\Category\Index::class)->name('faq.categories');
            Route::get('/faq/categories/create', Livewire\Faq\Category\Form::class)->name('faq.categories.create');
            Route::get('/faq/categories/{faq_category}/edit', Livewire\Faq\Category\Form::class)->name('faq.categories.edit');

            Route::get('/knowledgebase/manage', Livewire\KnowledgeBase\Index::class)->name('knowledgebase');
            Route::get('/knowledgebase/create', Livewire\KnowledgeBase\Form::class)->name('knowledgebase.create');
            Route::get('/knowledgebase/{knowledgebase}/edit', Livewire\KnowledgeBase\Form::class)->name('knowledgebase.edit');
            Route::get('/knowledgebase/categories', Livewire\KnowledgeBase\Category\Index::class)->name('knowledgebase.categories');
            Route::get('/knowledgebase/categories/create', Livewire\KnowledgeBase\Category\Form::class)->name('knowledgebase.categories.create');
            Route::get('/knowledgebase/categories/{k_b_category}/edit', Livewire\KnowledgeBase\Category\Form::class)->name('knowledgebase.categories.edit');

            Route::get('/articles/manage', Livewire\Article\Index::class)->name('articles');
            Route::get('/articles/create', Livewire\Article\Form::class)->name('articles.create');
            Route::get('/articles/{article}/edit', Livewire\Article\Form::class)->name('articles.edit');

            Route::get('/custom_fields', Livewire\CustomField\Index::class)->name('custom_fields');
            Route::get('/custom_fields/create', Livewire\CustomField\Form::class)->name('custom_fields.create');
            Route::get('/custom_fields/{custom_field}/edit', Livewire\CustomField\Form::class)->name('custom_fields.edit');

            Route::get('/users', Livewire\User\Index::class)->name('users');
            Route::get('/users/create', Livewire\User\Form::class)->name('users.create');
            Route::get('/extra', Livewire\User\ExtraProfile::class)->name('extra.profile');
            Route::get('/users/{user}/edit', Livewire\User\Form::class)->name('users.edit');

            Route::get('/roles', Livewire\Role\Index::class)->name('roles');
            Route::get('/roles/create', Livewire\Role\Form::class)->name('roles.create');
            Route::get('/roles/{role}/edit', Livewire\Role\Form::class)->name('roles.edit');

            // Route::get('/settings', Livewire\Admin\Settings::class)->name('settings');
            Route::get('/policies/edit', Livewire\Policy\Edit::class)->name('policies.edit');
            Route::get('/settings/ad', Livewire\Admin\Settings\Ad::class)->name('settings.ad');
            Route::get('/settings/forum', Livewire\Admin\Settings\Forum::class)->name('settings.forum');
            Route::get('/settings/general', Livewire\Admin\Settings\Index::class)->name('settings.general');
            Route::get('/settings/social_auth', Livewire\Admin\Settings\SocialAuth::class)->name('settings.social.auth');
            Route::get('/settings/social_links', Livewire\Admin\Settings\SocialLinks::class)->name('settings.social.links');

            Route::get('/invitations', Livewire\Invitation\Index::class)->name('invitations');
            Route::get('/notifications', Livewire\Forum\Notifications::class)->name('notifications');
            Route::get('/conversations/{conversation?}', Livewire\Forum\Conversations::class)->name('conversations');
            Route::post('/conversations/{conversation}/messages', Controllers\MessageController::class)->name('conversations.show');
        });

        Route::post('/logout', Controllers\LogoutController::class)->name('logout');
    });

    Route::post('/search', [Controllers\SearchController::class, 'search'])->name('search');
    Route::post('/search/tags', [Controllers\SearchController::class, 'tags'])->name('search.tags');

    Route::get('/faq/{faq:slug?}/show', Livewire\Faq\Show::class)->name('faqs.show');
    Route::get('/faqs/{faq_category:slug?}', Livewire\Faq\PublicList::class)->name('faqs.list');

    Route::get('/knowledgebase', Livewire\KnowledgeBase\PublicList::class)->name('knowledgebase.list');
    Route::get('/knowledgebase/{knowledge_base:slug?}/show', Livewire\KnowledgeBase\Show::class)->name('knowledgebase.show');
    Route::get('/knowledgebase/{k_b_category:slug?}', Livewire\KnowledgeBase\CategoryList::class)->name('knowledgebase.category');

    Route::get('/articles', Livewire\Article\PublicList::class)->name('articles.list');
    Route::get('/articles/{article:slug}', Livewire\Article\Show::class)->name('articles.show');
    Route::get('/categories/{category:slug}', Livewire\Category\Show::class)->name('categories.show');

    Route::get('/verify/guest/reply/{reply}', [Controllers\ReplyVerificationController::class, '__invoke'])->name('verify.reply');

    Route::get('/contact', Livewire\Contact::class)->name('contact');
    Route::get('/members', Livewire\Forum\Members::class)->name('members');
    Route::get('/register', Controllers\RegisterController::class)->middleware('guest')->name('register');

    Route::get('/followers/{user:username?}', Livewire\Forum\Followers::class)->name('followers');
    Route::get('/followings/{user:username?}', Livewire\Forum\Followings::class)->name('followings');

    Route::get('/invitations/{invitation}/{code}', Livewire\Invitation\Accept::class)->name('invitations.accept')->middleware('signed');
    Route::get('/auth/{provider}', [Controllers\SocialiteController::class, 'redirect'])->name('social.login');
    Route::get('/auth/{provider}/callback', [Controllers\SocialiteController::class, 'login'])->name('social.callback');

    Route::get('/privacy-policy', Livewire\Policy\Policy::class)->name('policy.show');
    Route::get('/terms-of-service', Livewire\Policy\Terms::class)->name('terms.show');
    Route::get('/users/{user:username}', Livewire\User\Show::class)->name('users.show');
    Route::get('/threads/{thread:slug}', Livewire\Forum\Thread::class)->name('threads.show');
    Route::get('/{category:slug?}', [Livewire\Forum\Home::class, '__invoke'])->name('threads');
});
