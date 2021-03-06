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
use Gedmo\Mapping\Annotation\Slug;
use Zentlix\MainBundle\Domain\Shared\Entity\Eventable;
use Zentlix\MenuBundle\Application\Command\Menu\CreateCommand;
use Zentlix\MenuBundle\Application\Command\Menu\UpdateCommand;

/**
 * @Mapping\Entity(repositoryClass="Zentlix\MenuBundle\Domain\Menu\Repository\MenuRepository")
 * @Mapping\Table(name="zentlix_menu_menu", uniqueConstraints={
 *     @Mapping\UniqueConstraint(columns={"code"})
 * })
 */
class Menu implements Eventable
{
    /**
     * @Mapping\Id()
     * @Mapping\GeneratedValue()
     * @Mapping\Column(type="integer")
     */
    private $id;

    /** @Mapping\Column(type="string", length=255) */
    private $title;

    /**
     * @Slug(fields={"title"}, updatable=false, unique=true)
     * @Mapping\Column(type="string", length=64)
     */
    private $code;

    /** @Mapping\Column(type="string", length=255, nullable=true) */
    private $description;

    /**
     * @Mapping\OneToOne(targetEntity="Zentlix\MenuBundle\Domain\Menu\Entity\Item", inversedBy="menu")
     * @Mapping\JoinColumn(name="root_item_id", referencedColumnName="id")
     */
    private ?Item $root_item;

    public function __construct(CreateCommand $command)
    {
        $this->setValuesFromCommands($command);
    }

    public function update(UpdateCommand $command)
    {
        $this->setValuesFromCommands($command);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setRootItem(Item $item): self
    {
        $this->root_item = $item;

        return $this;
    }

    public function getRootItem(): Item
    {
        return $this->root_item;
    }

    public function isCodeEqual(string $code): bool
    {
        return $code === $this->code;
    }

    /**
     * @param CreateCommand|UpdateCommand $command
     */
    private function setValuesFromCommands($command): void
    {
        $this->title = $command->title;
        $this->code = $command->code;
        $this->description = $command->description;
    }
}