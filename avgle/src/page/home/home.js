app.controller('homeCtrl', function( $timeout, $scope, Api, $ionicScrollDelegate) {
        function init() {
            $scope.page = 0;
            $scope.has_more = true;
            $scope.videos = [];
            $scope.position='';
            initSlideVideos();
        }

        function initSlideVideos(_order, _page, _limit) {
            var order = _order || 'mr';
            var page = _page || 1;
            var limit = _limit || 5;
            Api.get('videos/' + page + '?o=' + order + '&limit=' + limit).then(function(data) {
                if (data.success) {
                    $scope.slideVideos = data.response.videos;
                }
            });
        }
        $scope.allVideo = function(_order, _page, _limit) {
            var order = _order || 'mv';
            var page = _page || 1;
            if (page == 1) {
                $scope.searchKey = '';
                $scope.page = 1;
                $scope.videos = [];
                $ionicScrollDelegate.$getByHandle('homeScroll').scrollTop(true);
            }
            var limit = _limit || 10;
            Api.get('videos/' + page + '?o=' + order + '&limit=' + limit).then(function(data) {
                if (data.success) {
                    $scope.has_more = data.response.has_more;
                    $scope.videos = $scope.videos.concat(data.response.videos);
                    $scope.$broadcast('scroll.infiniteScrollComplete');
                }
            });
        }

        $scope.loadNextPage = function() {
            $scope.page++;
            $scope.allVideo('mv', $scope.page);
        }
        

        $scope.$on('$ionicView.loaded', function() {
            console.log("home","$ionicView.loaded")
            init();
        });

        $scope.$on('$ionicView.beforeEnter', function(e) {
           $scope.isBtnTop = false;
            console.log("star","$ionicView.beforeEnter");
        });

        $scope.scroll=function(){
             var content=$ionicScrollDelegate.$getByHandle('homeScroll');
             var pos=content.getScrollPosition();
             if(pos.top>600){
                  $timeout(function(){$scope.isBtnTop=true},300);
               }else{
                  $timeout(function(){$scope.isBtnTop=false},300);
             }
        }
        $scope.scrollTop=function($event){
            var ele=$event.target;
            if(ele.classList) ele.classList.add("activated");
            else ele.className +=" activated";
            $timeout(function(){
                if(ele.classList) ele.classList.remove("activated"); 
                else ele.className=ele.className.replace('activated','');
            },300);
            $ionicScrollDelegate.$getByHandle('homeScroll').scrollTop(true);
        }
    })



