# YAML Entity List Sort

The configuration of the entity list order can be done using an YAML file. This file should be imported into your application configuration.

This is a configuration file sorting on ID attribute :

```yaml
tellaw_sunshine_admin:
    entities:
        partner :
            list:
                fields:
                    id :
                        label: Identifiant
                        order: asc
```

| Option | Description | Required | Possible values |
| --- | --- |
| order | Order direction | No | **asc** \| **desc** |

{% hint style="danger" %}
The order attribute can only be applied to ONE field.
{% endhint %}

