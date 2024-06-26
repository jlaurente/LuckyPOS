app.controller('AdjustmentCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  $scope.IsVisible = true;
  $scope.ScanBarcode = function(customer){
    Data.post('search_account',{customer:customer}).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
        $route.reload();
      }else{
        if(results['barcodecnt']>1){
          // $scope.barcode_list = results['cust'];
          // $scope.IsBarcode = true;
        }else{
          $scope.custList = results;
        }
        $scope.IsVisible = false;
      }
    });
  }

  $scope.EnterCompanyID = function(company){
    Data.post('search_compId',{company:company}).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
        $route.reload();
      }else{
        $scope.compList = results;
        $scope.IsVisible = false;
      }
    });
  }

  $scope.view_barcode = function(id,barcode){
    Data.post('search_barcode',{'id':id, 'barcode':barcode,}).then(function (results) {
      $scope.custList = results;
      $scope.IsBarcode = false;
    });
  }

  $scope.CreditOrderAdjustment = function(adj){
    Data.post('addAdjustmentPayment',
    {
      'trxn_id': adj.trxn_id,
      'cust_id': adj.cust_id,
      'comp_id': adj.comp_id,
      'type': adj.type,
      'amount': adj.amount,
      'reason': adj.reason,
    } ).then(function (results) {
      Data.toast(results);
      if(results.status == "success") {
        $route.reload();
      }
    });
  }

  $scope.CompanyPaymentAdjustment = function(adj){
    Data.post('CompanyPaymentAdjustment',
    {
      'trxn_id': adj.trxn_id,
      'cust_id': adj.cust_id,
      'comp_id': adj.comp_id,
      'type': adj.type,
      'amount': adj.amount,
      'reason': adj.reason,
    } ).then(function (results) {
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