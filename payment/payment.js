app.controller('CreditPaymentCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  $scope.IsVisible = true;
  $scope.ScanBarcode = function(customer){
    Data.post('search_account',{customer:customer}).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
        $route.reload();
      }else{
        if(results['barcodecnt']>1){
          console.log($scope.barcode_list = results['cust']);
          $scope.IsBarcode = true;
        }else{
          $scope.custList = results;
        }
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

  $scope.addCreditPayment = function(customer){
    if(customer.amount_pay > 300.00 ){
      SweetAlert.swal({
        title: "Oops!", //Bold text
        text: "Amount is GREATER than 300.Would you like to proceed?.", //light text
        type: "warning", //type -- adds appropiriate icon
        showCancelButton: true, // displays cancel btton
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, proceed!",
        closeOnConfirm: true, //do not close popup after click on confirm, usefull when you want to display a subsequent popup
        closeOnCancel: true
      }, 
      function(isConfirm){ //Function that triggers on user action.
        if(isConfirm){
          Data.post('addCreditPayment',
          {
            'cust_id': customer.cust_id,
            'comp_id': customer.comp_id,
            'amount_pay': customer.amount_pay,
          } ).then(function (results) {
            Data.toast(results);
            if(results.status == "success") {
              $route.reload();
            }
          });
        } else {
          setTimeout($.loadingBlockHide, true);
        }
      });
    }else{
      Data.post('addCreditPayment',
      {
        'cust_id': customer.cust_id,
        'comp_id': customer.comp_id,
        'amount_pay': customer.amount_pay,
      } ).then(function (results) {
        Data.toast(results);
        if(results.status == "success") {
          $route.reload();
        }
      });
    }
  }

  $scope.logout = function () {
      Data.get('logout').then(function (results) {
          Data.toast(results);
          $location.path('login');
      });
  }
});

app.controller('CashPaymentCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  
  $scope.change = 0.0;
  $scope.calculate = function(){
    $scope.change = $scope.cash_amount - $scope.amount_pay;
  }
  
  $scope.addCashPayment = function(){
    Data.post('addCashPayment',{
      'amount_pay': $scope.amount_pay,
      'cash_amount': $scope.cash_amount
    }).then(function (results) {
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

app.controller('CompanyPaymentCtrl', function ($routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  $scope.IsVisibleDuedate = true;
  Data.get('getCompany').then(function (results) {
    $scope.companyList = results;
  });

  $scope.apply_search = function(){
    Data.post('getCompanyPayments',{
      'comp_id': $scope.comp_id,
      'due_date': $("#due_date").val(),
    }).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
      }else{
        $scope.PurchasesList = results['Purchases'];
        $scope.PaymentsList = results['Payments'];
        $scope.CreditList = results['CreditAdjustment'];
        $scope.DebitList = results['DebitAdjustment'];
        $scope.SubtotalList = parseFloat($scope.PurchasesList[0]['curr_purchase']??=0.00)+parseFloat($scope.PaymentsList[0]['curr_payment']??=0.00)+parseFloat($scope.CreditList[0]['curr_cred_adj']??=0.00)+parseFloat($scope.DebitList[0]['curr_deb_adj']??=0.00);
        $scope.PrevPurchasesList = results['PrevPurchases'];
        $scope.PrevPaymentsList = results['PrevPayments'];
        $scope.PrevCreditList = results['PrevCreditAdjustment'];
        $scope.PrevDebitList = results['PrevDebitAdjustment'];
        var num1 = parseFloat($scope.PrevPurchasesList[0]['prev_purchase'])+parseFloat($scope.PrevPaymentsList[0]['prev_payment']);
        $scope.PrevSubtotalList = parseFloat($scope.PrevPurchasesList[0]['prev_purchase']??=0.00)+parseFloat($scope.PrevCreditList[0]['prev_cred_adj']??=0.00)+parseFloat($scope.PrevPaymentsList[0]['prev_payment']??=0.00)+parseFloat($scope.PrevDebitList[0]['prev_deb_adj']??=0.00);
        $scope.DueDate = $scope.SubtotalList + $scope.PrevSubtotalList;
        $scope.VisibleDuedate = true;
        $scope.IsVisibleDuedate = false;
      }
    });
  };

  $scope.btnSubmit = function(){
    Data.post('CompanyPaymentDue',{
      'comp_id': $scope.comp_id,
      'due_date': $("#due_date").val(),
      'paymentDue': $("#paymentDue").val(),
      'amoutDue': $scope.DueDate
    }).then(function (results) {
      Data.toast(results);
      if(results.status == "success") {
        $route.reload();
      }
    });
  };
  $scope.logout = function () {
      Data.get('logout').then(function (results) {
          Data.toast(results);
          $location.path('login');
      });
  }
});
