<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Tellaw\SunshineAdminBundle\Form\DataTransformer\IdToEntityTransformer;

class Select2Type extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Select2Type constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['class'])) {
            $builder->resetModelTransformers();
            $builder->resetViewTransformers();
            $builder->addModelTransformer(new IdToEntityTransformer($options['class'], $this->em));
        }
    }

    public function getParent()
    {
        return EntityType::class;
    }
}


