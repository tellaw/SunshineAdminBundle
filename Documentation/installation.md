# Installation du module

In the configuration, enable the Symfony serializer component
 
In your config file (app/config/config.yml)
 
```app/config/config.yml
 framework:
     # ...
     serializer: { enable_annotations: true }
     # Alternatively, if you don't want to use annotations
     #serializer: { enabled: true }
```

