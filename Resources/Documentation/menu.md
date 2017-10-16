# Menu Configuration

# Introduction

The configuration of the menu can be done using an YAML file. This file should be imported into your application configuration.

The menu configuration file should start as this :
```
tellaw_sunshine_admin:
    menu :
        -
            ... menu element...
```

Here is how to include your configuration file to your config.yml standard file :
```
imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: services.yml }
...
    - { resource: sunshine/menu.yml }
...
```
config.yml file

A menu can handle the following elements :

## Section

A section is a separator of elements in the menu.

```
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

| Item                          | Description               |
|-------------------------------|---------------------------|
| label                         | Label which should be displayed |
| type                          | Type of element. Should be one of this page of documentation                                             |
| children                      | Any elements                      |


It contains children, which could be any of the menu supported elements.

## Submenu

A submenu is configured by :

```
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

| Item                          | Description               |
|-------------------------------|---------------------------|
| label                         | Label which should be displayed |
| type                          | Type of element. Should be one of this page of documentation                                             |
| icon                          | The name of an icon used by your theme                      |
| children                      | Any elements                      |

For default theme, the icon list is availabie here : http://fortawesome.github.io/Font-Awesome/icons/

## Sunshine List

```
...
        -
            label : Liste d'une entité
            type : list
            entityName : <my_entity>
            icon : compass
...
```

| Item                          | Description               |
|-------------------------------|---------------------------|
| label                         | Label which should be displayed |
| type                          | Type of element. Should be one of this page of documentation                                             |
| icon                          | The name of an icon used by your theme                      |
| entityName                      | Name of the entity in your configuration to display                      |

## Sunshine page

```
...
        -
            label : Mon Label 5
            type : page
            icon : compass
            parameters :
                id : demoPage2
...
```

| Item                          | Description               |
|-------------------------------|---------------------------|
| label                         | Label which should be displayed |
| type                          | Type of element. Should be one of this page of documentation                                             |
| icon                          | The name of an icon used by your theme                      |
| parameters/id                      | Id of the targeted page as it has been configured in your configuration                      |

## route

```
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

| Item                          | Description               |
|-------------------------------|---------------------------|
| label                         | Label which should be displayed |
| type                          | Type of element. Should be one of this page of documentation                                             |
| icon                          | The name of an icon used by your theme                      |
| route                      | name of the route available in your application                      |
| parameters                      | Any parameter you need to push to the route                      |


## External page


```
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

| Item                          | Description               |
|-------------------------------|---------------------------|
| label                         | Label which should be displayed |
| type                          | Type of element. Should be one of this page of documentation                                             |
| icon                          | The name of an icon used by your theme                      |
| parameters/url                      | Url of the page                      |
| target                      | Any of HTML compliant targets                     |


# Visibility configuration

You may need to configure menu items to be available only for specific users based on their roles and/or permissions.
You may achieve this by adding a security parameter for each menu item you want to control access to. 
See example below :

security:
    roles: [ROLE_ADMIN]
    permissions: ['edit_user']
    entity: User

