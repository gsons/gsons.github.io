app.controller('homeCtrl', function($ionicListDelegate ,$state,$ionicViewSwitcher,$localStorage, $sessionStorage, $scope, $stateParams, Api, $ionicScrollDelegate, $rootScope, $location) {
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
                $ionicScrollDelegate.$getByHandle('listScroll').scrollTop(true);
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
        $scope.view = function(video) {
            $localStorage.video = video;
            // $ionicViewSwitcher.nextDirection("back");
              $state.go("view");
            $ionicViewSwitcher.nextDirection("forward");   
        }

          $scope.save=function(vo){
            var list=$localStorage.saveList||[];
            var flag=false;
            for(var i in list){
                if(list[i].vid==vo.vid){
                    flag=true;break;
                }
            }
            if(!flag) list.push(vo);
            $localStorage.saveList=list;
            $ionicListDelegate.closeOptionButtons();
        }

        $scope.$on('$ionicView.loaded', function() {
            console.log("home","$ionicView.loaded")
            init();
        });

        $scope.scroll=function(){
             var content=$ionicScrollDelegate.$getByHandle('listScroll');
             var pos=content.getScrollPosition();
             $rootScope.homePosition=pos;
        }
    })

