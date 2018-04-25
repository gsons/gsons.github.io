 app.controller('listCtrl', function( $timeout, $scope, Api, $ionicScrollDelegate) {

     function init() {
         $scope.categoryId = "1";
         $scope.videos = [];
         $scope.has_more = true;
         initCategories();
         $scope.isBtnTop = false;
     }

     function initCategories() {
         Api.get('categories').then(function(data) {
             if (data.success) {
                 $scope.categories = data.response.categories;
                 $scope.categoryId = "1";
             }
         });
     }

     $scope.getVideoByCHID = function(chid, _page, _limit) {
         $scope.categoryId = chid;
         $scope.has_more = true;
         var page = _page || 1;
         if (page == 1) {
             $scope.page = 1;
             $scope.videos = [];
         }
         var limit = _limit || 10;
         Api.get('videos/' + page + '?c=' + chid + '&limit=' + limit).then(function(data) {
             if (data.success) {
                 $scope.$broadcast('scroll.infiniteScrollComplete');
                 $scope.has_more = data.response.has_more;
                 $scope.videos = $scope.videos.concat(data.response.videos);
             }
         });
     }


     $scope.loadNextPage = function() {
         $scope.page++;
         $scope.getVideoByCHID($scope.categoryId, $scope.page);
     }

     $scope.change = function(categoryId) {
         $scope.getVideoByCHID(categoryId);
     }
     $scope.$on('$ionicView.loaded', function() {
         console.log("list", "$ionicView.loaded")
         init();
     });

     $scope.scroll = function() {
         var content = $ionicScrollDelegate.$getByHandle('listScroll');
         var pos = content.getScrollPosition();
         if (pos.top > 600) {
             $timeout(function() { $scope.isBtnTop = true }, 300);
         } else {
             $timeout(function() { $scope.isBtnTop = false }, 300);
         }
     }
     $scope.scrollTop = function($event) {
         var ele = $event.target;
         if (ele.classList) ele.classList.add("activated");
         else ele.className += " activated";
         $timeout(function() {
             if (ele.classList) ele.classList.remove("activated");
             else ele.className = ele.className.replace('activated', '');
         }, 300);
         $ionicScrollDelegate.$getByHandle('listScroll').scrollTop(true);
     }
 });