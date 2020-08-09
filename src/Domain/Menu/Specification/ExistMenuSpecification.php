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
use Zentlix\MenuBundle\Domain\Menu\Repository\MenuRepository;

final class ExistMenuSpecification extends AbstractSpecification
{
    private MenuRepository $menuRepository;
    private TranslatorInterface $translator;

    public function __construct(MenuRepository $menuRepository, TranslatorInterface $translator)
    {
        $this->menuRepository = $menuRepository;
        $this->translator = $translator;
    }

    public function isExist(int $id): bool
    {
        return $this->isSatisfiedBy($id);
    }

    public function isSatisfiedBy($value): bool
    {
        if($this->menuRepository->find($value) === null) {
            throw new NotFoundException(sprintf($this->translator->trans('zentlix_menu.not_exist'), $value));
        }

        return true;
    }

    public function __invoke(int $id)
    {
        return $this->isExist($id);
    }
}