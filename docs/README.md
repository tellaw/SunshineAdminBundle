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
* Creation of a new Page with widgets
  * YAML Configuration
  * Using Widgets
* Creating Widgets
  * YAML Configuration
  * Service class creation
  * The View
  * The messageBag
  * Using Forms in widgets
  * Redirect to current route
  * Using services in widgets
* Generic Widget : List
  * YAML Configuration
  * Configuration Inheritance & Overrides
  * Preset values for Filters
  * Add/Remove columns or filters
  * Configure a datasource for a filter



### Crud Widget

* Crud list widget, how to re-use

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

