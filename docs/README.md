---
description: >-
  SunshineAdminBundle is a bundle designed to help you to create easily a
  backoffice tool for your application.
---

# Sunshine Admin Bundle

## Documentation

This documentation is available with a lot of features on :   
[https://sunshine-team.gitbook.io/sunshineadminbundle/](https://sunshine-team.gitbook.io/sunshineadminbundle/)

### Requierements

| PHP | 7.0 or later |
| --- | --- |
| Symfony | 3.4 or later. SF4 recommended. |

The bundle provides two different level :

* **Entities management** : Very simple to setup, it makes possible to handle CRUD actions very easily.
* **Pages** and **widgets **management : Makes possible for user to go a step further and customize the backoffice application.

### Let's play

* [Setup in your project](setup.md)
* [Theme Configuration](theme-configuration.md)

### Entities Management

* [YAML Entity description](yaml-entity-configuration.md)
* [Field types and overrides](field-type-and-overrides.md)
* [Entity Relations](entity-relations.md)

### Pages, Widget and menu

* [Menu Configuration](menu-configuration.md)
  * [Type : section](menu-configuration.md#type-:-section)
  * [Type : submenu](menu-configuration.md#type-:-submenu)
  * [Type : list](menu-configuration.md#type-:-sunshine-list)
  * [Type : page](menu-configuration.md)
  * [Type : route](menu-configuration.md#type-:-route)
  * [Type : external page](menu-configuration.md#type-:-external-page)
  * [Roles & Permissions](menu-configuration.md#roles-and-permissions-configuration)
* [Theme configuration](theme-configuration.md)
* [Page configuration](creating-a-page.md)
  * [YAML Configuration](creating-a-page.md#yaml-definition)
  * [Conventions](creating-a-page.md#conventions)
  * [Title & Description ](creating-a-page.md#title-and-description)
  * [Using widgets](creating-a-page.md#using-widgets)
* [Widgets](untitled.md)
  * [YAML Configuration](untitled.md#yaml-configuration)
  * [Service class creation](untitled.md#service-class-creation)
  * [The Twig view](untitled.md#view-creation)
  * [MessageBag Bus](untitled.md#messagebag-send-informations-to-widget)
  * [Using forms](untitled.md#forms-inside-a-widget)
  * [Redirect and Forward response](untitled.md#redirect-to-current-route)
* [Creation of a new Page with widgets](creating-a-page.md)
  * [YAML Configuration](creating-a-page.md#yaml-definition)
  * [Using Widgets](creating-a-page.md#using-widgets)
* [Creating Widgets](untitled.md)
  * [YAML Configuration](untitled.md#yaml-configuration)
  * [Service class creation](untitled.md#service-class-creation)
  * [The View](untitled.md#view-creation)
  * [The messageBag](untitled.md#messagebag-send-informations-to-widget)
  * [Using Forms in widgets](untitled.md#forms-inside-a-widget)
  * [Redirect to current route](untitled.md#redirect-to-current-route)
  * [Using services in widgets](untitled.md#how-to-inject-services-in-widget-service)

### Crud Widget

* [Generic Widget : List](generic-widgets.md)
  * [YAML Configuration](generic-widgets.md#yaml-configuration)
  * [Configuration Inheritance & Overrides](generic-widgets.md#configuration-inheritance)
  * [Preset values for Filters](generic-widgets.md#override-list-and-filters)
  * [Add/Remove columns or filters](generic-widgets.md#configuration-inheritance)
  * [Configure a datasource for a filter](filtering-custom-values-in-select2-lists.md)

### Security and Roles

* Enabling Authentication
* [Filtering by user role](roles-and-permissions.md)
  * [Widgets](roles-and-permissions.md#widget)
  * [Menu](roles-and-permissions.md#menu)
  * [Pages](roles-and-permissions.md#page)

### Filtering List

* [Default filter value](filtering-default-values.md)
* [Multiple filter values](filtering-options-multiple.md)

### Ordered list

* How to order lists

