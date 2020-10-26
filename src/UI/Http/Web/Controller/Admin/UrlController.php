<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Zentlix\MainBundle\UI\Http\Web\Controller\Admin\AbstractAdminController;
use Zentlix\MenuBundle\Application\Command\Item\Url\CreateCommand;
use Zentlix\MenuBundle\Application\Command\Item\Url\DeleteCommand;
use Zentlix\MenuBundle\Application\Command\Item\Url\UpdateCommand;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MenuBundle\Domain\Menu\Entity\Item;
use Zentlix\MenuBundle\UI\Http\Web\Form\Item\Url\CreateForm;
use Zentlix\MenuBundle\UI\Http\Web\Form\Item\Url\UpdateForm;

class UrlController extends AbstractAdminController
{
    public function create(Menu $menu, Request $request): Response
    {
        try {
            $command = new CreateCommand($menu);

            $this->createForm(CreateForm::class, $command)->handleRequest($request);
            $this->exec($command);

            $this->addFlash('success', $this->translator->trans('zentlix_menu.item.create.success'));

            return $this->redirectToRoute('admin.menu.update', ['id' => $menu->getId(), 'items' => 1]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin.menu.update', ['id' => $menu->getId(), 'items' => 1]);
        }
    }

    public function update(Item $item, Request $request): Response
    {
        try {
            $command = new UpdateCommand($item);

            $this->createForm(UpdateForm::class, $command)->handleRequest($request);
            $this->exec($command);

            $this->addFlash('success', $this->translator->trans('zentlix_menu.item.update.success'));

            return $this->redirectToRoute('admin.menu.update', ['id' => $item->getMenu()->getId(), 'items' => 1]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin.menu.update', ['id' => $item->getMenu()->getId(), 'items' => 1]);
        }
    }

    public function delete(Item $item): Response
    {
        try {
            $menuId = $item->getMenu()->getId();
            $command = new DeleteCommand($item);

            $this->exec($command);
            $this->addFlash('success', $this->translator->trans('zentlix_menu.item.delete.success'));

            return $this->redirectToRoute('admin.menu.update', ['id' => $menuId, 'items' => 1]);
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('admin.menu.update', ['id' => $menuId, 'items' => 1]);
        }
    }
}