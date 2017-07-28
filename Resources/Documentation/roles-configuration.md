# Roles Configuration

## Introduction

Sunshine roles management is based on Symfony roles. You should define roles withing your symfony application.
Theses roles will then be available to restrict access to sunshine elements.

## Access Restriction based on roles

### Menu

In the menu configuration, you can add a list of roles who can access to the link.
Any person who doesn't have the role, may not be able to see the menu entry.

Note : Default menu entries, generated for entities do not handle roles management.

Sample menu configuration :
```
    menu :
        -
            label : Reporting
            type : section
            children :

            -
                label : Mensuel
                type : page
                icon : compass
                parameters :
                    id : reporting_monthly
                roles :
                    - ROLE_ADMIN
```
With this configuration, the menu entry 'Mensuel' will only be shown to users with the role 'ROLE_ADMIN'
Note that you can give an array of authorized roles.

### Page

In the page configuration, you can add a list of roles who can access to the page.
Persons who doesn't have the role, will receive a Access Denied Exception.

```
tellaw_sunshine_admin:
    pages:
        demoPage :
            title : mydemoPageTitle
            description : My Demo Page description tag
            roles :
                - ROLE_ADMIN
            rows :
```

In this configuration, users without ROLE_ADMIN role will receive the Exception.
Note that you can give an array of authorized roles.

### Widget

In the widget configuration, you can add a list of roles who can see the widget.

```
            rows :
                -
                    widget1 :
                            title : Liste de projets
                            columns : 8
                            type : list
                            preload : false
                            parameters :
                                newRoute : my_route_for_new
                                editRoute : my_route_for_edit
                                entityName : project
                            roles :
                                - ROLE_USER
```

In this configuration, users without the ROLE_USER will noto see the widget.

### AnyWhere else...

Use the standard Symfony methods to test the roles.