<?php

namespace App\View\Components\Wireui\Inputs;

class PhoneInput extends BaseMaskable
{
    protected function getInputMask(): string
    {
        return "['(###) ###-####', '+# ### ###-####', '+## ## ####-####']";
    }
}
