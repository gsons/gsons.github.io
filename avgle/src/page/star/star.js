app.controller("starCtrl", function($timeout,$ionicScrollDelegate, $scope,$localStorage) {
    function init() {
    	$scope.videos=$localStorage.saveList;
       
    }
    $scope.$on('$ionicView.enter', function(e) {
            init();
            console.log("star","$ionicView.enter");
      });
   $scope.scroll=function(){
         var content=$ionicScrollDelegate.$getByHandle('starScroll');
         var pos=content.getScrollPosition();
         if(pos.top>500){
              $timeout(function(){$scope.isBtnTop=true},600);
           }else{
              $timeout(function(){$scope.isBtnTop=false},600);
         }
    }
    $scope.scrollTop=function($event){
        var ele=$event.target;
        if(ele.classList) ele.classList.add("activated");
        else ele.className +=" activated";
        $timeout(function(){
            if(ele.classList) ele.classList.remove("activated"); 
            else ele.className=ele.className.replace('activated','');
        },600);
        $ionicScrollDelegate.$getByHandle('starScroll').scrollTop(true);
    }
});