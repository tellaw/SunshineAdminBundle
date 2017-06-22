<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Symfony\Component\HttpFoundation\RequestStack;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Tellaw\SunshineAdminBundle\Service\AbstractWidget;

class EditWidget extends AbstractWidget {

    public function create ( $configuration, MessageBag $messagebag ) {

        $request = $this->getCurrentRequest();

        $route = $request->get("_route");
        return $this->render( "TellawSunshineAdminBundle:Default:index", array( "configuration" => $configuration, "request" => $request, "route" => $route ) );

    }

}
