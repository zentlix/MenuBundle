<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\DataTable;

use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Zentlix\MainBundle\Domain\DataTable\Column\TextColumn;
use Zentlix\MainBundle\Infrastructure\Share\DataTable\AbstractDataTableType;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\Table as TableEvent;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;

class Table extends AbstractDataTableType
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable->setName($this->router->generate('admin.menu.list'));
        $dataTable->setTitle('zentlix_menu.menu.menu');
        $dataTable->setCreateBtnLabel('zentlix_menu.menu.create.action');

        $dataTable
            ->add('id', TextColumn::class, [
                'label'   => 'zentlix_main.id',
                'visible' => true
            ])
            ->add('title', TextColumn::class,
                [
                    'render'  => fn($value, Menu $context) =>
                        sprintf('<a href="%s">%s</a>', $this->router->generate('admin.menu.update', ['id' => $context->getId()]), $value),
                    'visible' => true,
                    'label'   => 'zentlix_main.title'
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