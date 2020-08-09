<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Application\Command\Menu;

use Zentlix\MainBundle\Application\Command\UpdateCommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Service\Menu as MenuService;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    public array $items = [];

    public function __construct(Menu $menu, MenuService $menuService)
    {
        $this->entity = $menu;
        $this->title = $menu->getTitle();
        $this->description = $menu->getDescription();
        $this->code = $menu->getCode();
        $this->items = $menuService->getMenuItemsForDataTable($menu);
    }
}