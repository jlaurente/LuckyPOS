app.controller('UserCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  pre_blocker('on');
  Data.get('getUsers').then(function (results) {
    $scope.userList = results;
     pre_blocker('off');
  });
  $scope.selectedRow = null;  // initialize our variable to null
  $scope.setClickedRow = function(index){  //function that sets the value of selectedRow to current index
    $scope.selectedRow = index;
  }
  $scope.deleteUser = function(id){
    SweetAlert.swal({
        title: "Are you sure?", //Bold text
        text: "You want to delete this user.", //light text
        type: "warning", //type -- adds appropiriate icon
        showCancelButton: true, // displays cancel btton
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, proceed!",
        closeOnConfirm: true, //do not close popup after click on confirm, usefull when you want to display a subsequent popup
        closeOnCancel: true
    }, 
    function(isConfirm){ //Function that triggers on user action.
     
      if(isConfirm){
        Data.get('deleteUser/'+id).then(function (results) {
            Data.toast(results);
            if(results.status == "success") {
              $route.reload();
            }
        });
      } else {
          setTimeout($.loadingBlockHide, true);
      }
    });
  }
 
  $scope.logout = function () {
      Data.get('logout').then(function (results) {
          Data.toast(results);
          $location.path('login');
      });
  }
});

app.controller('AddUserCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  $scope.users = {}
  Data.get('getRole').then(function (results) {
    $scope.roleList = results;
  });

  $scope.addUser = function(customer){
    Data.post('addUser',{customer: customer}).then(function (results) {
      Data.toast(results);
      if(results.status == "success") {
        $route.reload();
      }
    });
  }
 
  $scope.logout = function () {
      Data.get('logout').then(function (results) {
          Data.toast(results);
          $location.path('login');
      });
  }
});

app.controller('EditUserCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {

  Data.get('getRole').then(function (results) {
    $scope.roleList = results;
  });
  Data.get('getUser/'+$routeParams.id).then(function(results){
    $scope.listUser = results;
    console.log(results);
    for(var i=0;i<$scope.listUser.length;i++){
      $scope.role_id = $scope.listUser[i]['role_id'];
    }
  });
  
  $scope.updateUser=function(customer){
    Data.put('updateUser/'+$routeParams.id,{customer: customer}).then(function (results) {
      Data.toast(results);
      $location.path('users');
    });
  }
 
  $scope.logout = function () {
    Data.get('logout').then(function (results) {
      Data.toast(results);
      $location.path('login');
    });
  }
});

app.controller('ChangePassCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  // pre_blocker('on');
  Data.get('getUser/'+$routeParams.uid).then(function(results){
    $scope.listUser = results;
  });
 
  $scope.changePass = function(user){
    Data.put('changePass/'+$routeParams.uid,{user: user}).then(function (results) {
      Data.toast(results);
      user.oldPass='';
    });
  }
 
  $scope.logout = function () {
    Data.get('logout').then(function (results) {
        Data.toast(results);
        $location.path('login');
    });
  }
});
