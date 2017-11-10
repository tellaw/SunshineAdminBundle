# Backoffice Page

Pages are items describing a page element of the back office.

It may contain any Widget and describe it layout. See Widget documentation for detailed informations about each of them.

## Page Configuration

A page is divided into rows, and each rows into columns. Each row is on a 12 element's grid. Widget's may not have any pre-defined number of cols, to adapt to any size from 1 to 12 columns.
 
## Widget as a Controller (WAAC)

This kind of widget is based on the usage of a standalone controller.
The controller must respect a fingerprint of parameters.

### YAML Configuration

```
    widget1 :
            title : Liste de projets
            columns : 6
            type : list
            preload : false
            parameters :
                newRoute : my_route_for_new
                editRoute : my_route_for_edit
                entityName : project
```
This sample, shows you how to setup the call of a WAC ( Widget as Controller ) from within your application.
By default, preload is set to false.

### Creation the controller Action

```
/**
* The Widget as a Controller is an action receiving as parameter 
* pageName, row and widgetName, and return an HTML Fragment.
*/
public function widgetCrudListAction(Request $request, $pageName, $row, $widgetName)
```

This easy to setup widget has an important drawback, it cannot access to the page request.
This separation may cause troubles for forms datas. As workaround, we do suggest to use the Widget as a Service method.

### Ajax loaded widgets

By default, widgets are loaded using Ajax. Nothing has to be done to achieve this!

### Preload widgets

Just set the preload option to true.

The configuration we used earlier may now look like this :

```
    widget1 :
            title : Liste de projets
            columns : 6
            type : list
            preload : true
            parameters :
                newRoute : my_route_for_new
                editRoute : my_route_for_edit
```   

 
## WAAS : Widget As A Service

This method define a widget using a Symfony service instead of a controller. The service must extends the AbstractWidget class from the bundle.

### Configure the service

```
  teamtracking.widget.monthlyreporting:
    class: AppBundle\Service\Widgets\MonthlyReport
    parent: sunshine.widgets.abstract
    calls:
        - [ setReportService, ['@teamtracking.report']]
        - [ setFormFactory, ['@form.factory']]
    tags:
      - {name: sunshine.widget }
```

A widget is a service declared with the tag "sunshine.widget". It must extends AbsractWidget.
Its configuration in service.yml should declare as parent 'sunshine.widgets.abstract' in order to configure its constructor.

If you need to inject services, you should use calls to your class.

### Create the widget

The minimum widget must be like this :

```
<?php
namespace Tellaw\SunshineAdminBundle\Service\Widgets;

use Tellaw\SunshineAdminBundle\Service\AbstractWidget;

class EditWidget extends AbstractWidget {

    public function create ( $configuration ) {
        return $this->render( "Default:index", array() );
    }

}

```

It has to extend AbstractWidget and implement the method called 'create'

### Configure your widget in a page

The widget must define a service to be considered as a 'Service Widget'
```
    widget1 :
            title : Liste de projets
            columns : 12
            service : sunshine.widgets.edit
```


