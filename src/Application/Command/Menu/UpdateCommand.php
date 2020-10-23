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

use Zentlix\MainBundle\Infrastructure\Share\Bus\UpdateCommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    public function __construct(Menu $menu)
    {
        $this->entity      = $menu;
        $this->title       = $menu->getTitle();
        $this->description = $menu->getDescription();
        $this->code        = $menu->getCode();
    }
}