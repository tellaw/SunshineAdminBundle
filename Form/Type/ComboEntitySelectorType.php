<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use App\Entity\PageStandard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Allow selection of family before displaying an autocomplete box
 */
class ComboEntitySelectorType extends AbstractType
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'family',
            ChoiceType::class,
            [
                'label' => 'Famille',
                'choices' => array_combine($options['families'], $options['families']),
                'attr' => ['class' => 'family-selector'],
                'empty_data' => ''
            ]
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $formOptions = $form->getConfig()->getOptions();
        $data = $event->getData();

        if (empty($data['family'])) {
            // Affectation de la famille par défaut lors du chargement initial du formulaire
            $data['family'] = reset($formOptions['families']);
            if (!$data['family']) {
                return;
            }
        }

        $form->add('entity', Select2EntityType::class, [
            'label' => 'Entité',
            'multiple' => false,
            'remote_route' => 'select2_list',
            'remote_params' => ['entityClass' => 'App\Entity\\' . $data['family']],
            'class' => 'App\Entity\\' . $data['family'],
            'primary_key' => 'id',
            'scroll' => true,
            'autostart' => true,
            'minimum_input_length' => 3,
            'page_limit' => 20,
            'allow_clear' => true,
            'delay' => 250,
            'cache' => false,
            'cache_timeout' => 60000, // if 'cache' is true
            'language' => 'fr',
            'placeholder' => 'Selectionner un élément',
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Exception
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['families' => []]);
    }

}
