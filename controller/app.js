function pre_blocker(action){ 
    switch(action){   
    case "on":      
    $(".pre_blocker").show();   
    break;        
    case "off":       
    $(".pre_blocker").hide();     break;  
    }
}
var app = angular.module('joy', ['oitozero.ngSweetAlert','datatables', 'datatables.buttons','ui.bootstrap','ngRoute', 'ngAnimate', 'toaster','angularUtils.directives.dirPagination']);
app.config(['$routeProvider',
function ($routeProvider) {
    $routeProvider.
    when('/login', {
        title: 'Login Page',
        templateUrl: 'pages/login.html',
        controller: 'authCtrl'
    })
    .when('/dashboard', {
        title: 'Dashboard',
        templateUrl: 'pages/dashboard.html',
        controller: 'DashboardCtrl'
    })
    
    // User Management
    .when('/users', {
        title: 'User Management',
        templateUrl: 'user_pages/user.html',
        controller: 'UserCtrl'

    }).when('/new_user', {
        title: 'New User',
        templateUrl: 'user_pages/new_user.html',
        controller: 'AddUserCtrl'

    }).when('/edit_user/:id', {
        title: 'Edit User',
        templateUrl: 'user_pages/edit_user.html',
        controller: 'EditUserCtrl'

    }).when('/change_password/:uid/', {
        title: 'Change Password',
        templateUrl: 'user_pages/change_password.html',
        controller: 'ChangePassCtrl'

    })

    // Company Management
    .when('/company', {
        title: 'Company Management',
        templateUrl: 'company/company.html',
        controller: 'CompanyCtrl'

    }).when('/new_company', {
        title: 'New Company',
        templateUrl: 'company/new_company.html',
        controller: 'CompanyCtrl'

    }).when('/edit_company/:id', {
        title: 'Edit Company',
        templateUrl: 'company/edit_company.html',
        controller: 'EditCompanyCtrl'

    })

    // Category Management
    .when('/customer', {
        title: 'Customer Management',
        templateUrl: 'customer/customer.html',
        controller: 'CustomerCtrl'

    }).when('/new_customer', {
        title: 'New Customer',
        templateUrl: 'customer/new_customer.html',
        controller: 'AddCustomerCtrl'

    }).when('/edit_customer/:id', {
        title: 'Edit Customer',
        templateUrl: 'customer/edit_customer.html',
        controller: 'EditCustomerCtrl'

    })

    // Payment Details
   .when('/credit_payment', {
        title: 'Credit Payment',
        templateUrl: 'payment/credit_payment.html',
        controller: 'CreditPaymentCtrl'

    }).when('/cash_payment', {
        title: 'Cash Payment',
        templateUrl: 'payment/cash_payment.html',
        controller: 'CashPaymentCtrl'

    })

    // Adjustment
   .when('/credit_order_adjustment', {
        title: 'Credit Order Adjustment',
        templateUrl: 'payment_adjustment/order_adjustment.html',
        controller: 'AdjustmentCtrl'

    }).when('/payment_adjustment', {
        title: 'Company Payment Adjustment',
        templateUrl: 'payment_adjustment/payment_adjustment.html',
        controller: 'AdjustmentCtrl'
    })

   // Company Payment
   .when('/payment_company', {
        title: 'Company Payment',
        templateUrl: 'payment/company_payment.html',
        controller: 'CompanyPaymentCtrl'
    })

    // Report Details
   .when('/credit_report', {
        title: 'Credit Payment',
        templateUrl: 'reports/credit_report.html',
        controller: 'ReportCreditCtrl'

    }).when('/credit_summary_report', {
        title: 'Credit Payment',
        templateUrl: 'reports/credit_summary_report.html',
        controller: 'SummartReportCtrl'

    }).when('/cash_report', {
        title: 'Cash Payment',
        templateUrl: 'reports/cash_report.html',
        controller: 'ReportCashCtrl'

    })

    .when('/', {
        title: 'Login Page',
        templateUrl: 'pages/login.html',
        controller: 'authCtrl',
        role: '0'
    })
    .otherwise({
        redirectTo: '/login'
    });
}]).run(function ($rootScope, $location, Data) {
  $rootScope.$on("$routeChangeStart", function (event, next, current) 
  {
      $rootScope.authenticated = false;
      Data.get('session').then(function (results) 
      {
          if (results.uid) 
          {
            $rootScope.authenticated = true;
            $rootScope.uid = results.uid;
            $rootScope.role = results.role;
            $rootScope.usertype_name = results.usertype_name;
            $rootScope.name = results.name;
          } else {
              var nextUrl = next.$$route.originalPath;
              if (nextUrl == '/login' || nextUrl == '/signup')
              {

              } else 
              {
                  $location.path("/login");
                  pre_blocker('off');
              }
          }
      });
  });
});

app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
  $scope.login = {};
  $scope.doLogin = function (customer) {
    Data.post('login', {customer: customer}).then(function (results) {
      Data.toast(results);
      if (results.status == "success") {
        $location.path('dashboard');
        // if(results.uid == 1){
        //   $location.path('dashboard');
        // }
        // if(results.uid == 2){
        //   $location.path('customer');
        // }
      }
    });
  };
});


app.controller('DashboardCtrl', function($scope, Data, $location) {
  Data.get('getCntUsers').then(function (results) {
    $scope.users = results;
  });
  Data.get('getCntCompany').then(function (results) {
    $scope.company = results;
  });
  Data.get('getCntCustomer').then(function (results) {
    $scope.customer = results;
  });
  Data.get('getCntCashPayment').then(function (results) {
    $scope.cash = results;
  });
  Data.get('getCntCreditPayment').then(function (results) {
    $scope.credit = results;
  });
  $scope.logout = function () {
      Data.get('logout').then(function (results) {
          Data.toast(results);
          $location.path('login');
      });
  }
});
