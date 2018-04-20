# Filtering : Options multiple

## Introduction

The configuration of "option multiple" can be done using an YAML file. This file should be imported into your application configuration.

Imagine configuration like this : 

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

You can set multiple choice field, to adding option **multiple** on your object field.

```yaml
tellaw_sunshine_admin:
    entities:
        project :
             ...
             list:
                ...
                filters:
                    ...
                    company :
                       multiple: true
```

| Option | Description | Required | Possible value |
| --- | --- |
| multiple | pultiple choice | No | true \| false |



