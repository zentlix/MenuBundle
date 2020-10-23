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
use Zentlix\MenuBundle\Infrastructure\Item\Bus\CreateCommandInterface;
use Zentlix\MenuBundle\Infrastructure\Item\Bus\UpdateCommandInterface;

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

    /** @Mapping\Column(type="boolean", options={"default": 0}) */
    private $blank;

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

    /** @Mapping\OneToOne(targetEntity="Zentlix\MenuBundle\Domain\Menu\Entity\Menu", mappedBy="root_item") */
    private $menu;

    public function __construct(CreateCommandInterface $command)
    {
        $this->provider = $command->getProvider();

        $this->setValuesFromCommands($command);
    }

    public function update(UpdateCommandInterface $command)
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

    public function getRoot(): Item
    {
        return $this->root;
    }

    public function getParent(): self
    {
        return $this->parent;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function isTargetBlank(): bool
    {
        return $this->blank;
    }

    public function getChildrens()
    {
        return $this->children ?? [];
    }

    public function getMenu(): Menu
    {
        if($this->getRoot()->getId() === $this->getId()) {
            return $this->menu;
        }

        return $this->getRoot()->getMenu();
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param CreateCommandInterface|UpdateCommandInterface $command
     */
    private function setValuesFromCommands($command): void
    {
        $this->title       = $command->getTitle();
        $this->url         = $command->getUrl();
        $this->sort        = $command->getSort();
        $this->is_category = $command->isCategory();
        $this->blank       = $command->isBlank();
        $this->depth       = $command->getDepth();
        $this->entity_id   = $command->getEntityId();
        $this->parent      = $command->getParent();
    }
}