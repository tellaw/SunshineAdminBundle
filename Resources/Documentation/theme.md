# Theme Configuration

# Introduction

The configuration of the theme can be done using an YAML file. This file should be imported into your application configuration.

The theme configuration file should start as this :
```
tellaw_sunshine_admin:
    theme :
        logo:
            url: logo.png
            alt: logo
            external_url: false
        name: Application Nama
```

NB : block theme is not required.

Here is how to include your configuration file to your config.yml standard file :
```
imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: services.yml }
...
    - { resource: sunshine/theme.yml }
...
```
config.yml file


## Section Logo

This section allows you to configure the logo of your project.

```
...
    logo:
        url: logo.png
        alt: logo
...
```
| Item                          | Description                                            | Required|
|-------------------------------|--------------------------------------------------------|----------
| url                           | Url of logo on project                                 | No      |
| alt                           | The text that appears when the image is unavailable    | No      |
| external_url                  | Check if logo is external url                          | No      |