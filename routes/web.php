<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', 'AuthController@index')->name('login');
Route::post('/login', 'AuthController@save')->name('login-form');

Route::get('/forget-password', 'AuthController@getForgetPassword')->name('forget-password');
Route::post('/forget-password', 'AuthController@postForgetPassword')->name('forget-form');

Route::get('/reset-password/{email}/{token}', 'AuthController@getResetPassword')->name('new-password');
Route::post('/reset-password', 'AuthController@postResetPassword');

Route::get('/logout', 'AuthController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function (){
  Route::get('/counties', 'HelperController@counties')->name('counties')
  ->middleware(['checkPower:customers-list']);
  Route::namespace('Management')->group(function () {
    Route::get('/', 'DashboardController@index')
    ->middleware(['checkPower:dashboard-list']);
    
    Route::get('/dashboard-projects', 'DashboardController@projects')->name('dashboard-projects');
    Route::get('/dashboard-exploration-status', 'DashboardController@explorationStatus')->name('get-exploration-status');
    Route::get('/dashboard-production-status', 'DashboardController@productionStatus')->name('get-production-status');
    Route::get('/dashboard-assembly-status', 'DashboardController@assemblyStatus')->name('get-assembly-status');
    Route::get('/dashboard-printing-status', 'DashboardController@printingStatus')->name('get-printing-status');
    Route::get('/dashboard-brief-status', 'DashboardController@briefStatus')->name('get-brief-status');
    Route::get('/dashboard-offer-status', 'DashboardController@offerStatus')->name('get-offer-status');
    
    Route::get('/dashboard-monthly-sales', 'DashboardController@monthlySales')->name('monthly-sales-json');
    Route::get('/dashboard-yearly-sales', 'DashboardController@yearlySales')->name('yearly-sales-json');

    Route::get('/customer-data/{id?}', 'DashboardController@customerData')->name('customer-data');
    Route::get('/yillik-satis-genel', 'DashboardController@yillikSatisgenel')->name('yillik-satis-genel');
    Route::get('/yillik-mt-genel', 'DashboardController@yillikMTGenel')->name('yillik-mt-genel');
    
  });
  Route::namespace('Records')->prefix('customers')->group(function () {
    Route::get('/', 'CustomerController@index')->name('customers')
    ->middleware(['checkPower:customers-list']);
    Route::get('/json', 'CustomerController@json')->name('customers-json')
    ->middleware(['checkPower:customers-list']);

    
    Route::get('/select2', 'CustomerController@select2')->name('customers-select2')
    ->middleware(['checkPower:customers-list']);
    Route::get('/companies', 'CustomerController@companies')->name('companies-json')
    ->middleware(['checkPower:customers-list']);
    
    Route::get('/assemblies', 'CustomerController@assemblies')->name('customer-assemblies');
    Route::get('/printings', 'CustomerController@printings')->name('customer-printings');
    Route::get('/productions', 'CustomerController@productions')->name('customer-productions');
    
    Route::get('/add', 'CustomerController@add')->name('add-customer')
    ->middleware(['checkPower:customers-add']);
    
    Route::get('/add/{id?}', 'CustomerController@update')->name('update-customer')
    ->middleware(['checkPower:customers-add']);
    
    Route::get('/detail/{id?}', 'CustomerController@detail')->name('customer-detail')
    ->middleware(['checkPower:customers-detail']);
    Route::get('/personels/{id?}', 'CustomerController@personels')->name('customer-personels')
    ->middleware(['checkPower:customers-detail']);
    
    Route::get('/delete/{id?}', 'CustomerController@delete')->name('delete-customer')
    ->middleware(['checkPower:customers-delete']);
    
    Route::post('/save', 'CustomerController@save')->name('save-customer')
    ->middleware(['checkPower:customers-add']);

  });
  Route::namespace('Records')->prefix('projects')->group(function () {
    Route::get('/', 'ProjectController@index')->name('projects')
    ->middleware(['checkPower:projects-list']);
    Route::get('/json', 'ProjectController@json')->name('projects-json')
    ->middleware(['checkPower:projects-list']);

    Route::get('/select2', 'ProjectController@select2')->name('projects-select2');
    Route::get('/list', 'ProjectController@list')->name('projects-list');
    
    Route::get('/add', 'ProjectController@add')->name('add-project')
    ->middleware(['checkPower:projects-add']);
    Route::get('/add/{id?}', 'ProjectController@update')->name('update-project')
    ->middleware(['checkPower:projects-add']);
    
    Route::get('/detail/{id?}', 'ProjectController@detail')->name('project-detail')
    ->middleware(['checkPower:projects-detail']);
    
    Route::get('/delete/{id?}', 'ProjectController@delete')->name('delete-project')
    ->middleware(['checkPower:projects-delete']);
    Route::get('/done/{id?}', 'ProjectController@done')->name('project-done')
    ->middleware(['checkPower:projects-delete']);
    
    Route::post('/save', 'ProjectController@save')->name('save-project')
    ->middleware(['checkPower:projects-add']);

  });
    
  Route::namespace('Order')->group(function () {
    Route::namespace('Production')->prefix('production')->group(function () {
      Route::get('/', 'ProductionController@index')->name('productions')
      ->middleware(['checkPower:production-list']);
      Route::get('/json', 'ProductionController@json')->name('productions-json')
      ->middleware(['checkPower:production-list']);

      Route::get('/select2', 'ProductionController@select2')->name('productions-select2')
      ->middleware(['checkPower:production-list']);
      
      Route::get('/add', 'ProductionController@add')->name('add-production')
      ->middleware(['checkPower:production-add']);
      Route::get('/add/{id?}', 'ProductionController@update')->name('update-production')
      ->middleware(['checkPower:production-add']);
      
      Route::get('/detail/{id?}', 'ProductionController@detail')->name('production-detail')
      ->middleware(['checkPower:production-detail']);
      
      Route::get('/delete-production-extra/{id?}', 'ProductionController@deleteExtra')->name('delete-production-extra')
      ->middleware(['checkPower:production-detail']);
      
      Route::get('/delete/{id?}', 'ProductionController@delete')->name('delete-production')
      ->middleware(['checkPower:production-delete']);
      
      Route::post('/upload', 'ProductionController@upload')->name('upload-production')
      ->middleware(['checkPower:production-add']);
      
      Route::post('/change-status', 'ProductionController@status')->name('update-production-status')
      ->middleware(['checkPower:production-status']);
      Route::post('/save-message', 'ProductionMessageController@save')->name('save-production-message')
      ->middleware(['checkPower:production-detail']);
      Route::post('/save', 'ProductionController@save')->name('save-production')
      ->middleware(['checkPower:production-add']);
    });
    
    Route::namespace('Assembly')->prefix('assembly')->group(function () {
      Route::get('/', 'AssemblyController@index')->name('assemblys')
      ->middleware(['checkPower:assembly-list']);
      Route::get('/json', 'AssemblyController@json')->name('assemblys-json')
      ->middleware(['checkPower:assembly-list']);

      Route::get('/select2', 'AssemblyController@select2')->name('assemblys-select2')
      ->middleware(['checkPower:assembly-list']);
      
      Route::get('/add', 'AssemblyController@add')->name('add-assembly')
      ->middleware(['checkPower:assembly-add']);
      Route::get('/add/{id?}', 'AssemblyController@update')->name('update-assembly')
      ->middleware(['checkPower:assembly-add']);
      
      Route::get('/detail/{id?}', 'AssemblyController@detail')->name('assembly-detail')
      ->middleware(['checkPower:assembly-detail']);
      
      Route::get('/delete-assembly-extra/{id?}', 'AssemblyController@deleteExtra')->name('delete-assembly-extra')
      ->middleware(['checkPower:assembly-detail']);
      
      Route::get('/delete/{id?}', 'AssemblyController@delete')->name('delete-assembly')
      ->middleware(['checkPower:assembly-delete']);
      
      Route::post('/upload', 'AssemblyController@upload')->name('upload-assembly')
      ->middleware(['checkPower:assembly-add']);
      
      Route::post('/change-status', 'AssemblyController@status')->name('update-assembly-status')
      ->middleware(['checkPower:assembly-status']);
      Route::post('/save-message', 'AssemblyMessageController@save')->name('save-assembly-message');
      Route::post('/save', 'AssemblyController@save')->name('save-assembly')
      ->middleware(['checkPower:assembly-add']);

    });
    
    Route::namespace('Printing')->prefix('printing')->group(function () {
      Route::get('/', 'PrintingController@index')->name('printings')
      ->middleware(['checkPower:printing-list']);
      Route::get('/json', 'PrintingController@json')->name('printings-json')
      ->middleware(['checkPower:printing-list']);

      Route::get('/select2', 'PrintingController@select2')->name('printings-select2')
      ->middleware(['checkPower:printing-list']);
      
      Route::get('/add', 'PrintingController@add')->name('add-printing')
      ->middleware(['checkPower:printing-add']);
      Route::get('/add/{id?}', 'PrintingController@update')->name('update-printing')
      ->middleware(['checkPower:printing-add']);
      
      Route::get('/detail/{id?}', 'PrintingController@detail')->name('printing-detail')
      ->middleware(['checkPower:printing-detail']);
      
      Route::get('/delete-printing-extra/{id?}', 'PrintingController@deleteExtra')->name('delete-printing-extra')
      ->middleware(['checkPower:printing-detail']);
      
      Route::get('/delete/{id?}', 'PrintingController@delete')->name('delete-printing')
      ->middleware(['checkPower:printing-delete']);
      
      Route::post('/upload', 'PrintingController@upload')->name('upload-printing')
      ->middleware(['checkPower:printing-add']);
      
      
      Route::post('/change-status', 'PrintingController@status')->name('update-printing-status')
      ->middleware(['checkPower:printing-status']);
      Route::post('/save-message', 'PrintingMessageController@save')->name('save-printing-message');
      Route::post('/save', 'PrintingController@save')->name('save-printing')
      ->middleware(['checkPower:printing-add']);
    });
    
    Route::namespace('Printing')->prefix('printing-meta')->group(function () {
      Route::get('/', 'PrintingMetaController@index')->name('printing-meta-index')
      ->middleware(['checkPower:printing_meta-list']);
      
    
      Route::get('/add', 'PrintingMetaController@add')->name('add-printing-meta')
      ->middleware(['checkPower:printing_meta-add']);
      Route::get('/add/{id?}', 'PrintingMetaController@update')->name('update-printing-meta')
      ->middleware(['checkPower:printing_meta-add']);
      
      Route::post('/save', 'PrintingMetaController@save')->name('save-printing-meta')
      ->middleware(['checkPower:printing_meta-add']);
    });
    Route::namespace('Exploration')->prefix('exploration')->group(function () {
      Route::get('/', 'ExplorationController@index')->name('explorations')
      ->middleware(['checkPower:exploration-list']);
      Route::get('/json', 'ExplorationController@json')->name('explorations-json')
      ->middleware(['checkPower:exploration-list']);

      Route::get('/select2', 'ExplorationController@select2')->name('explorations-select2')
      ->middleware(['checkPower:exploration-list']);
      
      Route::get('/add', 'ExplorationController@add')->name('add-exploration')
      ->middleware(['checkPower:exploration-add']);
      Route::get('/add/{id?}', 'ExplorationController@update')->name('update-exploration')
      ->middleware(['checkPower:exploration-add']);
      
      Route::get('/detail/{id?}', 'ExplorationController@detail')->name('exploration-detail')
      ->middleware(['checkPower:exploration-detail']);
      
      Route::get('/delete-exploration-extra/{id?}', 'ExplorationController@deleteExtra')->name('delete-exploration-extra')
      ->middleware(['checkPower:exploration-detail']);
      
      Route::get('/delete/{id?}', 'ExplorationController@delete')->name('delete-exploration')
      ->middleware(['checkPower:exploration-delete']);
      
      Route::post('/upload', 'ExplorationController@upload')->name('upload-exploration')
      ->middleware(['checkPower:exploration-add']);
      
      Route::post('/change-status', 'ExplorationController@status')->name('update-exploration-status')
      ->middleware(['checkPower:exploration-status']);
      Route::post('/save-message', 'ExplorationMessageController@save')->name('save-exploration-message')
      ->middleware(['checkPower:exploration-add']);
      Route::post('/save', 'ExplorationController@save')->name('save-exploration')
      ->middleware(['checkPower:exploration-add']);
      
      Route::get('/design-comments/{id?}/{comment_id}', 'ExplorationController@designComments')->name('exploration-design-comments');
      Route::get('/comments/{id?}/{comment_id}', 'ExplorationController@comments')->name('exploration-comments');
      
      Route::post('/save-design', 'ExplorationController@saveDesign');
    });
    Route::namespace('Exploration')->prefix('exploration-comments')->group(function () {
      Route::post('/save', 'ExplorationCommentController@save')->name('save-exploration-comment');
      Route::post('/delete', 'ExplorationCommentController@delete')->name('delete-exploration-comment');
    });
  
    Route::namespace('Exploration')->prefix('exploration-design')->group(function () {
      Route::post('/save', 'ExplorationDesignController@save')->name('save-exploration-design');
      Route::post('/delete', 'ExplorationDesignController@delete')->name('delete-exploration-design');
  
      Route::post('/save-comment', 'ExplorationDesignController@saveComment')->name('save-exploration-design-comment');
      Route::post('/delete-comment', 'ExplorationDesignController@deleteComment')->name('delete-exploration-design-comment');
    });
  
  });
  
  Route::namespace('Workflow')->group(function () {
    Route::namespace('Cheque')->prefix('/cheque')->group(function () {
      Route::get('/send', 'ChequeController@send')->name('send-cheques');
      Route::get('/received', 'ChequeController@received')->name('received-cheques');

      Route::get('/send-json', 'ChequeController@sendJson')->name('send-cheques-json');
      Route::get('/received-json', 'ChequeController@receivedJson')->name('received-cheques-json');

      Route::get('/add-send', 'ChequeController@addSend')->name('add-send-cheque');
      Route::get('/update-send/{id?}', 'ChequeController@updateSend')->name('update-send-cheque');
      
      Route::get('/add-received', 'ChequeController@addReceived')->name('add-received-cheque');
      Route::get('/update-received/{id?}', 'ChequeController@updateReceived')->name('update-received-cheque');

      Route::post('/save', 'ChequeController@save')->name('save-cheque');
      
      Route::post('/delete/{id?}', 'ChequeController@delete')->name('delete-cheque');
      Route::post('/confirm/{id?}', 'ChequeController@confirm')->name('confirm-cheque');
    });
    Route::namespace('Brief')->prefix('/briefs')->group(function () {
      Route::get('/', 'BriefController@index')->name('briefs');
      Route::get('/json', 'BriefController@json')->name('briefs-json');
  
      Route::get('/design-comments/{id?}/{comment_id}', 'BriefController@designComments')->name('brief-design-comments');
      Route::get('/comments/{id?}/{comment_id}', 'BriefController@comments')->name('brief-comments');
  
      Route::get('/add', 'BriefController@add')->name('add-brief');
      Route::get('/add-design/{id?}', 'BriefController@addDesign')->name('add-brief-design');
      Route::get('/add/{id?}', 'BriefController@update')->name('update-brief');
      Route::get('/detail/{id?}', 'BriefController@detail')->name('brief-detail');
  
      Route::post('/save', 'BriefController@save')->name('save-brief');
      Route::post('/save-design', 'BriefController@saveDesign');
      Route::post('/upload', 'BriefController@upload')->name('brief-upload');
      
      Route::get('/delete/{id?}', 'BriefController@delete')->name('delete-brief');
      Route::post('/dosya-sil/{id?}', 'BriefController@deleteFile')->name('brief-dosya-sil');
      Route::post('/update-status/{id?}', 'BriefController@updateStatus')->name('brief-status');
      Route::post('/designer-status/{id?}', 'BriefController@designerStatus')->name('brief-designer-status');
    });
  
    Route::namespace('Brief')->prefix('/brief-comments')->group(function () {
      Route::post('/save', 'BriefCommentController@save')->name('save-brief-comment');
      Route::post('/delete', 'BriefCommentController@delete')->name('delete-brief-comment');
    });
  
    Route::namespace('Brief')->prefix('/brief-design')->group(function () {
      Route::post('/save', 'BriefDesignController@save')->name('save-brief-design');
      Route::post('/delete', 'BriefDesignController@delete')->name('delete-brief-design');
  
      Route::post('/save-comment', 'BriefDesignController@saveComment')->name('save-brief-design-comment');
      Route::post('/delete-comment', 'BriefDesignController@deleteComment')->name('delete-brief-design-comment');
    });
  
    Route::namespace('Offer')->prefix('/offers')->group(function () {
      Route::get('/', 'OfferController@index')->name('offers');
      Route::get('/json', 'OfferController@json')->name('offers-json');
      Route::get('/add', 'OfferController@add')->name('add-offer');
      Route::get('/add/{id?}', 'OfferController@update')->name('update-offer');
      Route::get('/detail/{id?}', 'OfferController@detail')->name('offer-detail');
  
      Route::post('/save', 'OfferController@save')->name('save-offer');
      Route::get('/delete/{id?}', 'OfferController@delete')->name('delete-offer');
      Route::post('/delete-file/{id?}', 'OfferController@deleteFile')->name('delete-offer-file');
      
      Route::post('/update-status/{id?}', 'OfferController@updateStatus')->name('offer-status');
  
  
      Route::get('/message/{id?}', 'OfferController@message')->name('offer-message');
      Route::post('/send-message', 'OfferController@sendMessage')->name('send-offer-message-offer');
    });
  
    Route::namespace('Offer')->prefix('/offer-comments')->group(function () {
      Route::post('/save', 'OfferCommentController@save')->name('save-offer-comment');
      Route::post('/delete', 'OfferCommentController@delete')->name('delete-offer-comment');
    });
  
    Route::namespace('Offer')->prefix('/offer-message')->group(function () {
      Route::post('/delete', 'OfferMessageController@delete')->name('delete-offer-message');
  
      Route::post('/save-comment', 'OfferMessageController@saveComment')->name('save-offer-message-comment');
      Route::post('/delete-comment', 'OfferMessageController@deleteComment')->name('delete-offer-message-comment');
    });
  
  
    Route::namespace('Contract')->prefix('/contracts')->group(function () {
      Route::get('/', 'ContractController@index')->name('contracts');
      Route::get('/json', 'ContractController@json')->name('contracts-json');
      Route::get('/add', 'ContractController@add')->name('add-contract');
      Route::get('/add/{id?}', 'ContractController@update')->name('update-contract');
      Route::get('/detail/{id?}', 'ContractController@detail')->name('contract-detail');
  
      Route::post('/save', 'ContractController@save')->name('save-contract');
      Route::get('/delete/{id?}', 'ContractController@delete')->name('delete-contract');
      Route::post('/delete-file/{id?}', 'ContractController@deleteFile')->name('delete-contract-file');
      
      Route::post('/update-status/{id?}', 'ContractController@updateStatus')->name('contract-status');
  
      Route::get('/message/{id?}', 'ContractController@message')->name('contract-message');
      Route::post('/send-message', 'ContractController@sendMessage')->name('send-contract-message-contract');
    });
  
    Route::namespace('Contract')->prefix('/contract-comments')->group(function () {
      Route::post('/save', 'ContractCommentController@save')->name('save-contract-comment');
      Route::post('/delete', 'ContractCommentController@delete')->name('delete-contract-comment');
    });
  
    Route::namespace('Contract')->prefix('/contract-message')->group(function () {
      Route::post('/save', 'ContractMessageController@save')->name('save-contract-message');
      Route::post('/delete', 'ContractMessageController@delete')->name('delete-contract-message');
  
      Route::post('/save-comment', 'ContractMessageController@saveComment')->name('save-contract-message-comment');
      Route::post('/delete-comment', 'ContractMessageController@deleteComment')->name('delete-contract-message-comment');
    });
  
    Route::namespace('Bill')->prefix('/bills')->group(function () {
      Route::get('/', 'BillController@index')->name('bills');
      Route::get('/json', 'BillController@json')->name('bills-json');
      Route::get('/add', 'BillController@add')->name('add-bill');
      Route::get('/add/{id?}', 'BillController@update')->name('update-bill');
      Route::get('/detail/{id?}', 'BillController@detail')->name('bill-detail');
  
      Route::post('/save', 'BillController@save')->name('save-bill');
      Route::get('/delete/{id?}', 'BillController@delete')->name('delete-bill');
      Route::post('/delete-file/{id?}', 'BillController@deleteFile')->name('delete-bill-file');
      
      Route::post('/update-status/{id?}', 'BillController@updateStatus')->name('bill-status');
      Route::post('/bill-files/{id?}', 'BillController@billFiles')->name('bill-files');
      Route::post('/send-bill-to-customers/{id?}', 'BillController@sendCustomer')->name('send-bill-to-customer');
      
      Route::post('/import', 'BillController@import')->name('import-bill');
      Route::post('/upload', 'BillController@upload2')->name('upload-bill');
    });
  });

  Route::namespace('Purchase')->group(function () {
    Route::namespace('Purchase')->prefix('purchases')->group(function () {
      Route::get('/', 'PurchaseController@index')->name('purchases')
      ->middleware(['checkPower:purchase-list']);
      Route::get('/json', 'PurchaseController@json')->name('purchases-json')
      ->middleware(['checkPower:purchase-list']);

      
      Route::get('/select2', 'PurchaseController@select2')->name('purchases-select2')
      ->middleware(['checkPower:purchase-list']);
      
      Route::get('/add', 'PurchaseController@add')->name('add-purchase')
      ->middleware(['checkPower:purchase-add']);
      
      Route::get('/add/{id?}', 'PurchaseController@update')->name('update-purchase')
      ->middleware(['checkPower:purchase-add']);
      Route::post('/change-status', 'PurchaseController@status')->name('update-purchase-status')
      ->middleware(['checkPower:purchase-add']);
      
      Route::get('/detail/{id?}', 'PurchaseController@detail')->name('purchase-detail')
      ->middleware(['checkPower:purchase-detail']);
      Route::get('/personels/{id?}', 'PurchaseController@personels')->name('purchase-personels')
      ->middleware(['checkPower:purchase-detail']);
      
      Route::get('/delete/{id?}', 'PurchaseController@delete')->name('delete-purchase')
      ->middleware(['checkPower:purchase-delete']);
      
      Route::post('/save', 'PurchaseController@save')->name('save-purchase')
      ->middleware(['checkPower:purchase-add']);

    });
    Route::namespace('Product')->prefix('products')->group(function () {
      Route::get('/', 'ProductController@index')->name('products')
      ->middleware(['checkPower:product-list']);
      Route::get('/json', 'ProductController@json')->name('products-json')
      ->middleware(['checkPower:product-list']);

      
      Route::get('/select2', 'ProductController@select2')->name('products-select2')
      ->middleware(['checkPower:product-list']);
      Route::get('/categories', 'ProductController@categories')->name('categories-select2')
      ->middleware(['checkPower:product-list']);
      
      Route::get('/add', 'ProductController@add')->name('add-product')
      ->middleware(['checkPower:product-add']);
      
      Route::get('/add/{id?}', 'ProductController@update')->name('update-product')
      ->middleware(['checkPower:product-add']);
      
      Route::get('/detail/{id?}', 'ProductController@detail')->name('product-detail')
      ->middleware(['checkPower:product-detail']);
      Route::get('/personels/{id?}', 'ProductController@personels')->name('product-personels')
      ->middleware(['checkPower:product-detail']);
      
      Route::get('/delete/{id?}', 'ProductController@delete')->name('delete-product')
      ->middleware(['checkPower:product-delete']);
      
      Route::post('/save', 'ProductController@save')->name('save-product')
      ->middleware(['checkPower:product-add']);
      
      Route::post('/save-category', 'ProductController@saveCategory')->name('save-category')
      ->middleware(['checkPower:product-add']);

    });
    Route::namespace('Supplier')->prefix('suppliers')->group(function () {
      Route::get('/', 'SupplierController@index')->name('suppliers')
      ->middleware(['checkPower:supplier-list']);
      Route::get('/json', 'SupplierController@json')->name('suppliers-json')
      ->middleware(['checkPower:supplier-list']);

      
      Route::get('/select2', 'SupplierController@select2')->name('suppliers-select2');
      Route::get('/suppliers', 'SupplierController@suppliers')->name('get-suppliers-json');
      
      Route::get('/add', 'SupplierController@add')->name('add-supplier')
      ->middleware(['checkPower:supplier-add']);
      
      Route::get('/add/{id?}', 'SupplierController@update')->name('update-supplier')
      ->middleware(['checkPower:supplier-add']);
      
      Route::get('/detail/{id?}', 'SupplierController@detail')->name('supplier-detail')
      ->middleware(['checkPower:supplier-detail']);
      Route::get('/personels/{id?}', 'SupplierController@personels')->name('supplier-personels')
      ->middleware(['checkPower:supplier-detail']);
      
      Route::get('/delete/{id?}', 'SupplierController@delete')->name('delete-supplier')
      ->middleware(['checkPower:supplier-delete']);
      
      Route::post('/save', 'SupplierController@save')->name('save-supplier')
      ->middleware(['checkPower:supplier-add']);

    });
    Route::namespace('Expense')->prefix('expenses')->group(function () {
      Route::get('/', 'ExpenseController@index')->name('expenses')
      ->middleware(['checkPower:expense-list']);
      Route::get('/json', 'ExpenseController@json')->name('expenses-json')
      ->middleware(['checkPower:expense-list']);

      
      Route::get('/select2', 'ExpenseController@select2')->name('expenses-select2')
      ->middleware(['checkPower:expense-list']);
      
      Route::get('/add', 'ExpenseController@add')->name('add-expense')
      ->middleware(['checkPower:expense-add']);
      
      Route::get('/add/{id?}', 'ExpenseController@update')->name('update-expense')
      ->middleware(['checkPower:expense-add']);
      Route::post('/change-status', 'ExpenseController@status')->name('update-expense-status')
      ->middleware(['checkPower:expense-edit']);
      
      Route::get('/detail/{id?}', 'ExpenseController@detail')->name('expense-detail')
      ->middleware(['checkPower:expense-detail']);
      Route::get('/personels/{id?}', 'ExpenseController@personels')->name('expense-personels')
      ->middleware(['checkPower:expense-detail']);
      
      Route::get('/delete/{id?}', 'ExpenseController@delete')->name('delete-expense')
      ->middleware(['checkPower:expense-delete']);
      
      Route::post('/save', 'ExpenseController@save')->name('save-expense')
      ->middleware(['checkPower:expense-add']);

    });
  });
  Route::namespace('Management')->prefix('users')->group(function () {
    Route::get('/', 'UsersController@index')->name('users')
    ->middleware(['checkPower:users-list']);
    Route::get('/add', 'UsersController@add')->name('add-user')
    ->middleware(['checkPower:users-add']);
    
    Route::get('/detail/{id?}', 'UsersController@profile')->name('personel-detail')
    ->middleware(['checkPower:users-add']);
    
    Route::get('/earnest/{id?}', 'UsersController@earnest')->name('personel-earnest');
    Route::get('/permission/{id?}', 'UsersController@permission')->name('personel-permission');
    Route::get('/belonging/{id?}', 'UsersController@belonging')->name('personel-belonging');

    Route::get('/update/{id?}', 'UsersController@update')->name('update-user')
    ->middleware(['checkPower:users-edit']);
    Route::get('/delete/{id?}', 'UsersController@passive')->name('passive-user')
    ->middleware(['checkPower:users-delete']);
    Route::get('/active/{id?}', 'UsersController@active')->name('active-user')
    ->middleware(['checkPower:users-edit']);
    Route::post('/save', 'UsersController@save')->name('user-save')
    ->middleware(['checkPower:users-add']);
    Route::get('/emailverified', 'UsersController@emailverified')->name('email-verified-user');
  });
  
  Route::namespace('Management')->prefix('vehicles')->group(function () {
    Route::get('/', 'VehicleController@index')->name('vehicles')
    ->middleware(['checkPower:vehicles-list']);
    Route::get('/add', 'VehicleController@add')->name('add-vehicle')
    ->middleware(['checkPower:vehicles-add']);
    
    Route::get('/update/{id?}', 'VehicleController@update')->name('update-vehicle')
    ->middleware(['checkPower:vehicles-edit']);
    Route::get('/delete/{id?}', 'VehicleController@passive')->name('passive-vehicle')
    ->middleware(['checkPower:vehicles-delete']);
    Route::get('/active/{id?}', 'VehicleController@active')->name('active-vehicle')
    ->middleware(['checkPower:vehicles-edit']);
    Route::post('/save', 'VehicleController@save')->name('vehicle-save')
    ->middleware(['checkPower:vehicles-add']);
    Route::get('/emailverified', 'VehicleController@emailverified')->name('email-verified-vehicle');
  });

  Route::prefix('user')->namespace('Management')->group(function () {
    Route::get('/', 'UserController@profile')->name('user-dashboard');
    
    Route::get('/earnest', 'UserController@earnest')->name('user-earnest');
    Route::get('/permission', 'UserController@permission')->name('user-permission');
    Route::get('/belonging', 'UserController@belonging')->name('user-belonging');

    Route::get('profil', 'UserController@profile')->name('user-profile');
    Route::post('kaydet', 'UserController@save')->name('user-save-myself');
    Route::post('password', 'UserController@updatePassword')->name('user-password');
  });
  Route::prefix('workflow')->namespace('Management')->group(function () {
    Route::get('/', 'WorkflowController@index')->name('workflows');
    Route::get('/button', 'WorkflowController@button')->name('workflow-button');
    Route::get('/item', 'WorkflowController@item')->name('workflow-item');
    
    Route::post('/save', 'WorkflowController@save')->name('save-workflow');
  });
  
  Route::namespace('Personal')->group(function () {
    Route::prefix('costs')->group(function () {
      Route::get('/', 'CostController@index')->name('costs');
      Route::get('/json', 'CostController@json')->name('costs-json');

      Route::get('/masraf-yazdir', 'CostController@print')->name('print-costs');

      Route::get('add', 'CostController@add')->name('add-cost');
      Route::get('detail/{id?}', 'CostController@detail')->name('cost-detail');
      Route::get('update/{id?}', 'CostController@update')->name('update-cost');
      Route::get('delete/{id?}', 'CostController@delete')->name('delete-cost');

      Route::post('save', 'CostController@save')->name('save-cost');
      Route::post('status-update', 'CostController@statusUpdate')->name('update-cost-status');

      Route::get('waiting-approval', 'CostController@onayBekleyen')->name('cost-waiting-approval');
      Route::get('waiting-payment', 'CostController@odemeBekleyen')->name('cost-waiting-payment');

      Route::post('upload', 'CostController@upload')->name('cost-upload');
    });
    
    Route::namespace('Demands')->prefix('intranet')->group(function () {
      Route::get('/', 'IntranetController@index')->name('intranet');
      Route::get('/duyuru/{id?}', 'IntranetController@announceDetail')->name('intranet-duyuru-detay');
      Route::get('/duyurular', 'IntranetController@announces')->name('intranet-duyurular');
      Route::get('rating/{id?}', 'IntranetController@rating')->name('egitim-rating');
      Route::post('rating-kaydet', 'IntranetController@saveRating')->name('egitim-rating-kaydet');
    });
    
    Route::namespace('Demands')->prefix('wage')->group(function () {
      Route::get('/odeme', 'WageController@odeme')->name('maaslar-odeme-bekleyen');
      Route::post('/odeme', 'WageController@isPaid');
      Route::post('/upload/{id?}', 'WageController@upload');
    });


    Route::namespace('Demands')->prefix('intranet')->group(function () {
      Route::prefix('permisions')->group(function () {
        Route::get('/', 'PermissionController@index')->name('personel-izinler');
        Route::get('ekle', 'PermissionController@add')->name('personel-izin-ekle');
        Route::get('duzenle/{id?}', 'PermissionController@update')->name('personel-izin-duzenle');
        Route::get('sil/{id?}', 'PermissionController@delete')->name('personel-izin-sil');
        Route::post('kaydet', 'PermissionController@save')->name('personel-izin-kaydet');
      });

      Route::prefix('avanslar')->group(function () {
        Route::get('/', 'EarnestController@index')->name('personel-avanslar');
        Route::get('ekle', 'EarnestController@add')->name('personel-avans-ekle');
        Route::get('duzenle/{id?}', 'EarnestController@update')->name('personel-avans-duzenle');
        Route::get('sil/{id?}', 'EarnestController@delete')->name('personel-avans-sil');
        Route::post('kaydet', 'EarnestController@save')->name('personel-avans-kaydet');
    });

    Route::prefix('vizeler')->group(function () {
        Route::get('/', 'VisaController@index')->name('personel-vizeler');
        Route::get('ekle', 'VisaController@add')->name('personel-vize-ekle');
        Route::get('duzenle/{id?}', 'VisaController@update')->name('personel-vize-duzenle');
        Route::get('sil/{id?}', 'VisaController@delete')->name('personel-vize-sil');
        Route::post('kaydet', 'VisaController@save')->name('personel-vize-kaydet');
    });

    Route::prefix('ihtiyaclar')->group(function () {
        Route::get('/', 'NeedsController@index')->name('personel-ihtiyaclar');
        Route::get('ekle', 'NeedsController@add')->name('personel-ihtiyac-ekle');
        Route::get('duzenle/{id?}', 'NeedsController@update')->name('personel-ihtiyac-duzenle');
        Route::get('sil/{id?}', 'NeedsController@delete')->name('personel-ihtiyac-sil');
        Route::post('kaydet', 'NeedsController@save')->name('personel-ihtiyac-kaydet');
    });

    Route::prefix('mesailer')->group(function () {
        Route::get('/', 'OvertimeController@index')->name('personel-mesailer');
        Route::get('ekle', 'OvertimeController@add')->name('personel-mesai-ekle');
        Route::get('duzenle/{id?}', 'OvertimeController@update')->name('personel-mesai-duzenle');
        Route::get('sil/{id?}', 'OvertimeController@delete')->name('personel-mesai-sil');
        Route::post('kaydet', 'OvertimeController@save')->name('personel-mesai-kaydet');
    });

    Route::prefix('zimmetler')->group(function () {
        Route::get('/', 'BelongingsController@index')->name('personel-zimmetler');
        Route::get('ekle', 'BelongingsController@add')->name('personel-zimmet-ekle');
        Route::get('duzenle/{id?}', 'BelongingsController@update')->name('personel-zimmet-duzenle');
        Route::get('sil/{id?}', 'BelongingsController@delete')->name('personel-zimmet-sil');
        Route::post('kaydet', 'BelongingsController@save')->name('personel-zimmet-kaydet');
    });
  });
  });
  Route::namespace('HR')->group(function () {
    Route::prefix('employee')->namespace('Personel')->group(function () {
      Route::get('/', 'PersonelController@index')->middleware(['checkPower:employee-index'])->name('employees');
      Route::get('/json', 'PersonelController@json')->middleware(['checkPower:employee-index'])->name('employees-json');

      Route::get('add', 'PersonelController@add')->middleware(['checkPower:employee-add'])->name('add-employee');
      Route::get('update/{id?}', 'PersonelController@update')->middleware(['checkPower:employee-edit'])->name('update-employee');
      Route::get('detail/{id?}', 'PersonelController@detail')->middleware(['checkPower:employee-index'])->name('employee-detail');
      
      Route::post('new-save', 'PersonelController@personelAddSave')->middleware(['checkPower:employee-add'])->name('save-new-employee');
      Route::post('save', 'PersonelController@save')->middleware(['checkPower:employee-add'])->name('save-employee');

      Route::get('remove-employee/{id?}', 'PersonelController@removeEmployee')->middleware(['checkPower:employee-add'])->name('remove-employee');
      Route::get('activate-employee/{id?}', 'PersonelController@activateEmployee')->middleware(['checkPower:employee-add'])->name('activate-employee');

      Route::get('employee-information/{id?}', 'PersonelController@personal')->middleware(['checkPower:employee-index'])->name('employee-information');
      Route::post('personel-bilgileri/{id?}', 'PersonelController@personalSave')->middleware(['checkPower:employee-update'])->name('save-employee-information');
      Route::post('personel-family/{id?}', 'PersonelController@familySave')->middleware(['checkPower:employee-update'])->name('personel-family');
      Route::post('personel-files/{id?}', 'PersonelController@filesSave')->middleware(['checkPower:employee-update'])->name('personel-files');
      Route::post('hesap-bilgileri/{id?}', 'PersonelController@accountSave')->middleware(['checkPower:employee-update'])->name('save-personel-account');

      Route::get('employee-account/{id?}', 'PersonelController@account')->middleware(['checkPower:personel-görüntüle'])->name('employee-account');

      Route::get('employee-education/{id?}', 'PersonelController@education')->middleware(['checkPower:employee-index'])->name('employee-education');
      Route::get('employee-finance/{id?}', 'PersonelController@finance')->middleware(['checkPower:employee-index'])->name('employee-finance');
      Route::get('employee-demand/{id?}', 'PersonelController@demand')->middleware(['checkPower:employee-talep'])->name('employee-demand');
      Route::get('employee-rating/{id?}', 'PersonelController@rating')->middleware(['checkPower:employee-index'])->name('employee-rating');
      Route::get('belonging/{id?}', 'PersonelController@belonging')->middleware(['checkPower:employee-index'])->name('employee-belonging');

      Route::get('all-belongings', 'PersonelController@allbelongings')->middleware(['checkPower:employee-index'])->name('all-belongings');

      Route::get('belonging-detail/{id?}', 'PersonelController@editBelonging')->middleware(['checkPower:employee-update'])->name('belonging-detail');
      Route::get('add-belonging/{id?}', 'PersonelController@addBelonging')->middleware(['checkPower:employee-update'])->name('add-belonging');


      Route::post('update-wage', 'PersonelController@wageSave')->middleware(['checkPower:employee-wage'])->name('update-wage');
      Route::get('add-certificate/{id?}', 'PersonelController@addCertificate')->middleware(['checkPower:employee-update'])->name('single-certificate');
      Route::get('delete-certificate/{id?}', 'PersonelController@deleteCertificate')->middleware(['checkPower:employee-update'])->name('delete-employee-certificate');

      Route::post('permision', 'PersonelController@updatePermission')->middleware(['checkPower:employee-update'])->name('update-permision');
      Route::post('earnest', 'PersonelController@updateEarnest')->name('update-earnest');
      Route::post('education', 'PersonelController@updateEducation')->middleware(['checkPower:employee-update'])->name('update-education');
      Route::post('certificate', 'PersonelController@certificateSave')->middleware(['checkPower:employee-update'])->name('certificate-update');
      Route::post('visa', 'PersonelController@updateVisa')->middleware(['checkPower:employee-update'])->name('update-visa');
      Route::post('need', 'PersonelController@updateNeed')->middleware(['checkPower:employee-update'])->name('update-need');
      Route::post('belonging-save', 'PersonelController@updateBelonging')->middleware(['checkPower:employee-update'])->name('update-belonging');

      Route::post('upload', 'PersonelController@upload')->name('upload-employee');
    });

    Route::prefix('demands')->group(function () {
      Route::get('izin', 'DemandsController@permission')->name('permision-waiting-approval');
      Route::get('avans', 'DemandsController@earnest')->name('earnest-waiting-approval');
      Route::get('ihtiyaclar', 'DemandsController@need')->name('need-waiting-approval');

      Route::get('muhasebe-avans', 'DemandsController@earnestAccountant')->name('muhasebe-avans-talep');

      Route::post('izin', 'DemandsController@updatePermission')->name('ik-izin-talep-kaydet');
      Route::post('avans', 'DemandsController@updateEarnest')->name('ik-avans-talep-kaydet');
      Route::post('vize', 'DemandsController@updateVisa')->name('ik-vize-talep-kaydet');
    });
  });
});


Route::namespace('Workflow')->group(function () {
  Route::namespace('Offer')->group(function () {
    Route::get('/teklif/{hash}', 'OfferController@musteriTeklif')->name('musteri-teklif');
    Route::get('/sozlesme/{hash}', 'OfferController@musteriSozlesme')->name('musteri-sozlesme');
    Route::post('/save', 'OfferMessageController@save')->name('save-offer-message');
  });
});



// Demo routes
Route::get('/datatables', 'PagesController@datatables');
Route::get('/ktdatatables', 'PagesController@ktDatatables');
Route::get('/select2', 'PagesController@select2');
Route::get('/jquerymask', 'PagesController@jQueryMask');
Route::get('/icons/custom-icons', 'PagesController@customIcons');
Route::get('/icons/flaticon', 'PagesController@flaticon');
Route::get('/icons/fontawesome', 'PagesController@fontawesome');
Route::get('/icons/lineawesome', 'PagesController@lineawesome');
Route::get('/icons/socicons', 'PagesController@socicons');
Route::get('/icons/svg', 'PagesController@svg');

// Quick search dummy route to display html elements in search dropdown (header search)
Route::get('/quick-search', 'PagesController@quickSearch')->name('quick-search');
