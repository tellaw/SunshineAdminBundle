# Create a Page with custom controller

Why do I need to create a custom controller ?

Well imagine, your dashboard or page should focus on a particular data. You will have to pass this data to your page as parameter (custom route), and then to widgets.

## Create the controller

A custom controller should extends the 'AbstractPageController' of the sunshine bundle.

```
class PageController extends AbstractPageController
{

    /**
     * Expose Page by default for the sunshine bundle
     *
     * @Route("/page/{pageId}", name="sunshine_page")
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse
     */
    public function pageAction($pageId = null)
    {

        return $this->renderPage( array(), $pageId );
    }

}
```

The controller should extend the AbstractPageController. It implements actions that you want to use.

It should render its page using the renderPage method given by its abstract class. This method will read the page configuration, load the defined widgets and try to render them.

## Pass parameters to widgets in the page.

Within the page controller, you can create a MessageBag object to send messages to your widgets.


```
class PageController extends AbstractPageController
{

    /**
     * Expose Page by default for the sunshine bundle
     *
     * @Route("/page/{pageId}", name="my_custom_page")
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse
     */
    public function pageAction($pageId = null)
    {
        /** @var MessageBag $messages */
        $messageBag = new MessageBag();
        $messageBag->addMessage( "myCustomKey", "MyCustomValue" );

        return $this->renderPage( array(), $pageId, $messageBag );
    }

}
```

Then, within your widgets, you are able to call :

```
...
public function create ( $configuration, MessageBag $messagebag ) {
    $myMessage = $messageBag->getMessage("myCustomKey");
}
...
```

The page configuration is the same as the normal "Sunshine Page".

## Including this page in the menu

You should use the default option 'route' of the menu to define a call to your custom page

```
...
        -
            label : Route Custom
            type : route
            icon : diamond
            route : widget_test
            parameters :
                pageId : demoPage

...
```

(Refer to menu documentation)