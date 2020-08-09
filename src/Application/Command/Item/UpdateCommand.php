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
use Zentlix\MainBundle\Application\Command\UpdateCommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Service\Providers;

class UpdateCommand extends Command implements UpdateCommandInterface
{
    public function __construct(Item $item, Providers $providers, Request $request)
    {
        $this->entity = $item;
        $this->menu = $item->getMenu();
        $this->providers = $providers;

        $this->setDataFromRequest($request, $item);
    }
}