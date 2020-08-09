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

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu as MenuEntity;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;
use Zentlix\MenuBundle\Domain\Menu\Repository\MenuRepository;

class Menu
{
    private UrlGeneratorInterface $router;
    private TranslatorInterface $translator;
    private Providers $providers;
    private MenuRepository $menuRepository;
    private ItemRepository $itemRepository;

    public function __construct(UrlGeneratorInterface $router,
                                TranslatorInterface $translator,
                                Providers $providers,
                                MenuRepository $menuRepository,
                                ItemRepository $itemRepository)
    {
        $this->router = $router;
        $this->translator = $translator;
        $this->providers = $providers;
        $this->menuRepository = $menuRepository;
        $this->itemRepository = $itemRepository;
    }

    public function getMenuItemsForDataTable(MenuEntity $menu)
    {
        $result = [];

        /** @var Item $item */
        foreach ($menu->getItems()->getValues() as $item) {
            $result[] = [
                'id' => $item->getId(),
                'title' => sprintf('<a href="%s">%s</a>', $this->router->generate('admin.menu_item.update', ['id' => $item->getId()]), $item->getTitle()),
                'url' => $this->providers->getProviderByType($item->getProvider())->getUrl($item->asArray()),
                'parent' => $item->getParent() !== null ?$item->getParent()->getTitle() : $this->translator->trans('zentlix_main.no'),
                'sort' => $item->getSort()
            ];
        }

        return $result;
    }

    public function getMenuTree(string $code): ?array
    {
        $menu = $this->menuRepository->findOneByCode($code);

        if(is_null($menu)) {
            return null;
        }

        $result = $this->itemRepository->getMenuTree($menu->getId());

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
}