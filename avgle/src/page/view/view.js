app.controller('viewCtrl', function($timeout,Toast, $state, $localStorage, $scope, Api, $ionicScrollDelegate, $sce) {
    $scope.$on('$ionicView.enter', function(e) {
        init();
        console.log("view", "$ionicView.enter");
    });

    $scope.$on('$ionicView.beforeLeave', function(e) {
        $scope.video_src = undefined;
        console.log("view", "$ionicView.beforeLeave");
    });


    function init() {
        $scope.page = 0;
        $scope.isBtnTop = false;
        $scope.has_more = true;
        $scope.videos = [];
        $scope.video = $localStorage.video;
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

    $scope.scroll = function() {
        var content = $ionicScrollDelegate.$getByHandle('viewScroll');
        var pos = content.getScrollPosition();
        if (pos.top > 1000) {
            $timeout(function() { $scope.isBtnTop = true }, 600);
        } else {
            $timeout(function() { $scope.isBtnTop = false }, 600);
        }
        $scope.isBtnTop = true 
    }
    $scope.scrollTop = function($event) {
        var ele = $event.target;
        if (ele.classList) ele.classList.add("activated");
        else ele.className += " activated";
        $timeout(function() {
            if (ele.classList) ele.classList.remove("activated");
            else ele.className = ele.className.replace('activated', '');
        }, 600);
        $ionicScrollDelegate.$getByHandle('viewScroll').scrollTop(true);
    }
})