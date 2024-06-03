<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class ChoiceButtonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('save_and_quit', SubmitType::class, [
                'label' => 'Enregistrer et quitter',
                'attr' => ['class' => 'btn-info']
            ]);
    }
}
