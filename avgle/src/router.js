 app.config(function($stateProvider, $urlRouterProvider) {

     $stateProvider
         .state('app', {
             url: '/app',
             templateUrl: 'tpl/app.html?v=' + $tplVersion,
             abstract: true
         })

         .state('app.home', {
             url: '/home',
             views: {
                 'tab-home': {
                     templateUrl: 'tpl/home.html?v=' + $tplVersion,
                     controller: 'homeCtrl',
                     cache:true
                 }
             }
         })

         .state('app.list', {
             url: '/list?key',
             views: {
                 'tab-list': {
                     templateUrl: 'tpl/list.html?v=' + $tplVersion,
                     controller: 'listCtrl',
                     cache:true
                 }
             }
         })
         
         .state('app.person', {
             url: '/person',
             views: {
                 'tab-person': {
                     templateUrl: 'tpl/person.html?v=' + $tplVersion,
                     controller: 'personCtrl',
                     cache:true
                 }
             }
         })

         .state('view', {
             url: '/view',
             templateUrl: 'tpl/view.html?v=' + $tplVersion,
             controller: 'viewCtrl',
             cache:true
         })

         .state('star', {
             url: '/star',
             templateUrl: 'tpl/star.html?v=' + $tplVersion,
             controller: 'starCtrl',
             cache:true
         })



     $urlRouterProvider.otherwise('/app/home');
 });