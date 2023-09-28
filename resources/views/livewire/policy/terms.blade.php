<x-slot name="title">
  {{ __('Terms of Service') }}
</x-slot>
<x-slot name="metaTags">
  <meta name="description" content="{{ __('Please read our terms of servce before using our forums.') }}">
</x-slot>

<div class="bg-white dark:bg-gray-900 shadow md:rounded-md mx-auto max-w-3xl sm:-mx-6 md:mx-0 px-4 sm:px-6 lg:px-8 py-6">
  <div class="prose dark:prose-invert max-w-none">
    {{ str($terms)->markdown()->toHtmlString() }}
  </div>
</div>
