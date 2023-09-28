<?php

namespace App\View\Components\Wireui;

class Notifications extends Component
{
    public const BOTTOM_CENTER = 'bottom-center';

    public const BOTTOM_LEFT = 'bottom-left';

    public const BOTTOM_RIGHT = 'bottom-right';

    public const TOP_CENTER = 'top-center';

    public const TOP_LEFT = 'top-left';

    public const TOP_RIGHT = 'top-right';

    public function __construct(
        public string $zIndex = 'z-50',
        public ?string $position = self::TOP_RIGHT,
    ) {
        $this->position = $this->getPosition($position);
    }

    public function getPosition(?string $position): string
    {
        return $this->classes([
            'sm:items-start sm:justify-start'  => self::TOP_LEFT === $position,
            'sm:items-start sm:justify-center' => self::TOP_CENTER === $position,
            'sm:items-start sm:justify-end'    => self::TOP_RIGHT === $position,
            'sm:items-end sm:justify-start'    => self::BOTTOM_LEFT === $position,
            'sm:items-end sm:justify-center'   => self::BOTTOM_CENTER === $position,
            'sm:items-end sm:justify-end'      => self::BOTTOM_RIGHT === $position,
        ]);
    }

    public function render()
    {
        return view('wireui::components.notifications');
    }
}
