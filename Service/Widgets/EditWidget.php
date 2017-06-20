<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;

class EditWidget extends AbstractWidget {

    public function create ( $configuration ) {

        $request = $this->getCurrentRequest();

        $route = $request->get("_route");
        return $this->render( "Default:index", array( "configuration" => $configuration, "request" => $request, "route" => $route ) );

    }

}
