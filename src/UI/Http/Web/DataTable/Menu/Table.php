<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\DataTable\Menu;

use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Zentlix\MainBundle\Infrastructure\Share\DataTable\AbstractDataTableType;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\Table as TableEvent;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;

class Table extends AbstractDataTableType
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable->setName('menu-datatable');

        $dataTable
            ->add('id', TextColumn::class, [
                'label'   => 'zentlix_main.id',
                'visible' => true
            ])
            ->add('title', TwigColumn::class,
                [
                    'template'  => '@MenuBundle/admin/menu/datatable/title.html.twig',
                    'visible'   => true,
                    'label'     => 'zentlix_main.title'
                ])
            ->add('code', TextColumn::class, [
                'label'   => 'zentlix_main.symbol_code',
                'visible' => true
            ])
            ->addOrderBy($dataTable->getColumnByName('id'), $dataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, ['entity' => Menu::class]);

        $this->eventDispatcher->dispatch(new TableEvent($dataTable));
    }
}