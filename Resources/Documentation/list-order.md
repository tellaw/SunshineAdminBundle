# Entity list order

# Introduction

The configuration of the entity list order can be done using an YAML file. This file should be imported into your application configuration.

The theme configuration file should start as this :

```
tellaw_sunshine_admin:
    entities:
        partner :
            list:
                fields:
                    id :
                        label: Identifiant
                        order: asc
                      
```

| Item                          | Description                                            | Required|
|-------------------------------|--------------------------------------------------------|----------
| order                           | order direction (asc, desc)                     | No      |

Note : The order attribute must be applied to only one field.