<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tellaw\SunshineAdminBundle\Service\CrudService;

/**
 */
class FiltersType extends AbstractType
{
    /**
     * @var CrudService
     */
    protected $crudService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DefaultType constructor.
     * @param CrudService $crudService
     * @param EntityManagerInterface $em
     */
    public function __construct(CrudService $crudService, EntityManagerInterface $em)
    {
        $this->crudService = $crudService;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
    }

    /**
     * Construction du formulaire selon la nature de l'entité
     *
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $fieldsConfiguration = $form->getConfig()->getOptions()['fields_configuration'];
        $this->buildFormFields($form, $fieldsConfiguration);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['fields_configuration' => [], 'configuration' => [], 'crud_service' => $this->crudService, 'em' => $this->em ]);
        $resolver->setRequired('fields_configuration');
        $resolver->setRequired('crud_service');
        $resolver->setRequired('em');
    }

    /**
     * Genération des champs du formulaire selon la config yml
     *
     * @param Form|FormBuilder $form
     * @param array $fieldsConfiguration
     * @throws \Exception
     */
    protected function buildFormFields($form, array $fieldsConfiguration): void
    {
        $this->crudService->buildFormFields($form, $fieldsConfiguration, 'filterElement', false);
    }

}
