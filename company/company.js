app.controller('CompanyCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  Data.get('getCompany').then(function (results) {
    console.log(results)
    $scope.companyList = results;
  });

  $scope.addCompany = function(customer){
    Data.post('addCompany',{customer: customer}).then(function (results) {
      Data.toast(results);
      if(results.status == "success") {
        $route.reload();
      }
    });
  }

  $scope.deleteCompany = function(id){
    SweetAlert.swal({
        title: "Are you sure?", //Bold text
        text: "You want to delete this company.", //light text
        type: "warning", //type -- adds appropiriate icon
        showCancelButton: true, // displays cancel btton
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, proceed!",
        closeOnConfirm: true, //do not close popup after click on confirm, usefull when you want to display a subsequent popup
        closeOnCancel: true
    }, 
    function(isConfirm){ //Function that triggers on user action.
     
      if(isConfirm){
        Data.get('deleteCompany/'+id).then(function (results) {
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

app.controller('EditCompanyCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
 
  Data.get('getCompany/'+$routeParams.id).then(function(results){
    $scope.listCompany = results;
  });

  $scope.editCompany=function(customer){
    Data.put('updateCompany/'+$routeParams.id,{customer: customer}).then(function (results) {
      Data.toast(results);
      $location.path('company');
    });
  }

  $scope.logout = function () {
    Data.get('logout').then(function (results) {
      Data.toast(results);
      $location.path('login');
    });
  }
});