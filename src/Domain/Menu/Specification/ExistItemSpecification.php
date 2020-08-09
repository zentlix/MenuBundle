<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\Domain\Menu\Specification;

use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Application\Query\NotFoundException;
use Zentlix\MainBundle\Domain\Shared\Specification\AbstractSpecification;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;

final class ExistItemSpecification extends AbstractSpecification
{
    private ItemRepository $itemRepository;
    private TranslatorInterface $translator;

    public function __construct(ItemRepository $itemRepository, TranslatorInterface $translator)
    {
        $this->itemRepository = $itemRepository;
        $this->translator = $translator;
    }

    public function isExist(int $id): bool
    {
        return $this->isSatisfiedBy($id);
    }

    public function isSatisfiedBy($value): bool
    {
        if($this->itemRepository->find($value) === null) {
            throw new NotFoundException(sprintf($this->translator->trans('zentlix_menu.item.not_exist'), $value));
        }

        return true;
    }

    public function __invoke(int $id)
    {
        return $this->isExist($id);
    }
}