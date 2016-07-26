var app = angular.module("stock",['ngRoute','angularUtils.directives.dirPagination']);
app.config(['$routeProvider',function($routeProvider){
    $routeProvider
        .when('/codes',{templateUrl:'/views/code.html',controller:'codeCtrl'})
        .when('/kospis',{templateUrl:'/views/kospis.html',controller:'kospiCtrl'})
    	.otherwise({redirectTo:'/codes'});
    // $locationProvider.html5Mode(false); // false
}]);
app.config(function(paginationTemplateProvider) {
    paginationTemplateProvider.setPath('/bower_components/angularUtils-pagination/dirPagination.tpl.html');
});
app.controller('mainCtrl',function($scope, $http){

});
app.controller('codeCtrl',function($scope, $http){
	$scope.codes = [];
	$scope.days = [];
	$scope.newCode = '';
	$scope.loading = false;
	$scope.pageSize = 20;
	$scope.codeOrder = 'id';
	$scope.codeOrderAsc = false;
	$http.get('/code').then(function(res){
		$scope.codes = res.data;
		$scope.codes.length && $scope.seeDetail($scope.codes[0].id, $scope.codes[0].cdName);
	})
	$scope.seeDetail = function(id){
		console.log(id)
	}
	$scope.codeAdd = function(){
		$http.post('/code/create',{"codeNumStr":$scope.newCode})
		.then(function(res){
			if(res.status==200)
				$scope.codes = res.data;
		});
		return false;
	}
	$scope.delete = function(codeid){
		$http.post('/code/destroy',{"codeId":codeid})
		.then(function(res){
			if(res.status==200)
				$scope.codes = res.data;
		});
		return false;
	}
	$scope.seeDetail = function(codeid, cdName){
		$scope.currentName = cdName;
		$http.post('/code/data',{"codeId":codeid})
		.then(function(res){
			$scope.days = res.data;
		});
		return false;
	}
	$scope.crawl = function(){
		$scope.loading = true;
		$http.post('/code/crawl')
		.then(function(res){
			$scope.loading = false;
			$scope.codes = res.data;
		});
		return false;
	}
});
app.controller('kospiCtrl',function($scope, $http){
    $scope.datas = [];
    $scope.loading = false;
    $scope.pageSize = 20;
    $scope.codeOrder = 'id';
    $scope.codeOrderAsc = false;
    $scope.currentDate = null;
    $scope.currentLength = null;
    $http.get('/kospi').then(function(res){
        $scope.datas = res.data;
        $scope.currentDate = res.data[0].ksDate;
        $scope.currentLength = '총'+res.data.length+'건';
    })
    $scope.crawl = function(){
        $scope.loading = true;
        $http.post('/kospi/crawl')
        .then(function(res){
            $scope.loading = false;
            $scope.datas = res.data;
            $scope.currentDate = res.data[0].ksDate;
            $scope.currentLength = '총'+res.data.length+'건';
        });
        return false;
    }
});
app.directive('draggable',['$document',function($document){
    return {
        restrict: "C",
        link: function(scope, element, attr){
                var moving = null;
                var startX = 0, startY = 0, x = 0, y = 0;
                var marginx = 0, marginy = 0;
                element.on('mousedown',function(event){
                        event.preventDefault();
                        moving = angular.element(event.target).parent();
                        while(!moving.hasClass('panel')){
                                moving = moving.parent();
                        }
                        marginx = parseFloat(moving.css('marginLeft').replace('px',''));
                        marginy = parseFloat(moving.css('marginTop').replace('px',''));
                        startX = event.pageX;
                        startY = event.pageY;
                        $document.on('mousemove',mousemove);
                        $document.on('mouseup',mouseup);
                });
                function mousemove(event){
                        x = marginx + (event.pageX - startX);
                        y = marginy + (event.pageY - startY);
                        moving.css({
                                marginTop: y + 'px',
                                marginLeft: x + 'px'
                        })
                }
                function mouseup(){
                        moving = null,  startX = 0, startY = 0, x = 0, y = 0, marginx = 0, marginy = 0;
                        $document.off('mousemove',mousemove);
                        $document.off('mouseup',mouseup);
                }
        }
    }
}]);
// scope.download = function(proj,type){
//         // debugger;
//         var idx = proj.idx;
//         proj.packing_loading = true;
//         scope.http({
//                 method:'POST',
//                 url:'/nafa_do',
//                 data:{mode:'do_packing',idx:proj.idx,type:type},
//                 transformRequest: function(obj) {var str = []; for(var p in obj) str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p])); return str.join("&"); },
//                 headers:{'Content-Type':'application/x-www-form-urlencoded'}
//         }).success(function(data){

//                 try{
//                         if(data.state){
//                                 proj.packing_complete = true;
//                                 if(data.down)
//                                         document.getElementById('hiddenfrm').src = data.down;
//                                 scope.timeout(function(){
//                                         proj.packing_loading = false;
//                                         proj.packing_complete = false;
//                                 },1000);

//                         }else{
//                                 proj.packing_loading = false;
//                         }
//                 }catch(e){}
//                 // scope.saveResult = data;
//                 // scope.editErrorMsg = (data == 'ok')?'':data;
//                 // scope.editSuccessMsg = (data == 'ok')?'저장되었습니다':'';
//                 // scope.projform.$setPristine();
//         });
// }