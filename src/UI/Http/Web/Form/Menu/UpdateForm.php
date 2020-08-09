<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Form\Menu;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MenuBundle\Application\Command\Menu\UpdateCommand;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\UpdateForm as UpdateFormEvent;
use Zentlix\MenuBundle\Domain\Menu\Entity\Menu;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\MainBundle\UI\Http\Web\Type\DataTableType;

class UpdateForm extends AbstractForm
{
    private EventDispatcherInterface $eventDispatcher;
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $router;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                TranslatorInterface $translator,
                                UrlGeneratorInterface $router)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Menu $menu */
        $menu = $builder->getData()->getEntity();

        $builder->add(
            $builder->create('main', Type\FormType::class, ['inherit_data' => true, 'label' => 'zentlix_main.main'])
                ->add('title', Type\TextType::class, [
                    'label' => 'zentlix_main.title'
                ])
                ->add('code', Type\TextType::class, [
                    'label'    => 'zentlix_main.symbol_code',
                    'required' => false
                ])
                ->add('description', Type\TextareaType::class, [
                    'label'    => 'zentlix_main.description',
                    'required' => false
                ])
        );

        $builder->add(
            $builder->create('items', Type\FormType::class, ['inherit_data' => true, 'label' => 'zentlix_menu.item.items'])
               ->add('items', DataTableType::class, [
                   'displayedColumns' => ['id', 'title', 'url', 'parent', 'sort'],
                   'columnLabels' => [
                       'id'     => 'zentlix_main.id',
                       'title'  => 'zentlix_main.title',
                       'url'    => 'zentlix_main.site.url',
                       'parent' => 'zentlix_main.parent',
                       'sort'   => 'zentlix_main.sort'
                   ],
                   'actionUrl'   => $this->router->generate('admin.menu_item.create', ['id' => $menu->getId()]),
                   'actionTitle' => 'zentlix_menu.add_item'
               ])
        );

        $this->eventDispatcher->dispatch(new UpdateFormEvent($builder));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'     =>  UpdateCommand::class,
            'label'          => 'zentlix_menu.menu.update.process',
            'form'           =>  self::TABS_FORM,
            'deleteBtnLabel' => 'zentlix_menu.menu.delete.action',
            'deleteConfirm'  => 'zentlix_menu.menu.delete.confirmation'
        ]);
    }
}