app.controller('viewCtrl', function(Toast,$state,$localStorage,$sessionStorage,$scope, $stateParams, Api, $ionicScrollDelegate, $rootScope, $location, $sce) {
        $scope.$on('$ionicView.enter', function(e) {
            init();
            console.log("view","$ionicView.enter");
        });

         $scope.$on('$ionicView.beforeLeave', function(e) {
            $scope.video_src=undefined;
            console.log("view","$ionicView.beforeLeave");
        });


        function init() {
            $scope.page = 0;
            $scope.has_more = true;
            $scope.videos = [];
            $scope.video=$localStorage.video;
            $scope.allVideo($scope.page);
            $scope.video_src = $sce.trustAsResourceUrl($scope.video.embedded_url);
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
            var limit = _limit || 15;
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
            $scope.allVideo('', $scope.page);
        }

         $scope.save=function(vo){
            var list=$localStorage.saveList||[];
            var flag=false;
            for(var i in list){
                if(list[i].vid==vo.vid){
                    flag=true;break;
                }
            }
            if(!flag) list.unshift(vo);
             else{
            Toast.show("您已经收藏了该电影");
           }
            $localStorage.saveList=list;
            $ionicListDelegate.closeOptionButtons();
        }
        
        $scope.view = function(video) {
            $localStorage.video = video;
            $state.go("view",{},{reload:true})
        }
    })