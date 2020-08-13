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
use Zentlix\MainBundle\Application\Command\CommandHandlerInterface;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\AfterDelete;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\BeforeDelete;
use Zentlix\MenuBundle\Domain\Menu\Service\Cache;

class DeleteHandler implements CommandHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(DeleteCommand $command): void
    {
        $menuId = $command->menu->getId();

        $this->eventDispatcher->dispatch(new BeforeDelete($command));

        foreach ($command->menu->getItems()->getValues() as $item) {
            $this->entityManager->remove($item);
        }

        Cache::clearMenu($command->menu->getCode());
        Cache::clearMenuTree($command->menu->getCode());

        $this->entityManager->remove($command->menu);
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new AfterDelete($menuId));
    }
}