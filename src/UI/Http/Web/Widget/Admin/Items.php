<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Widget\Admin;

use Twig\Extension\AbstractExtension;
use Twig\Environment;
use Twig\TwigFunction;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;
use Zentlix\MenuBundle\Domain\Menu\Service\Providers;

class Items extends AbstractExtension
{
    private ItemRepository $itemRepository;
    private Providers $providers;

    public function __construct(ItemRepository $itemRepository, Providers $providers)
    {
        $this->itemRepository = $itemRepository;
        $this->providers = $providers;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('admin_menu_items', [$this, 'getItems'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function getItems(Environment $twig, Menu $menu): ?string
    {
        return $twig->render('@MenuBundle/admin/widgets/items.html.twig', [
            'items'     => $this->itemRepository->children($menu->getRootItem()),
            'menu'      => $menu,
            'providers' => $this->providers
        ]);
    }
}