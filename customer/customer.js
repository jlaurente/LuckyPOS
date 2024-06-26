app.filter('minLength', function(){
  return function(input, len, pad){
    input = input.toString(); 
    if(input.length >= len) return input;
    else{
      pad = (pad || 0).toString(); 
      return new Array(1 + len - input.length).join(pad) + input;
    }
  };
});

app.controller('CustomerCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  pre_blocker('on');
  Data.get('getCustomers').then(function (results) {
    $scope.customerList = results;
     pre_blocker('off');
  });
  $scope.selectedRow = null;  // initialize our variable to null
  $scope.setClickedRow = function(index){  //function that sets the value of selectedRow to current index
    $scope.selectedRow = index;
  }
  $scope.deleteUser = function(id){
    SweetAlert.swal({
        title: "Are you sure?", //Bold text
        text: "You want to remove this customer.", //light text
        type: "warning", //type -- adds appropiriate icon
        showCancelButton: true, // displays cancel btton
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, proceed!",
        closeOnConfirm: true, //do not close popup after click on confirm, usefull when you want to display a subsequent popup
        closeOnCancel: true
    }, 
    function(isConfirm){ //Function that triggers on user action.
     
      if(isConfirm){
        Data.get('deleteCustomer/'+id).then(function (results) {
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

app.controller('AddCustomerCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  $scope.users = {}
  Data.get('getCompany').then(function (results) {
    $scope.roleList = results;
  });

  $scope.addCustomer = function(customer){
    Data.post('addCustomer',{customer: customer}).then(function (results) {
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

app.controller('EditCustomerCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {

  Data.get('getCompany').then(function (results) {
    $scope.roleList = results;
  });
  Data.get('getCustomer/'+$routeParams.id).then(function(results){
    $scope.listCustomer = results;
    console.log(results);
    for(var i=0;i<$scope.listCustomer.length;i++){
      $scope.comp_id = $scope.listCustomer[i]['comp_id'];
    }
  });
  
  $scope.updateCustomer=function(customer){
    Data.put('updateCustomer/'+$routeParams.id,{customer: customer}).then(function (results) {
      Data.toast(results);
      $location.path('customer');
    });
  }
 
  $scope.logout = function () {
    Data.get('logout').then(function (results) {
      Data.toast(results);
      $location.path('login');
    });
  }
});
