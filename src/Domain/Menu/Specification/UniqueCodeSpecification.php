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

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Domain\Shared\Specification\AbstractSpecification;
use Zentlix\MenuBundle\Domain\Menu\Repository\MenuRepository;

final class UniqueCodeSpecification extends AbstractSpecification
{
    private MenuRepository $menuRepository;
    private TranslatorInterface $translator;

    public function __construct(MenuRepository $menuRepository, TranslatorInterface $translator)
    {
        $this->menuRepository = $menuRepository;
        $this->translator = $translator;
    }

    public function isUnique(string $code): bool
    {
        return $this->isSatisfiedBy($code);
    }

    public function isSatisfiedBy($value): bool
    {
        if($this->menuRepository->findOneByCode($value)) {
            throw new NonUniqueResultException($this->translator->trans('zentlix_menu.already_exist'));
        }

        return true;
    }

    public function __invoke(string $code)
    {
        return $this->isUnique($code);
    }
}
