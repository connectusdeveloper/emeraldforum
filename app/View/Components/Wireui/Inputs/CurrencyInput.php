<?php

namespace App\View\Components\Wireui\Inputs;

use WireUi\View\Components\Input;

class CurrencyInput extends Input
{
    public string $decimal;
    public bool $emitFormatted;
    public int $precision;
    public string $thousands;

    public function __construct(
        string $thousands = ',',
        string $decimal = '.',
        int $precision = 2,
        bool $emitFormatted = false,
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
        ?string $append = null
    ) {
        parent::__construct($borderless, $shadowless, $label, $hint, $cornerHint, $icon, $rightIcon, $prefix, $suffix, $prepend, $append);

        $this->thousands = $thousands;
        $this->decimal = $decimal;
        $this->precision = $precision;
        $this->emitFormatted = $emitFormatted;
    }

    protected function getView(): string
    {
        return 'wireui::components.inputs.currency';
    }
}
