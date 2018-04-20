# Filtering : Default values

## Default filtering value

### Introduction

The configuration of "default filtering value" can be done using an YAML file. This file should be imported into your application configuration.

Imagine the following configuration

```yaml
tellaw_sunshine_admin:
    entities:
        project :
             configuration:
                 id: id
                 class: AppBundle\Entity\Project

             attributes:
                 id:
                    label: Id

                 name:
                    label: Nom

                 budget:
                    label: Budget

                 company:
                    label: Société
                    filterAttribute : name

             list:
                title: Title de la page de LISTE
                description: "Descirption courte de la page de liste.<br/>
                test<br/>
                test"
                fields:
                     id : ~
                     name : ~
                     budget : ~
                     company : ~
                search:
                    name : ~
                    company : ~
                    
                filters:
                    name : ~ 
                    budget : ~
                    company : ~ 
```

Suppose you want to set the default value on your filter form with the following values :

```php
    name = "my default value name"
    company = ["My First Company", "My second company"]
```

you can proceed as well, using the **value** option on your filter field

```yaml
tellaw_sunshine_admin:
    entities:
        project :
             ...
             list:
                ...
                general_search: "default value on general search"

                filters:
                    name :
                       value:
                            arguments: ["my default value name"]
                    budget : ~
                    company :
                        value:
                            arguments: ["My First Company", "My second company"] # default value of company.
```

| Option | Description | Required | Possible values |
| --- | --- | --- |
| provider | provide default value of your field  | no | **function\_name** or **your\_service@function\_name** |
| arguments | arguments of you provied | no | array |



