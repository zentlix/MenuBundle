<?php

/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Zentlix to newer
 * versions in the future. If you wish to customize Zentlix for your
 * needs please refer to https://docs.zentlix.io for more information.
 */

declare(strict_types=1);

namespace Zentlix\MenuBundle\UI\Http\Web\Form\Item;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\Type;
use Zentlix\MenuBundle\Application\Command\Item\Command;
use Zentlix\MenuBundle\Application\Command\Item\UpdateCommand;
use Zentlix\MenuBundle\Application\Command\Item\CreateCommand;
use Zentlix\MenuBundle\Domain\Menu\Repository\ItemRepository;

class Form extends AbstractForm
{
    protected EventDispatcherInterface $eventDispatcher;
    protected TranslatorInterface $translator;
    protected ItemRepository $itemRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                TranslatorInterface $translator,
                                ItemRepository $itemRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->itemRepository = $itemRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Command $command */
        $command = $builder->getData();
        $menuItems = array_merge(['zentlix_main.no' => null], $this->itemRepository->assocByMenuId($command->getMenu()->getId()));
        if($command instanceof UpdateCommand) {
            if($key = array_search($command->getEntity()->getId(), $menuItems)) {
                if(\is_bool($key) === false) {
                    unset($menuItems[$key]);
                }
            }
        }

        $builder->add('provider', Type\ChoiceType::class, [
            'choices' => $command->providers->assoc(),
            'label'   => 'zentlix_menu.menu.type',
            'update'  => true
        ]);
        $builder->add('sort', Type\IntegerType::class, [
            'label'       => 'zentlix_main.sort',
            'data'        => $command instanceof CreateCommand ? $this->itemRepository->getMaxSort() + 1 : $command->sort,
            'constraints' => [
                new GreaterThan(['value' => 0, 'message' => $this->translator->trans('zentlix_main.validation.greater_0')])
            ]
        ]);

        if($command->providers->isEntityProvider($command->provider)) {
            $builder->add('entity_id', Type\ChoiceType::class, [
                'choices' => $command->providers->getProviderByType($command->provider)->getEntities(),
                'label'   => $command->providers->getProviderByType($command->provider)->getTitle(),
                'update'  => true
            ]);
        }

        $builder->add('title', Type\TextType::class, [
            'label' => 'zentlix_menu.item.item'
        ]);

        if($command->providers->getProviderByType($command->provider)->isNeedUrl()) {
            $builder->add('url', Type\TextType::class, [
                'label'    => 'zentlix_main.site.url',
                'required' => false
            ]);
        }

        $builder->add('parent', Type\ChoiceType::class, [
            'choices'  => $menuItems,
            'label'    => 'zentlix_menu.item.parent',
            'required' => false
        ]);

        if($command->is_category) {
            $builder->add('depth', Type\IntegerType::class, [
                'label'       => 'zentlix_main.menu.depth',
                'constraints' => [
                    new GreaterThan(['value' => 0, 'message' => $this->translator->trans('zentlix_main.validation.greater_0')])
                ],
                'help' => 'zentlix_main.menu.depth_hint'
            ]);
        }
    }
}