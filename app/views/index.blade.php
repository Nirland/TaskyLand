<!doctype html>
<html lang="en" ng-app="tlApp">
<head>
  <meta charset="utf-8">
  <title>TaskyLand</title>
  {{ HTML::style('css/tl.css') }}
  {{ HTML::style('css/bootstrap.min.css') }}
</head>
<body>  
    <div ng-view></div>
    {{ HTML::script('js/angular.min.js') }}
    {{ HTML::script('js/angular-route.min.js') }}
    {{ HTML::script('js/tlApp.js') }}
    {{ HTML::script('js/tlServices.js') }}
    {{ HTML::script('js/tlControllers.js') }}
</body>
</html>
