# Backoffice Page

Pages are items describing a page element of the back office.

It may contain any Widget and describe it layout. See Widget documentation for detailed informations about each of them.

## Page Configuration

A page is divided into rows, and each rows into columns. Each row is on a 12 element's grid. Widget's may not have any pre-defined number of cols, to adapt to any size from 1 to 12 columns.
 
## @Section
 
```
tellaw_sunshine_admin_entities:
        Page :
            id : myPageId
            title : myPageTitle
            parent : parentPageName
            roles : 
                - // Agreed roles 1
                - // Agreed roles 2 ...
            description : My Page description tag
            
            content :
                
                headerrow :                                     // Id of the Row
                    size: 9
                    children :
                
                        widget1:                                // Id of the Widget
                            title : Widget Title on the page
                            type : CrudList
                            parameters :
                                myParameter1 : myParameterValue1


```

## Description of Page Elements

|-------------------------------|---------------------------|---------------------------|-----------------------------------|
| Item                          | Context                   | Description               | Required                          |
| id                            | Page                      | Identifier or the page, should be the name of the YAML file describing the page located in sunshine/pages/ | Yes |
| title                         | Page                      | Title of the page                                             | No |
| parent                        | Page                      | Parent page the configuration is based on                     | No |
| roles                         | Page                      | Array of roles who can access this page                       | No |
| description                   | Page                      | Description of the page                                       | No |
| content                       | Page                      | Composition of the page (Array of elements )                  | No |
| size                          | Row                       | Number of columns used by the row (grid is 12), default : 3   | No |
| children                      | Row                       | Description of the widgets in the row (Array of widgets)      | Yes |
| title                         | Widget                    | Title of the widget, may be displayed inside the widget.      | No |
| type                          | Widget                    | Type of the widget, please read Widget documentation          | Yes |
| parameters                    | Widget                    | Parameters for the widget                                     | No |

