<!DOCTYPE html>
<html ng-app="stock">
<head>
        <meta charset="UTF-8">
        <title>stock</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/css/base.css">
        <script src="/bower_components/angular/angular.min.js"></script>
        <script src="/bower_components/angular-route/angular-route.min.js"></script>
        <script src="/bower_components/angularUtils-pagination/dirPagination.js"></script>
        <style>
                .packing_column {background-color: #fc6675 !important;}
                .selected_row_color {background-color: #f9e08c !important;}
                a{cursor:pointer;}
                a:focus, a:hover {text-decoration: inherit;
                .transition{transition:all ease-out 0.5s; -webkit-transition: all ease-out 0.5s;}
        </style>
</head>
<body ng-controller="MainController"  ng-cloak class="ng-cloak" style="padding-top: 70px;padding-bottom:70px;">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="opacity:0.8;">
                <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex5-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar" ng-repeat="tab in menus.tabs">{{tab.label}}</span>
                        </button>
                        <a class="navbar-brand" href="#current">stock</a>
                </div>
                <div class="collapse navbar-collapse navbar-ex5-collapse">
                        <ul class="nav navbar-nav">
                                <li ng-class="{active:tab==menus.selectedTab}" ng-repeat="tab in menus.tabs" tab="tab">
                                        <a href="{{tab.link}}" ng-click="menus.selectedTab=tab">{{tab.label}}</a>
                                </li>
                        </ul>
                </div>
    </nav>
    <!-- <div ng-view style="width:100%;"></div> -->
        <!-- <script src="/js/stock.js"></script> -->
</body>
</html>