<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Domain\Menu\Entity;

use Doctrine\ORM\Mapping;
use Gedmo\Mapping\Annotation;
use Zentlix\MainBundle\Domain\Shared\Entity\SortTrait;
use Zentlix\MainBundle\Domain\Shared\Entity\Eventable;
use Zentlix\MenuBundle\Application\Command\Item\CreateCommand;
use Zentlix\MenuBundle\Application\Command\Item\UpdateCommand;

/**
 * @Annotation\Tree(type="nested")
 * @Mapping\Entity(repositoryClass="Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository")
 * @Mapping\Table(name="zentlix_menu_menu_items")
 */
class Item implements Eventable
{
    use SortTrait;

    /**
     * @Mapping\Id()
     * @Mapping\GeneratedValue()
     * @Mapping\Column(type="integer")
     */
    private $id;

    /** @Mapping\Column(type="string", length=255) */
    private $title;

    /** @Mapping\Column(type="string", length=255, nullable=true) */
    private $url;

    /** @Mapping\Column(type="boolean", options={"default": 0}) */
    private $is_category;

    /** @Mapping\Column(type="integer", nullable=true) */
    private $entity_id;

    /** @Mapping\Column(type="integer", options={"default": 1}) */
    private $depth;

    /**
     * @var Menu
     * @Mapping\ManyToOne(targetEntity="Menu", inversedBy="items")
     * @Mapping\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     */
    private $menu;

    /**
     * @Annotation\TreeLeft
     * @Mapping\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Annotation\TreeLevel
     * @Mapping\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @Annotation\TreeRight
     * @Mapping\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Annotation\TreeRoot
     * @Mapping\ManyToOne(targetEntity="Item")
     * @Mapping\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Annotation\TreeParent
     * @Mapping\ManyToOne(targetEntity="Item", inversedBy="children")
     * @Mapping\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @Mapping\OneToMany(targetEntity="Item", mappedBy="parent")
     * @Mapping\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /** @Mapping\Column(type="string", length=64) */
    private $provider;

    public function __construct(CreateCommand $command)
    {
        $this->setValuesFromCommands($command);
    }

    public function update(UpdateCommand $command)
    {
        $this->setValuesFromCommands($command);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    public function isCategory(): bool
    {
        return $this->is_category;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function getRoot(): Item
    {
        return $this->root;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function asArray(): array
    {
        return [
            'title'       => $this->title,
            'url'         => $this->url,
            'sort'        => $this->sort,
            'is_category' => $this->is_category,
            'depth'       => $this->depth,
            'entity_id'   => $this->entity_id,
            'menu_id'     => $this->menu->getId(),
            'parent'      => $this->parent,
            'provider'    => $this->provider
        ];
    }

    /**
     * @param CreateCommand|UpdateCommand $command
     */
    private function setValuesFromCommands($command): void
    {
        $this->title = $command->title;
        $this->url = $command->url;
        $this->sort = $command->sort;
        $this->is_category = $command->is_category;
        $this->depth = $command->depth;
        $this->entity_id = $command->entity_id;
        $this->menu = $command->getMenu();
        $this->parent = $command->parent;
        $this->provider = $command->provider;
    }
}