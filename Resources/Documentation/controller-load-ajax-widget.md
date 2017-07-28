# Create a Custom Ajax Controller to load a widget

## Creating the controller

As for a custom page controller, your Ajax controller will extend 'AbstractPageController'.

Despite the page controller, this ajax controller will call the render method 'renderWidget'

```
protected function renderWidget ( $pageId, $widgetId, $messageBag ){}
```

| Parameter     | Type          | Description   |
|---------------|---------------|---------------|
| pageId        | String        | Configuration name of the page |
| widgetId      | String       | Id of the widget in the page to refresh |
| messageBag    | MessageBag   | MessageBag object to send to the service |

## Example :

```
<?php

namespace AppBundle\Controller;

use Tellaw\SunshineAdminBundle\Controller\AbstractPageController;
use Tellaw\SunshineAdminBundle\Entity\MessageBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AjaxController extends AbstractPageController
{

/**
* Reload a widget
*
* @Route("/ajax/resource_timesheet/{pageId}/{widgetId}/{week}", name="ajax_resource_timesheet")
* @Method({"GET", "POST"})
*
* @return JsonResponse
*/
    public function resourceWidgetAction( $pageId, $widgetId, $week = 1)
    {

        /** @var MessageBag $messages */
        $messageBag = new MessageBag();
        $messageBag->addMessage( "pageId", $pageId );
        $messageBag->addMessage( "widgetId", $widgetId );
        $messageBag->addMessage( "week", $week );

        return $this->renderWidget($pageId, $widgetId, $messageBag );
    }

}
```
