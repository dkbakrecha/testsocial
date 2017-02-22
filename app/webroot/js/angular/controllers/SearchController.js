app.controller('SearchController', function ($scope, $http) {
    $http.get("http://192.168.1.29/testnew/pages/getlibraries.json")
            .success(function (response) {
                $scope.libraries = response.libraries;
            });
            
            
    $scope.search = function(){
        //console.log($scope.mainsearch);
        $http.get("http://192.168.1.29/testnew/pages/getlist/"+$scope.mainsearch+".json")
            .success(function (response) {
                $scope.libsearchs = response.finalArray;
            });
        
        //$scope.searchRes = "asd" + $scope.mainsearch;
    }        
});