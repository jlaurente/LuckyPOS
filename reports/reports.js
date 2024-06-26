app.controller('ReportCreditCtrl', function ($modal,$routeParams,$scope,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder, Data, $location,$route,SweetAlert) {
  
  var tabClasses;
  
  function initTabs() {
    tabClasses = ["","","",""];
  }
  
  $scope.getTabClass = function (tabNum) {
    return tabClasses[tabNum];
  };
  
  $scope.getTabPaneClass = function (tabNum) {
    return "tab-pane " + tabClasses[tabNum];
  }
  
  $scope.setActiveTab = function (tabNum) {
    initTabs();
    tabClasses[tabNum] = "active";
  };
  

  
  //Initialize 
  initTabs();
  $scope.setActiveTab(1);
  var today = new Date();
  var date = today.getFullYear()+''+(today.getMonth()+1)+''+today.getDate();
  var time = today.getHours()+''+today.getMinutes()+''+today.getSeconds();
  $scope.IsCreditDetail = true;
  $scope.IsSummary = false;
  $scope.IsCash = false;

  $scope.exportCreditDetails = function (option) {
      switch (option) {
          case 'excel': $scope.$broadcast('export-excel', {});
              break; 
          default: console.log('no event caught'); 
      }
  }
  
  $scope.creditDetails = function(){
    Data.get('getCompany').then(function (results) {
      $scope.companyList = results;
    });
    var modal_popup = angular.element('#creditDetails');
    modal_popup.modal('show');
  };
  $scope.summaryDetails = function(){
    Data.get('getCompany').then(function (results) {
      $scope.companyList = results;
    });
    var modal_popup = angular.element('#summaryDetails');
    modal_popup.modal('show');
  };
  $scope.cashDetails = function(){
    var modal_popup = angular.element('#cashDetails');
    modal_popup.modal('show');
  };
  $scope.soa = function(){
    Data.get('getCompany').then(function (results) {
      $scope.companyList = results;
    });
    var modal_popup = angular.element('#soa');
    modal_popup.modal('show');
  };
  $scope.adjustment = function(){
    Data.get('getCompany').then(function (results) {
      $scope.companyList = results;
    });
    var modal_popup = angular.element('#adjustment');
    modal_popup.modal('show');
  };
  $scope.payment = function(){
    Data.get('getCompany').then(function (results) {
      $scope.companyList = results;
    });
    var modal_popup = angular.element('#payment');
    modal_popup.modal('show');
  };

  $scope.apply_credit_report = function () {
    Data.post('CreditTrxnReport',
    {
      'startdate': $("#credit_startdate").val(),
      'enddate': $("#credit_enddate").val(),
      'comp_id': $("#comp_id").val(),
    } ).then(function (results) {
     if(results.status == "error") {
        Data.toast(results);
      }else{ 
        $scope.credit_report = results;
      }
    });
    $scope.IsCreditDetail = true;
    $scope.IsSummary = false;
    $scope.IsPayment = false;
    $scope.IsAdjustment = false;
    $scope.IsCash = false;
  }
  $scope.summary_report = function (barcodes) {
    Data.post('CreditSummaryTrxnReport',
    {
      'summary_startdate': $("#summary_startdate").val(),
      'summary_enddate': $("#summary_enddate").val(),
      'summary_comp_id': $("#summary_comp_id").val(),
    } ).then(function (results) {
      $scope.credit_summary_report = results;
    });
    
    $scope.IsCash = false;
    $scope.IsAdjustment = false;
    $scope.IsCreditDetail = false;
    $scope.IsPayment = false;
    $scope.IsSummary = true;
  }
  $scope.apply_cash_report = function () {
    Data.post('getOrderCash',
    {
      'startdate': $("#cash_startdate").val(),
      'enddate': $("#cash_enddate").val(),
    } ).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
      }else{ 
        $scope.cash_report = results;
      }
      $scope.IsCreditDetail = false;
      $scope.IsSummary = false;
      $scope.IsAdjustment = false;
      $scope.IsPayment = false;
      $scope.IsCash = true;
    });
  }
  
  $scope.apply_adjustment_report = function () {
    Data.post('AdjustmentReport',
    {
      'startdate': $("#adjustment_startdate").val(),
      'enddate': $("#adjustment_enddate").val(),
      'adjustment_comp_id': $("#adjustment_comp_id").val(),
    } ).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
      }else{
        $scope.adjustment_report = results;
      }
    });
    $scope.IsAdjustment = true;
    $scope.IsCreditDetail = false;
    $scope.IsSummary = false;
    $scope.IsCash = false;
    $scope.IsPayment = false;
  }
  $scope.apply_payment_report = function () {
    Data.post('PaymentReport',
    {
      'startdate': $("#payment_startdate").val(),
      'enddate': $("#payment_enddate").val(),
      'comp_id': $("#comp_id").val(),
    } ).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
      }else{
        $scope.payment_report = results;
      }
    });
    $scope.IsPayment = true;
    $scope.IsAdjustment = false;
    $scope.IsCreditDetail = false;
    $scope.IsSummary = false;
    $scope.IsCash = false;
  }

  $scope.soa_report = function () {
    Data.post('SOAReport',
    {
      'comp_id': $("#soa_comp_id").val()
    } ).then(function (results) {
      if(results.status == "error") {
        Data.toast(results);
      }else{
        var name;
        for(var i = 0; i < results.length; i++){
          name = results[i]['comp_name'];
        }
        window.open('/luckypos/api/soa.php?comp_id='+$("#soa_comp_id").val()+'&comp_name='+name+'&due_date='+$("#due_date").val(), '_blank');
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
