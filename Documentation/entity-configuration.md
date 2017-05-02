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
           name:
              label: attribute-label
              type : string
              sortable : true

       form:                                    // Description of the form view
           name:
               label: form-label
               placeholder : xxx
               readOnly : false

       list:                                    // Description of the list view
           name :
              label: list-label

       filters:                                 // Description of the filters
           name :
              label: filter-label

       search:                                  // Description of the search methods
           name :
              label: search-label
```

## Configuration Section

| -- | -- | 
| Item  | Description |
| id    | identifier of the entity |
| class | Class path |
| ROLES | list of Roles wich may have full access to the entity |

