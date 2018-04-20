# Roles & Permissions

Sunshine roles management is based on Symfony roles. You should define roles withing your symfony application.  
Theses roles will then be available to restrict access to sunshine elements.

## Access Restriction based on roles and roles permissions

### Menu

You may need to configure menu items to be available only for specific users based on their roles and/or permissions. You may achieve this by adding a security parameter for each menu item you want to control access to. See example below :

Sample menu configuration :

```yaml
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
                security:
                    roles: ['ROLE_ADMIN']
                    permissions: ['edit_user']
                    entity: User
```

With this configuration, the menu entry 'Mensuel' will only be shown to users with the role 'ROLE\_ADMIN'  
Note that you can give an array of authorized roles.

### Page

In the page configuration, you can add a list of roles who can access to the page.  
Persons who doesn't have the role, will receive a Access Denied Exception.

```yaml
tellaw_sunshine_admin:
    pages:
        demoPage :
            title : mydemoPageTitle
            description : My Demo Page description tag
            roles :
                - ROLE_ADMIN
            rows :
            ...
```

In this configuration, users without ROLE\_ADMIN role will receive the Exception.  
Note that you can give an array of authorized roles.

### Widget

In the widget configuration, you can add a list of roles who can see the widget.

```yaml
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

In this configuration, users without the ROLE\_USER will not see the widget.

### AnyWhere else...

Use the standard Symfony methods to test the roles.  
[Check Symfony documentation on security](https://symfony.com/doc/current/security.html)

