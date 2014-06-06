var tlServices = angular.module('tlServices', []);

//User roles constants
tlServices.constant('UserRoles', {
    ADMIN: 'ADMIN',
    USER: 'USER',
    ANON: 'ANON'
});

//User roles hierarchy
tlServices.factory('UserHierarchy', function(UserRoles){    
    return{
        ADMIN: [UserRoles.USER, UserRoles.ANON],
        USER: [UserRoles.ANON],
        ANON: []
    };    
});

//Error Interceptor
tlServices.factory('ErrorInterceptor', function ($q, $location) {
    return {       
        responseError: function (response) {
            if (response.status === 401) {
                $location.path('/login');
            }
            if (response.status >= 404) {
            }
            return $q.reject(response);
        }
    };
});

tlServices.factory('AuthService', function ($http, $rootScope, UserService, UserHierarchy) {    
    $http.defaults.headers.common['Authorization'] = 'Basic ' + sessionStorage.getItem('authdata');
        
    return {
        
        setCredentials: function (username, password) {
            var encoded = btoa(username + ':' + password);
            $http.defaults.headers.common.Authorization = 'Basic ' + encoded;
                        
            return UserService.current().then(function(response){
                sessionStorage.setItem('user', JSON.stringify(response.data));
                $rootScope.user = response.data;
                sessionStorage.setItem('authdata', encoded);            
            });
        },
        
        clearCredentials: function () {
            document.execCommand('ClearAuthenticationCache');
            $http.defaults.headers.common.Authorization = 'Basic ';
            sessionStorage.removeItem('authdata');
            sessionStorage.removeItem('user');
        },
        
        testCredentials: function (access) {
            var user = this.user();            
            if (sessionStorage.getItem('authdata') !== null && user !== null){
                return ((user.role === access) ||
                        (UserHierarchy[user.role].indexOf(access) !== -1));
            }
            return false;
        }, 
                                        
        user : function() {
            if ($rootScope.user === undefined){                
                $rootScope.user = JSON.parse(sessionStorage.getItem('user'));
            }
            return $rootScope.user;            
        }
                        
    };
});

tlServices.factory('UserService', function($http) {

    return {
                
        // get current user
        current : function() {           
            return $http.get('/api/auth');
        },
        
        // get all the users
        index : function() {
            return $http.get('/api/user');
        },

        // get user
        show : function(id) {
            return $http.get('/api/user/' + id);
        },
        
        // create a new user
        store : function(userData) {
            return $http({
                    method: 'POST',
                    url: '/api/user',
                    headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
                    data: $.param(userData)
            });
        },

        // update a existed user
        update : function(id, userData) {
            return $http({
                    method: 'PUT',
                    url: '/api/user/' + id,
                    headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
                    data: $.param(userData)
            });
        },

        // destroy a user
        destroy : function(id) {
            return $http.delete('/api/user/' + id);
        }
    };
});