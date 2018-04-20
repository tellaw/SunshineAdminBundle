# YAML Entity configuration

### Configuration

```yaml
tellaw_sunshine_admin_entities:
  project :                                     // Name of the Entity
       
       configuration:                           // Global description of the entity
           id: id
           class: AppBundle\Entity\Project
           ROLES:
             - ROLE_DEV

       attributes:                              // Attribute description (global)
           name:                                // Name of the property to handle
              label: attribute-label
              type: string
              sortable : true

       form:                                    // Description of the form view
           name:                                // Name of the property to handle
               label: form-label
               placeholder: xxx
               readOnly: false

       list:                                    // Description of the list view
           id:
              label: Identifier
              order: desc
           name:                               // Name of the property to handle
              label: list-label

       filters:                                 // Description of the filters
           name:                               // Name of the property to handle
              label: filter-label

       search:                                  // Description of the search methods
           name:                               // Name of the property to handle
              label: search-label
```

### Configuration Section

This is the global declaration of the entity for the Sunshine Bundle.

| Item | **Description** | **Required** |
| --- | --- | --- | --- |
| id | Identifier of the entity | Yes |
| class | Class path of the Entity | Yes |
| ROLES | list of Roles which may have full access to the entity | No |

### Attributes Section

This section describes each attributes with its defaults values. This makes easy to write only once most of configuration attributes.

| Item | **Description** | **Required** |
| --- | --- | --- | --- |
| label | Default label used for the different views | No |
| type | Type of data \(String / integer / Object / Email / HTML \)... | No |
| sortable | true / false : enable the sorting | No |

### Form Section

| Item | Description | Required |
| --- | --- | --- | --- | --- |
| label | Default label used for the different views | No |
| type | Type of data \(String / integer / Object / Email / HTML \)... | No |
| placeholder | Define a placeholder for the field. | No |
| readOnly | Define if a field must be in a read only status | No |

### List Section

| Item | Description | Required |
| --- | --- |
| label | Default label used for the different views | No |

### Filters Section

| Item | Description | Required |
| --- | --- |
| label | Default label used for the different views | No |

### Search Section

| Item | Description | Required |
| --- | --- |
| label | Default label used for the different views | No |

