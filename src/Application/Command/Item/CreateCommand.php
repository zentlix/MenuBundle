<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Application\Command\Item;

use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\Application\Command\CreateCommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Service\Providers;

class CreateCommand extends Command implements CreateCommandInterface
{
    public function __construct(Menu $menu, Providers $providers, Request $request = null)
    {
        $this->menu = $menu;
        $this->providers = $providers;

        $this->setDataFromRequest($request);
    }
}