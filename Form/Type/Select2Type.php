<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Select2Type extends AbstractType
{
    public function getParent()
    {
        return EntityType::class;
    }
}