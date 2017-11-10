# Relations

Relations can only be described using the Doctrine annotation configuration.

## Entity description

```
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

## Yaml Configuration
```
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

_**Note :**_ the configuration of the project in the Yaml, just adding a property **'filterAttribute'** under the field. This property is used by the search to find required values for the linked entity.

The related class should have a **__toString** method. That method will be used to render values in forms and in list tables.

The **expanded** configuration key expected true or false as value. It defines if the field should be displayed in an expanded state or not. By default, value is false.

```
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
