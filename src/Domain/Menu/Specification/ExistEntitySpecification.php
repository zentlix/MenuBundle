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

use Doctrine\Persistence\ObjectRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\Application\Query\NotFoundException;
use function is_null;

final class ExistEntitySpecification
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function isExist(ObjectRepository $repository, int $id): void
    {
        if(is_null($repository->find($id))) {
            throw new NotFoundException(sprintf($this->translator->trans('zentlix_menu.entity_not_found'), $id));
        }
    }

    public function __invoke(ObjectRepository $repository, int $id): void
    {
        $this->isExist($repository, $id);
    }
}