# Entity Relations

Relations can only be described using the Doctrine annotation configuration.

### Entity description

```php
/**
 * Class Invoice
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimesheetsRepository")
 */
class Invoice {

    [...]

    /**
     * @var Project $project
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;
    
    [...]
    
}
```

The Invoice is linked to a project. A project could be linked to multiple invoices.

### Yaml Configuration

```php
tellaw_sunshine_admin:
    entities:
        invoice :
             configuration:
                 id: id
                 class: AppBundle\Entity\Invoice

             attributes:
                 id:
                    label: Id

                 title:
                    label: Titre

                 project:
                    label: Projet
                    filterAttribute : name
                    expanded : true

             form:
                title: Gestion des factures
                description: ""
                fields:
                     title: ~
                     type : ~
                     number: ~
                     company: ~
                     project: ~
                     partner: ~
                     receivedDate: ~
                     amount: ~

             list:
                title: Gestion des factures
                description: ""
                fields:
                     id : ~
                     number : ~
                     title : ~
                     amount : ~
                     company : ~

                search:
                    title : ~
                    partner : ~
                    project : ~
```

### The filterAttribute configuration key

In the YAML configuration of the project,  just add a property **'filterAttribute'** under the field.   
This property is used by the search to find required values for the linked entity.

{% hint style="danger" %}
This property is used by doctrine DQL, to load results faster. This key is required for any relation
{% endhint %}

### The \_\_toString method

The related class should have a **\_\_toString** method. That method will be used to render values in forms and in list tables.

```php
/**
 * Class Project
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 *
 */
class Project {

    [...]

    public function __toString()
    {
        return $this->getName();
    }
 
    [...]
    
}
```

### The 'expanded' configuration key

The '**expanded'** configuration key expected true or false as value. It defines if the field should be displayed in an expanded state or not. By default, value is false.

```yaml
tellaw_sunshine_admin:
    entities:
        invoice :
             configuration:
                 id: id
                 class: AppBundle\Entity\Invoice

             attributes:
                 id:
...

...
                 project:
                    label: Projet
                    filterAttribute : name
                    expanded : true
```

This option is equivalent to the Symfony configuration :

* [ChoiceType Field : 'expanded'](https://symfony.com/doc/current/reference/forms/types/choice.html#expanded)

