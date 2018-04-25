app.controller("demoCtrl", function(Api,$rootScope, $location, $ionicScrollDelegate, $loading, $scope, $http, $sessionStorage, $localStorage) {
    function init() {
        $scope.showItem = 0;
        // $scope.categories = [{ name: "tab0" }, { name: "tab1" }, { name: "tab2" }, { name: "tab3" }, { name: "tab4" }, { name: "tab5" }, { name: "tab6" }, { name: "tab7" }];
        initCategories();
    }
    $scope.toggleShow = function(index) {
        if(index>=$scope.categories.length) return;
        $scope.showItem = index;
        var dom=document.getElementById("tab-cate-"+index);
        var left=dom.offsetLeft+dom.offsetWidth;
        var offset=left-document.body.clientWidth/2;
        $ionicScrollDelegate.$getByHandle('slideScroll').scrollTo(offset,0,true);
    }

     function initCategories() {
         Api.get('categories').then(function(data) {
             if (data.success) {
                 $scope.categories = data.response.categories;
                 $scope.categoryId = "1";
             }
         });
     }
    init();
});