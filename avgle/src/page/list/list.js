 app.controller('listCtrl', function(Toast,$ionicListDelegate, $state, $ionicViewSwitcher, $timeout, $localStorage, $sessionStorage, $scope, $stateParams, Api, $ionicScrollDelegate, $rootScope, $location) {

     function init() {
         $scope.categoryId = "1";
         $scope.searchKey = $location.search().key || '';
         $scope.page = 0;
         $scope.videos = [];
         $scope.has_more = true;
         initCategories();
     }

     function initCategories() {
         Api.get('categories').then(function(data) {
             if (data.success) {
                 $scope.categories = data.response.categories;
                 $scope.categoryId = "1";
             }
         });
     }


     $scope.focus = function() {
         $timeout(function() { $ionicScrollDelegate.$getByHandle('contentScroll').resize(); }, 500)
     }

     $scope.changeSearchKey = function(searchKey) {
         if (!searchKey) {
             $scope.clearKey();
         }
     }
     $scope.clearKey = function() {
         $timeout(function() { setInputblur(); }, 500);
         $scope.getVideoByCHID($scope.categoryId);
     }
     $scope.getVideoByCHID = function(chid, _page, _limit) {
         $scope.categoryId = chid;
         $scope.has_more = true;
         var page = _page || 1;
         if (page == 1) {
             $scope.searchKey = '';
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

     $scope.searchVideo = function(_keyword, _page, _limit) {
         var page = _page || 1;
         if (page == 1) {
             $scope.page = 1;
             $scope.videos = [];
         }
         var keyword = _keyword || '';
         var limit = _limit || 15;
         var path = ($location.search().key == $scope.searchKey) ? 'jav' : 'search';
         Api.get(path + '/' + encodeURIComponent(keyword) + '/' + page + '?limit=' + limit).then(function(data) {
             if (data.success) {
                 $scope.has_more = data.response.has_more;
                 $scope.videos = $scope.videos.concat(data.response.videos);
                 $scope.$broadcast('scroll.infiniteScrollComplete');


             }
         });
     }

     $scope.loadNextPage = function() {
         $scope.page++;
         if ($scope.searchKey) {
             $scope.searchVideo($scope.searchKey, $scope.page);
         } else {
             $scope.getVideoByCHID($scope.categoryId, $scope.page);
         }
     }
     $scope.view = function(video) {
         $localStorage.video = video;
         // $ionicViewSwitcher.nextDirection("back");
         $state.go("view");
         $ionicViewSwitcher.nextDirection("forward");

     }

     $scope.save = function(vo) {
         var list = $localStorage.saveList || [];
         var flag = false;
         for (var i in list) {
             if (list[i].vid == vo.vid) {
                 flag = true;
                 break;
             }
         }
         if (!flag) list.unshift(vo);
         else{
            Toast.show("您已经收藏了该电影");
           }
         $localStorage.saveList = list;
         $ionicListDelegate.closeOptionButtons();
     }

     function setInputblur() {
         // var body=document.getElementsByTagName("body")[0];
         // body.focus();
         document.getElementById("input_key").blur();
         document.getElementById("select_cate").blur();
     }
     $scope.submit = function($event, searchKey) {
         $scope.searchVideo(searchKey);
         setInputblur();
     }
     $scope.change = function($event, categoryId) {
         $scope.getVideoByCHID(categoryId);
         setInputblur();
     }
     $scope.$on('$ionicView.loaded', function() {
         console.log("list", "$ionicView.loaded")
         init();
     });
 });