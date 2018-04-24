app.controller("starCtrl", function($ionicViewSwitcher,$state,$ionicListDelegate,$rootScope,$location,$ionicScrollDelegate,$loading, $scope, $http,$sessionStorage,$localStorage) {
    function init() {
    	$scope.videos=$localStorage.saveList;
    }
    $scope.$on('$ionicView.enter', function(e) {
            init();
            console.log("star","$ionicView.enter");
      });
    $scope.delete=function(index){
    	$scope.videos.splice(index,1);
    	$localStorage.saveList=$scope.videos;
        $ionicListDelegate.closeOptionButtons();
    }
    $scope.view = function(video) {
            $localStorage.video = video;
             // $ionicViewSwitcher.nextDirection("back");
             $state.go("view");
            $ionicViewSwitcher.nextDirection("forward");
           
        }
});