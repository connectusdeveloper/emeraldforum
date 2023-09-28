<?php

namespace App\View\Components\Wireui;

use Illuminate\Support\{Str, Stringable};

class Input extends FormComponent
{
    public ?string $append;
    public bool $borderless;
    public ?string $cornerHint;
    public bool $errorless;
    public ?string $hint;
    public ?string $icon;
    public ?string $label;
    public ?string $prefix;
    public ?string $prepend;
    public ?string $rightIcon;
    public bool $shadowless;
    public ?string $suffix;

    public function __construct(
        bool $borderless = false,
        bool $shadowless = false,
        ?string $label = null,
        ?string $hint = null,
        ?string $cornerHint = null,
        ?string $icon = null,
        ?string $rightIcon = null,
        ?string $prefix = null,
        ?string $suffix = null,
        ?string $prepend = null,
        ?string $append = null,
        bool $errorless = false
    ) {
        $this->borderless = $borderless;
        $this->shadowless = $shadowless;
        $this->label = $label;
        $this->hint = $hint;
        $this->cornerHint = $cornerHint;
        $this->icon = $icon;
        $this->rightIcon = $rightIcon;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->prepend = $prepend;
        $this->append = $append;
        $this->errorless = $errorless;
    }

    public function getInputClasses(bool $hasError = false): string
    {
        $defaultClasses = $this->getDefaultClasses();

        if ($this->prefix || $this->icon) {
            $defaultClasses .= ' pl-8';
        }

        if ($hasError || $this->suffix || $this->rightIcon) {
            $defaultClasses .= ' pr-8';
        }

        if ($hasError) {
            return "{$this->getErrorClasses()} {$defaultClasses}";
        }

        return "{$this->getDefaultColorClasses()} {$defaultClasses}";
    }

    protected function getDefaultClasses(): string
    {
        return Str::of('form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none')
            ->unless($this->shadowless, fn (Stringable $stringable) => $stringable->append(' shadow-sm'))
            ->when($this->borderless, function (Stringable $stringable) {
                return $stringable->append(' border-transparent focus:border-transparent focus:ring-transparent');
            });
    }

    protected function getDefaultColorClasses(): string
    {
        return Str::of('placeholder-gray-400 dark:bg-gray-800')
            ->append(' dark:placeholder-gray-500')
            ->unless($this->borderless, function (Stringable $stringable) {
                return $stringable
                    ->append(' border border-gray-300 dark:border-gray-700 focus:ring-primary-500 focus:border-primary-500')
                    ->append(' dark:border-gray-600');
            });
    }

    protected function getErrorClasses(): string
    {
        return Str::of('text-negative-900 dark:text-negative-600 placeholder-negative-300 dark:placeholder-negative-500')
            ->unless($this->borderless, function (Stringable $stringable) {
                return $stringable
                    ->append(' border border-negative-300 focus:ring-negative-500 focus:border-negative-500')
                    ->append(' dark:bg-gray-800 dark:border-negative-600');
            });
    }

    protected function getView(): string
    {
        return 'wireui::components.input';
    }
}
