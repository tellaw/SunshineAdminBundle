# Widgets

## Yaml Configuration

A widget should be configured in a page as follow :

```yaml
ListComments:
	title: Comments widget
	description: My description goes here
	columns: 12
	service: AppBundle\Service\Widgets\Comment
```

| Item | Description | Required |
| --- | --- | --- | --- | --- | --- | --- |
| Identifier or Name | This is a uniq identifier of the widget on a page. | Yes |
| title | Title of the widget | No |
| description | Description of the widget | No |
| columns | Number of columns the widget should take \(max 12\) | Yes |
| service | Path or alias of the service. | Yes |
| template | Template to use for rendering | No |

## Service class creation

```php
abstract class AbstractWidget {

    public function __construct( RequestStack $requestStack, \Twig_Environment $twig, EntityManager $em )

    protected function getDoctrine ()

    protected function getCurrentRequest()

    protected function render ( $template, $parameters )

    public abstract function create ( $configuration, MessageBag $messageBag);
}
```

Your widget should be located in the namespace :

{% hint style="success" %}
namespace AppBundle\Service\Widgets;
{% endhint %}

The class must extend AbstractWidget from "Tellaw\SunshineAdminBundle\Service"

### getDoctrine\(\)

This method return  an entityManager.

### getCurrentRequest\(\)

This method return the current request.

### render\($template, $parameters\)

This method return the rendered template. Merging template and parameters array.

{% hint style="danger" %}
The template name should contains the path, **but should not contains extension.**
{% endhint %}

### create \( $configuration, MessageBag $messageBag\)

This method is called by the page to render your widget. Implement here your business logic.

## View creation

The view is a simple Twig template. You can access any parameters you sent to it.  
For more informations, please, read [Twig documentation.](https://twig.symfony.com/doc/2.x/)

## MessageBag : Send informations to widget

The message bag is a message bus making possible for you to send messages from a page to the widgets.

The messageBag is an attribut you can access from Pages and Widgets. 

[MessageBag class definition is here.](https://github.com/tellaw/SunshineAdminBundle/blob/master/Entity/MessageBag.php)

```php
namespace Tellaw\SunshineAdminBundle\Entity;
class MessageBag {
    private $messageBag = array();
    public function addMessage ( $key, $message );
    public function getMessage ( $key );
}
```

Use the addMessage method to save a message, and getMessage to read it.

## Forms inside a widget

As widgets can access to the request, you can easily create forms. The following syntax is very similar to a basic Symfony Controller

```php
   /**
     * @param $configuration
     * @param MessageBag $messageBag
     * @return string|RedirectResponse
     */
    public function create($configuration, MessageBag $messageBag)
    {
        $entityName = $messageBag->getMessage("messageEntityName");
        $parentEntityName = $messageBag->getMessage("entityName");
        $id = $messageBag->getMessage('id');

        $message = new \AppBundle\Entity\Message();
        $form = $this->formFactory->create(
            MessageType::class,
            $message,
            [
				...
            ]
        );

        if ($form->handleRequest($this->getCurrentRequest()) && $form->isValid() && $form->isSubmitted()) {
            $this->em->persist($message);
            $this->em->flush();
            return new RedirectResponse(
                $this->router->generate($this->getCurrentRequest()->get('_route'), ['id' => $id])
            );
        }

        return $this->render(
            'widgets/message',
            [
				...
            ]
        );
    }
```

## Redirect to current Route

As your widgets can be located in multiple pages, with different routes, you should take care when redirecting or forwarding to a new page.

The following syntax use the current route to redirect :

```php
return new RedirectResponse(
	$this->router->generate(
		$this->getCurrentRequest()->get('_route'),
		['id' => $id]
	)
);
```

## How to inject services in Widget Service

Exemple of usefull services to inject in your Widget service.

{% hint style="info" %}
Inject ONLY the service required by your business logic.
{% endhint %}

```php
    ...
    use Doctrine\ORM\EntityManager;
    use Symfony\Component\Form\FormFactory;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\RouterInterface;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
    use Tellaw\SunshineAdminBundle\Entity\MessageBag;
    use Tellaw\SunshineAdminBundle\Service\AbstractWidget;
    use Tellaw\SunshineAdminBundle\Service\CrudService;
    use Tellaw\SunshineAdminBundle\Service\EntityService;
    ...
    /**
     * Message constructor.
     * @param RouterInterface $router
     * @param FormFactory $formFactory
     * @param RequestStack $requestStack
     * @param \Twig_Environment $twig
     * @param EntityManager $em
     * @param MailerService $mailerService
     * @param EntityService $entityService
     * @param CrudService $crudService
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        RouterInterface $router,
        FormFactory $formFactory,
        RequestStack $requestStack,
        \Twig_Environment $twig,
        EntityManager $em,
        EntityService $entityService,
        CrudService $crudService,
        TokenStorage $tokenStorage
    ) {
        parent::__construct($requestStack, $twig, $em);
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->mailerService = $mailerService;
        $this->entityService = $entityService;
        $this->crudService = $crudService;
        $this->tokenStorage = $tokenStorage;
    }
```



