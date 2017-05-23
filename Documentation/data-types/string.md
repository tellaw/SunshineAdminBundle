# Data Type : String

## Configuration of field

### YAML

In the Yaml configuration, the string field can be mapped using :

```
           name:
              label: User name
              type : string
```

*Type must be lowercase*


### ORM Datatype mapping

The String front value must be mapped by any 'varchar' property.


## React templates

This field must be imported using the following method :

```
    import StringField from '../data_types/string/StringField.jsx';
```


## Validations

This field doesn't handle any validation yet
