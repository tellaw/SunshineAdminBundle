# Theme Configuration

The configuration of the theme can be done using an YAML file. This file should be imported into your application configuration.

The theme configuration file should start as this :

```yaml
tellaw_sunshine_admin:
    theme :
        logo:
            url: logo.png
            alt: logo
            external_url: false
        name: Sunshine | Dashboard
```

{% hint style="info" %}
**Note**: block theme is not required.
{% endhint %}

Including the file in your config :

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: services.yml }
...
    - { resource: sunshine/theme.yml }
...
```

## Section Logo

This section allows you to configure the logo of your project.

```text
...
    logo:
        url: logo.png
        alt: logo
...
```

| Item | Description | Required |
| --- | --- | --- | --- |
| url | Url of logo on project | No |
| alt | The text that appears when the image is unavailable  | No |
| external\_url | Check if logo is external url   | No |

