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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zentlix\MenuBundle\Application\Command\Item\CreateCommand;
use Zentlix\MenuBundle\Domain\Menu\Event\Item\CreateForm as CreateFormEvent;

class CreateForm extends Form
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->eventDispatcher->dispatch(new CreateFormEvent($builder));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateCommand::class,
            'label'      => 'zentlix_menu.item.create.process',
            'form'       => self::SIMPLE_FORM
        ]);
    }
}