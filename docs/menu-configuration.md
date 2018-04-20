# Menu Configuration

The configuration of the menu can be done using an YAML file. This file should be imported into your application configuration.

Here is how to include your configuration file to your config.yml standard file :

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: services.yml }
...
    - { resource: sunshine/menu.yml }
...
```

A menu can handle the following elements:

## Section

A section is a separator of elements in the menu.

```yaml
...
        -
            label : Entités
            type : section
            children :

            -
                label : Company
                type : page
                icon : diamond
                parameters :
                    id : demoPage

            -
                label : Project
                type : page
                icon : puzzle
                parameters :
                    id : demoPage2
...
```

| Item | Description | Required |
| --- | --- | --- | --- |
| label | Label which should be displayed | Yes |
| type | Type of element. Should be one of this page of documentation | Yes |
| children | Any elements | No |

## Submenu

```yaml
...
        -
            label : Sous Menu
            type : subMenu
            icon : compass
            children :

                -
                    label : Mon Label 5
                    type : page
                    icon : compass
                    parameters :
                        id : demoPage2

                -
                    label : Mon Label 6
                    type : external
                    icon : compass
                    parameters :
                        url : http://www.google.fr
...
```

It contains children, which could be any of the menu supported elements.

| Item | Description | Required |
| --- | --- | --- | --- | --- |
| label | Label which should be displayed | Yes |
| type | Type of element. Should be one of this page of documentation | Yes |
| icon | The name of an icon used by your theme | Yes |
| children | Any element | No |

## Sunshine List

```yaml
...
        -
            label : Liste d'une entité
            type : list
            entityName : <my_entity>
            icon : compass
...
```

| Item | Description | Required |
| --- | --- | --- | --- | --- |
| label | Label which should be displayed | Yes |
| type | Type of element. Should be one of this page of documentation | Yes |
| icon | The name of an icon used by your theme | Yes |
| entityName | Name of the entity in your configuration to display | Yes |

## Sunshine page

```yaml
...
        -
            label : Mon Label 5
            type : page
            icon : compass
            parameters :
                id : demoPage2
...
```

| Item | Description | Required |
| --- | --- | --- | --- | --- |
| label | Label which should be displayed | Yes |
| type | Type of element. Should be one of this page of documentation | Yes |
| icon | The name of an icon used by your theme | Yes |
| parameters/id | Id of the targeted page as it has been configured in your configuration | Yes |

## route

```yaml
...
        -
            label : Route Custom
            type : route
            icon : diamond
            route : widget_test
            parameters :
                pageName : demoPage
                row : 0
                widgetName : test
...
```

| Item | Description | Required |
| --- | --- | --- | --- | --- | --- |
| label | Label which should be displayed | Yes |
| type | Type of element. Should be one of this page of documentation | Yes |
| icon | The name of an icon used by your theme | Yes |
| route | name of the route available in your application  | Yes |
| parameters | Any parameter you need to push to the route | Yes |

## External page

```yaml
...
        -
            label : Mon Label 4
            type : external
            icon : compass
            target: _blank
            parameters :
                url : http://www.google.fr
...
```

| Item | Description | Required |
| --- | --- | --- | --- | --- | --- |
| label | Label which should be displayed | Yes |
| type | Type of element. Should be one of this page of documentation | Yes |
| icon | The name of an icon used by your theme | Yes |
| parameters/url | Url of the page | Yes |
| target | Any of HTML compliant targets | Yes |

## Roles & Permissions configuration

You may need to configure menu items to be available only for specific users based on their **roles** and/or    **permissions**.  
You may achieve this by adding a security parameter for each menu item you want to control access to. 

See example below :

```yaml
```
security:
    roles: [ROLE_ADMIN]
    permissions: ['edit_user']
    entity: User
```
```

