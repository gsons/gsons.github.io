app.directive('ionImg', function() {
    return {
      scope: {
        ngsrc: '@',
        ngopt: '@',
      },
      link: function($scope, $dom) {
        var src = $scope.ngsrc;
        var ngopt = $scope.ngopt;
        var dom_image = angular.element($dom)[0];
        var img_src_default ="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACcAAAAnCAIAAAADwcZiAAAAW0lEQVRYCe3SMQ7AMAzDwLb//6k/UWTnyGQJPWqQgIPfmXmO33d8cQ22ups94YQ9gb7Js+SmhNnFSxP2LLkpYXbx0oQ9S25KmF28NGHPkpsSZhcvTdiz5KabhH9OFAMPqToRyQAAAABJRU5ErkJggg==";
        dom_image.src = img_src_default;
        if (ngopt) {
          var ngopt = ngopt.split(',');
          var offset = ngopt[0];
          var scale = ngopt[1];
          dom_image.width = screen.width + parseInt(offset);
          dom_image.height = dom_image.width * scale;
        }
        var image = new Image();
        image.src = src;
        image.onload = function() {
          dom_image.src = src;
        }
      },
    };
  })

/**
*rjHoldActive指令
*产生一种数据动态涟漪效果
*/
app.directive('rjHoldActive',function($timeout) {
        return {
            restrict: 'AE',
            replace: false,
            link: function(scope, element, iAttrs, controller) {
                element.bind("click",function(event){
                    var ele=document.getElementById("ripple");
                    if(ele.classList) ele.classList.add("animate");else ele.className +=" animate";
                    ele.style.left= (event.pageX-20)+"px";
                    ele.style.top= (event.pageY-20)+"px";
                    $timeout(function(){
                        ele.style.left="-40px";
                        ele.style.top="-40px";
                        if(ele.classList){
                             ele.classList.remove("animate");
                        }
                        else {
                            ele.className=ele.className.replace('animate','');
                        }
                    },200);
                });
            }
        };
})


