# Widget : Crud LIST

## Description

This widget intends to display a list with some content extracted from a CRUD module.

## Attributes

| Parameter     | Type          | Description   |
|---------------|---------------|---------------|
| entityName    | String        | Name of the entity in the confiiguration files to use |
| pageStart     | Integer       | Page to display (pagination) |
| length        | Integer       | Number of item in the page (default : 10) |
| searchKey     | String        | String to search in the fields described in the configuration |
| filters       | Composed      | Key/ Values of filters to apply format (key$value\|key2$value2\|key3$value3) |
| orderBy       | String        | Attribut to use for ordering |
| orderWay      | String        | ASC or DESC, to decide to sort way |


## Usage in a sunshing page

## Usage in React JSX

```
...
import CrudList from '../widgets/CrudList.jsx';
...

<CrudList index={this.props.index} widget={this.props.widget} />

```


## Used Webservice
