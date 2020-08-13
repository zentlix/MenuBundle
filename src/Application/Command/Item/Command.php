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
use Symfony\Component\Validator\Constraints;
use Zentlix\MainBundle\Application\Command\DynamicPropertyCommand;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandInterface;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Service\MenuEntityProviderInterface;
use Zentlix\MenuBundle\Domain\Menu\Service\Providers;

class Command extends DynamicPropertyCommand implements CommandInterface
{
    /** @Constraints\NotBlank() */
    public ?string $title;

    /** @Constraints\NotBlank() */
    public ?string $provider;

    /** @var int|null|Item */
    public $parent;

    public ?string $url;
    public int $sort = 1;
    public bool $is_category = false;
    public bool $blank = false;
    public int $depth = 1;
    public ?int $entity_id;
    /** @var Providers */
    public Providers $providers;
    protected Menu $menu;
    protected Item $entity;

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    protected function setDataFromRequest(Request $request = null, Item $item = null): void
    {
        $item ? $parent = $item->getParent() : $parent = null;

        if($request) {
            $this->entity_id = (int) $request->request->get('entity_id', $item ? $item->getEntityId() : null);
            $this->provider = $request->request->get('provider', $item ? $item->getProvider() :
                $this->providers->getDefaultProvider()->getType());
            $this->sort = (int) $request->request->get('sort', $item ? $item->getSort() : 1);
            $this->url = $request->request->get('url', $item ? $item->getUrl() : null);
            $this->is_category = (bool) $request->request->get('is_category', $item ? $item->isCategory() : false);
            $this->blank = (bool) $request->request->get('blank', $item ? $item->isTargetBlank() : false);
            $this->depth = (int) $request->request->get('depth', $item ? $item->getDepth() : 1);
            $this->parent = (int) $request->request->get('parent', $parent ? $parent->getId() : null);
            $this->title = $request->request->get('title', $item ? $item->getTitle() : null);

            if((!empty($this->provider) && (int) $this->entity_id > 0) && empty($this->title)) {
                if($this->providers->getProviderByType($this->provider) instanceof MenuEntityProviderInterface) {
                    $this->title = $this->providers->getProviderByType($this->provider)->getEntityTitle($this->entity_id);
                }
            }
        }
    }

    public function getEntity(): Item
    {
        return $this->entity;
    }
}