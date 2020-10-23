<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Application\Command\Item\Url;

use Zentlix\MainBundle\Infrastructure\Share\Bus\CreateCommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Service\UrlMenuProvider;

class CreateCommand extends Command implements CreateCommandInterface
{
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
        $this->provider = UrlMenuProvider::getType();
    }
}