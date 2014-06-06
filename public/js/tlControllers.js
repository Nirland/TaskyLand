var tlControllers = angular.module('tlControllers', ['tlServices']);

tlControllers.controller('LoginCtrl', 
    function($scope, $location, AuthService){      
        $scope.submit = function(){
            var promise = AuthService.setCredentials($scope.username, $scope.password);
            promise.then(function(){
                $location.path('/');
            });
        };      
    });
    
tlControllers.controller('MainCtrl', 
    function($scope, AuthService){
        $scope.user =  AuthService.user();
    });    