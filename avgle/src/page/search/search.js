app.controller("searchCtrl", function($timeout,Api, $location, $ionicScrollDelegate, $scope) {
    $scope.searchVideo = function(_keyword, _page, _limit) {
        var page = _page || 1;
        if (page == 1) {
            $scope.page = 1;
            $scope.videos = [];
            $ionicScrollDelegate.$getByHandle('searchScroll').scrollTop(true);
        }
        var keyword = _keyword || '';
        if(!keyword) return;
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

    $scope.submit = function(searchKey) {
        $ionicScrollDelegate.$getByHandle('searchScroll').scrollTop(true);
        $scope.searchVideo(searchKey);
    }

    $scope.loadNextPage = function(searchKey) {
        $scope.page++;
        $scope.searchVideo(searchKey, $scope.page);
    }

    $scope.$on('$ionicView.enter', function(e) {
        init();
        console.log("search", "$ionicView.enter");
    });

    function init() {
        $scope.searchKey=$location.search().key;
        if($scope.searchKey)$scope.has_more=true;
        document.getElementById("input_key").focus();
    }
    $scope.scroll = function() {
        var content = $ionicScrollDelegate.$getByHandle('searchScroll');
        var pos = content.getScrollPosition();
        if (pos.top > 600) {
            $timeout(function() { $scope.isBtnTop = true },300);
        } else {
            $timeout(function() { $scope.isBtnTop = false },300);
        }
    }
    $scope.scrollTop = function($event) {
        var ele = $event.target;
        if (ele.classList) ele.classList.add("activated");
        else ele.className += " activated";
        $timeout(function() {
            if (ele.classList) ele.classList.remove("activated");
            else ele.className = ele.className.replace('activated', '');
        },300);
        $ionicScrollDelegate.$getByHandle('searchScroll').scrollTop(true);
    }
    
    $scope.search=function(keyword){
        $scope.searchKey=keyword;
        $scope.searchVideo(keyword);
    }
});