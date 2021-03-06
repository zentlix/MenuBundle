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
use Zentlix\MenuBundle\Application\Command\Menu\UpdateCommand;
use Zentlix\MenuBundle\Domain\Menu\Event\Menu\UpdateForm as UpdateFormEvent;
use Zentlix\MainBundle\UI\Http\Web\FormType\AbstractForm;
use Zentlix\MainBundle\UI\Http\Web\Type;

class UpdateForm extends AbstractForm
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', Type\TextType::class, [
                'label' => 'zentlix_main.title'
            ])
            ->add('code', Type\TextType::class, [
                'label'    => 'zentlix_main.symbol_code',
                'required' => true
            ])
            ->add('description', Type\TextareaType::class, [
                'label'    => 'zentlix_main.description',
                'required' => false
            ]);

        $this->eventDispatcher->dispatch(new UpdateFormEvent($builder));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => UpdateCommand::class]);
    }
}