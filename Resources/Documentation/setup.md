# Installation du module

## Step 1/4 : In the configuration, enable the Symfony serializer component
 
In your config file (app/config/config.yml)
Add the namespace to be able to extend Sunshine templates:

```
# Twig Configuration
twig:
 [...]
    paths:
        '%kernel.root_dir%/../vendor/tellaw/sunshine-admin-bundle/Tellaw/SunshineAdminBundle/Resources/views': sunshine
 [...]
```

## Step 2/4 : Add the bundle in your composer JSON.
```
"tellaw/sunshine-admin-bundle": "0.9.0"
```
Please check Packagist to find the latest release : https://packagist.org/packages/tellaw/sunshine-admin-bundle

## Step 3/4 : Add the bundle to the kernel

```
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
```
new Tellaw\SunshineAdminBundle\TellawSunshineAdminBundle(),
```

## Step 4/4 : Update your configuration

Add to your routing.yml
```
tellaw_sunshine_admin:
    resource: "@TellawSunshineAdminBundle/Controller"
    type:     annotation
    prefix:   /admin
```

Configure whatever you want instead of 'admin'.
