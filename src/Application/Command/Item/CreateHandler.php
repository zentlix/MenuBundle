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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Zentlix\MainBundle\Application\Command\CommandHandlerInterface;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;
use Zentlix\MenuBundle\Domain\Menu\Service\Cache;
use Zentlix\MenuBundle\Domain\Menu\Specification\ExistEntitySpecification;
use Zentlix\MenuBundle\Domain\Menu\Specification\ExistMenuSpecification;
use Zentlix\MenuBundle\Domain\Menu\Specification\ExistItemSpecification;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\BeforeCreate;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\AfterCreate;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\Domain\Menu\Service\Providers;

class CreateHandler implements CommandHandlerInterface
{
    private ExistMenuSpecification $existMenuSpecification;
    private ExistItemSpecification $existItemSpecification;
    private ExistEntitySpecification $existEntitySpecification;
    private ItemRepository $itemRepository;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private Providers $providers;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                ExistMenuSpecification $existMenuSpecification,
                                ExistItemSpecification $existItemSpecification,
                                ExistEntitySpecification $existEntitySpecification,
                                ItemRepository $itemRepository,
                                Providers $providers)
    {
        $this->existMenuSpecification = $existMenuSpecification;
        $this->existItemSpecification = $existItemSpecification;
        $this->existEntitySpecification = $existEntitySpecification;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->itemRepository = $itemRepository;
        $this->providers = $providers;
    }

    public function __invoke(CreateCommand $command): void
    {
        $this->existMenuSpecification->isExist($command->getMenu()->getId());

        if($command->providers->isEntityProvider($command->provider)) {
            $this->existEntitySpecification->isExist($this->entityManager->getRepository(
                $command->providers->getProviderByType($command->provider)->getEntityClassName()), $command->entity_id);
            $command->url = null;
        } else {
            $command->entity_id = null;
        }

        if($command->parent) {
            $this->existItemSpecification->isExist($command->parent);
            $command->parent = $this->itemRepository->get($command->parent);
            $this->itemRepository->reorder($this->itemRepository->get($command->parent), 'sort');
        }

        $this->eventDispatcher->dispatch(new BeforeCreate($command));

        $item = new Item($command);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        Cache::clearMenuTree($item->getMenu()->getCode());

        $this->eventDispatcher->dispatch(new AfterCreate($item, $command));
    }
}