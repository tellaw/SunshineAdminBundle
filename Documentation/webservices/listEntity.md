# Webservice : Entity Listing page

## Request

The path used to retrieve list content :

```
     /crud/list/{entityName}/{pageStart}/{length}/{searchKey}/{filters}/{orderBy}/{orderWay}
     {"GET", "POST"}
Eg :
     http://local.dev/teamtracking/web/app_dev.php/admin/crud/list/project/1/5/E/name$Ma/name/desc
```

## Parameters

The available parameters are :

| Parameter     | Type          | Description   |
|---------------|---------------|---------------|
| entityName    | String        | Name of the entity in the confiiguration files to use |
| pageStart     | Integer       | Page to display (pagination) |
| length        | Integer       | Number of item in the page (default : 10) |
| searchKey     | String        | String to search in the fields described in the configuration |
| filters       | Composed      | Key/ Values of filters to apply format (key$value\|key2$value2\|key3$value3) |
| orderBy       | String        | Attribut to use for ordering |
| orderWay      | String        | ASC or DESC, to decide to sort way |


## Response

```
{
    fields : {
        id : {
            type : int,
            label : 'Id',
            sortable : false,
        },
        name : {
            type : String,
            label : 'Nom',
            sortable : true
        }
    },
    lists : {
    
    },
    context : {
        numberOfItemPerPage : 10,
        page : 1,
        searchTerm : '',
        filters : {
            name : 'test'
        }
    }
}
```
