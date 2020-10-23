<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Domain\Menu\Service;

use Zentlix\MenuBundle\Domain\Menu\Entity\Menu as MenuEntity;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;
use Zentlix\MenuBundle\Domain\Menu\Repository\MenuRepository;
use function is_null;

class Menu
{
    private Providers $providers;
    private MenuRepository $menuRepository;
    private ItemRepository $itemRepository;

    public function __construct(Providers $providers,
                                MenuRepository $menuRepository,
                                ItemRepository $itemRepository)
    {
        $this->providers = $providers;
        $this->menuRepository = $menuRepository;
        $this->itemRepository = $itemRepository;
    }

    public function getMenuTree(string $code): ?array
    {
        $menu = $this->getCachedMenu($code);

        if(is_null($menu)) {
            return null;
        }

        $result = $this->itemRepository->getMenuTree($menu->getRootItem()->getId());
        $build = function ($tree) use (&$build) {
            foreach ($tree as $key => $node) {
                $tree[$key]['url'] = $this->providers->getProviderByType($node['provider'])->getUrl($node);
                if (count($node['__children']) > 0) {
                    $tree[$key]['__children'] = $build($node['__children']);
                }
            }

            return $tree;
        };

        return $build($this->itemRepository->buildTree($result));
    }

    public function getCachedMenu(string $code): ?MenuEntity
    {
        $menu = Cache::getMenu($code);

        if(is_null($menu)) {
            $menu = $this->menuRepository->findOneByCode($code);
        }

        if(is_null($menu)) {
            return null;
        }

        Cache::setMenu($code, $menu);

        return $menu;
    }
}