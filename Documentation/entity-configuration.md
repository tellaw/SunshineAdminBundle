# Entity Description

## Yaml Configuration

```
tellaw_sunshine_admin_entities:
  project :                                     // Name of the Entity
       
       configuration:                           // Global description of the entity
           id: id
           class: AppBundle\Entity\Project
           ROLES :
             - ROLE_DEV

       attributes:                              // Attribute description (global)
           name:                                // Name of the property to handle
              label: attribute-label
              type : string
              sortable : true

       form:                                    // Description of the form view
           name:                                // Name of the property to handle
               label: form-label
               placeholder : xxx
               readOnly : false

       list:                                    // Description of the list view
           name :                               // Name of the property to handle
              label: list-label

       filters:                                 // Description of the filters
           name :                               // Name of the property to handle
              label: filter-label

       search:                                  // Description of the search methods
           name :                               // Name of the property to handle
              label: search-label
```

## Configuration Section

This is the global declaration of the entity for the Sunshine Bundle.

 | Item  | Description |
 | ----- | ----------- |
 | id    | identifier of the entity |
 | class | Class path |
 | ROLES | list of Roles wich may have full access to the entity |
 
## Attributes Section

This section describes each attributes with its defaults values. This makes easy to write only once most of configuration attributes.

 | Item  | Description |
 | ----- | ----------- |
 | label | Default label used for the different views |
 | type  | Type of data (String / integer / Object / Email / HTML )... |
 | sortable | true / false : enable the sorting |
 
 ## Form Section
 
 This section describes each attributes for the form view.
 
 | Item  | Description |
 | ----- | ----------- |
 | label | Default label used for the different views |
 | type  | Type of data (String / integer / Object / Email / HTML )... |
 | placeholder | Define a placeholder for the field. |
 | readOnly | Define if a field must be in a read only status |
 
 ## List Section
 
 This section describes each attributes for the list views.
 
 | Item  | Description |
 | ----- | ----------- |
 | label | Default label used for the different views |
 
 
 ## Filters Section
 
 This section describes each attributes for the filters. A filter is a restrictive measure on a field. It appears on the list view.
 
 | Item  | Description |
 | ----- | ----------- |
 | label | Default label used for the different views |
  
 
 ## Search Section

 This section describes each attributes for the search. The search will consider the fields with a 'OR' request.
 
 | Item  | Description |
 | ----- | ----------- |
 | label | Default label used for the different views |
 
 
