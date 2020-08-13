<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Widget;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Zentlix\MenuBundle\Domain\Menu\Service\Menu;

class MenuTitle extends AbstractExtension
{
    private Menu $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('menuTitleWidget', [$this, 'getMenuTitle'], ['needs_environment' => false, 'is_safe' => ['html']]),
        ];
    }

    public function getMenuTitle(string $code): ?string
    {
        $menu = $this->menu->getCachedMenu($code);

        if(is_null($menu)) {
            return null;
        }

        return $menu->getTitle();
    }
}