<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class DefaultType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
    }

    /**
     * Construction du formulaire selon la nture de l'entité
     *
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData(); 

        $fieldsConfiguration = $form->getConfig()->getOptions()['fields_configuration'];
        $entityConfiguration = $form->getConfig()->getOptions()['configuration'];
        $crudService = $form->getConfig()->getOptions()['crud_service'];
        $crudService->buildFormFields($form, $fieldsConfiguration);

        $form->add('Valider', SubmitType::class);

        dump($fieldsConfiguration);
        dump($entityConfiguration);
        dump($form);
        dump($data); //die;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('fields_configuration');
        $resolver->setRequired('configuration');
        $resolver->setRequired('crud_service');
    }
}
