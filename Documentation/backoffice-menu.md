# Backoffice menu

The menu of the back-office is fully configurable. It may contain the following items :


| Item                    | Description                                              | Implemented     |
|-------------------------|----------------------------------------------------------|-----------------|
| section                | Represent a section in the menu                          | Yes             |
| subMenu                | Represent a submenu in the menu                          | Yes             |
| ~~sunshineCrudList~~       | ~~Reference to a CRUD list component~~                       | ~~Not yet~~         |
| ~~sunshineCrudEdit~~       | ~~Reference to a CRUD Edit Component~~                       | ~~Not yet~~         |
| sunshinePage           | Reference to a Sunshine configured Page loading widgets  | Yes             |
| external               | Reference to an External URL                             | Yes             |

The order of elements described in the application configuration may be thee exact order of items in the menu

## Common item description & format

```
tellaw_sunshine_admin_entities:
    menu :
        -
            identifier : myPageId
            type : sunshinePage
            parameters : 
                myparameter : myvalue            

        - 
            ... Item 2
```

The menu is a list of pages. Each page is defined by its type and a uniq Id in the menu

## @Section

```
    type : section
    children :
        -
            // Any Menu element

```

## @SubMenu

```
    type : subMenu
    children :
        -
        // Any Menu element

```


## @SunshineCrudList

```
        identifier : myPageId
        type : sunshineCrudList
        parameters : 
            entity : myEntity
            searchKey : defaultSearchKey

```

## @SunshineCrudEdit
```
        identifier : myPageId
        type : sunshineCrudList
        parameters : {
            entity : myEntity
            targetId : myObjectId

```

## @SunshinePage
```
        identifier: myPageId
        type: sunshinePageId

```

Where myPageConfigurationName stands for the configuration name in the application for the page description. Identifier name must represent the configuration name

## @External
```
        identifier: myPageId
        type: external
        parameters :
            targetUrl : http://www.google.fr
            target : _blank

```

Where target may accept any HTML accepted value.
