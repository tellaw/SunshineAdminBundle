---
description: Setup & Configuration of the bundle in your project
---

# Setup

### Enable Symfony cerializer component

Add the namespace to be able to extend Sunshine templates:

{% code-tabs %}
{% code-tabs-item title="app/config/config.yml" %}
```yaml
# Twig Configuration
twig:
 [...]
    paths:
        '%kernel.root_dir%/../vendor/tellaw/sunshine-admin-bundle/Tellaw/SunshineAdminBundle/Resources/views': sunshine
 [...]
```
{% endcode-tabs-item %}
{% endcode-tabs %}

### Import the bundle using composer

```bash
composer require tellaw/sunshine-admin-bundle
```

{% hint style="danger" %}
Please check Packagist to find the latest release :   
https://packagist.org/packages/tellaw/sunshine-admin-bundle
{% endhint %}

### Add the bundle to the kernel

```php
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            
            ...
            new Tellaw\SunshineAdminBundle\TellawSunshineAdminBundle(),
            ...
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }
```

Add the following line :

```php
new Tellaw\SunshineAdminBundle\TellawSunshineAdminBundle(),
```

### Update your project configuration

Add to your routing.yml

```yaml
tellaw_sunshine_admin:
    resource: "@TellawSunshineAdminBundle/Controller"
    type:     annotation
    prefix:   /admin
```

{% hint style="info" %}
Note that you can configure the root path of your admin here. Just replace /admin by anything.
{% endhint %}

