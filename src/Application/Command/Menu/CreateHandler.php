<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Application\Command\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Infrastructure\Share\Bus\CommandHandlerInterface;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\BeforeCreate;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\AfterCreate;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Specification\UniqueCodeSpecification;

class CreateHandler implements CommandHandlerInterface
{
    private UniqueCodeSpecification $uniqueCodeSpecification;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                UniqueCodeSpecification $uniqueCodeSpecification)
    {
        $this->uniqueCodeSpecification = $uniqueCodeSpecification;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateCommand $command): void
    {
        if($command->code) {
            $this->uniqueCodeSpecification->isUnique($command->code);
        }

        $this->eventDispatcher->dispatch(new BeforeCreate($command));

        $menu = new Menu($command);

        $this->entityManager->persist($menu);
        $this->entityManager->flush();

        $command->menuId = $menu->getId();

        $this->eventDispatcher->dispatch(new AfterCreate($menu, $command));
    }
}