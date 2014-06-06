var tlApp = angular.module('tlApp', ['ngRoute', 'tlControllers', 'tlServices']);

//route config
tlApp.config(function($routeProvider, UserRoles) {
    $routeProvider.
        when('/login', {
            templateUrl: 'partials/login.html',
            controller: 'LoginCtrl',
            access: UserRoles.ANON
        }).
        when('/', {
            templateUrl: 'partials/main.html',
            controller: 'MainCtrl',
            access: UserRoles.USER
        }).        
        otherwise({
            redirectTo: '/'
        });
});

//Register error interceptor
tlApp.config(function($httpProvider) {
    $httpProvider.interceptors.push('ErrorInterceptor');
});

//access route control
tlApp.run(function($rootScope, $location, AuthService, UserRoles) {
    $rootScope.$on('$routeChangeStart', function(event, next, current) {
        if ( !AuthService.testCredentials(next.access) ) {
            $location.path( '/login' );
        }
    });    
});

//access view control
tlApp.directive('access', function($rootScope, AuthService) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var prevDisp = element.css('display');
            $rootScope.$watch('user.role', function(role) {
                if(!AuthService.testCredentials(attrs.access))
                    element.css('display', 'none');
                else
                    element.css('display', prevDisp);
            });
        }
    };
});