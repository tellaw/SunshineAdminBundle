# Default filtering value

# Introduction

The configuration of "default filtering value" can be done using an YAML file. This file should be imported into your application configuration.

Imagine configuration like this : 

```
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

Suppose you want to set the default value on your filter form like this

```
    name = "my default value name"
    company = ["Editions T.I", "Comundi"]
```


you can proceed as well, using the **value** option on your filter field

```
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
                            arguments: ["Editions T.I", "Comundi"] # default value of company.
```

| Field                             | Description                           | Required |  Possible value                                   |
|-----------------------------------|---------------------------------------|----------|---------------------------------------------------|
| provider                          | provide default value of your field   | No       | function_name or your_service@function_name       |
| arguments                         | arguments of your provider            | No       | array                                             |
