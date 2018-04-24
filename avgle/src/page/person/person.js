app.controller("personCtrl",function($scope,$localStorage){
    $scope.$on('$ionicView.loaded', function() {
            console.log("person","$ionicView.loaded")
            // init();
        });
});