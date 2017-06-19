<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Tellaw\SunshineAdminBundle\Service\AbstractWidget;

class EditWidget extends AbstractWidget {

    public function create ( $configuration ) {


        return $this->render( "Default:index", array() );

    }

}
