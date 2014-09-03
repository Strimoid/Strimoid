angular.module('app').controller('UserSettings', function($scope, $http) {
    $scope.blockDomain = function(domain) {
        $http.post('/me/blocked_domain', { domain: domain }).success(function(data){
            $scope.blockedDomains.push(data.domain);
        });
    };

    $scope.unblockDomain = function(domain) {
        $http.delete('/me/blocked_domain', { domain: domain }).success(function(data){
            $scope.blockedDomains = _.without($scope.domains, data.domain);
        });
    };
});
