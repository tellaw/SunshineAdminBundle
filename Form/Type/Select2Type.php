<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Select2Type extends AbstractType
{
    public function getParent()
    {
        return ChoiceType::class;
    }
}
