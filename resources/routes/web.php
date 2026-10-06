<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    GRNController,
    POSController,
    HomeController,
    UserController,
    BanksController,
    PartyController,
    LeadController,
    SalesController,
    CitiesController,
    ProductController,
    ProductMappingController,
    EmployeeController,
    PurchaseController,
    SupplierController,
    CustomersController,
    PurchaserController,
    SaleOrderController,
    WarehouseController,
    ProductionController,
    SaleDemandController,
    TradeGroupController,
    BankPaymentController,
    BankReceiptController,
    CashPaymentController,
    CashReceiptController,
    DepartmentsController,
    OpeningBalanceVoucher,
    SalesReturnController,
    AccountGroupController,
    OpeningStockController,
    RightsLevel1Controller,
    RightsLevel2Controller,
    RightsLevel3Controller,
    VoucherNamesController,
    AccountGroup2Controller,
    AccountGroup3Controller,
    PurchaseOrderController,
    StockTransferController,
    AccountSettingController,
    InwardGatePassController,
    JournalVoucherController,
    PurchaseReturnController,
    PurchaserStockController,
    RecipeCreationController,
    RecipeListController,
    CustomerProductController,
    CustomerReportsController,
    CustomerLedgerAccountController,
    SupplierReportsController,
    DeliveryChallanController,
    DeliveryChallanNoGSTController,
    InventoryReportController,
    PendingVouchersController,
    PurchaserReportController,
    RequestGenerateController,
    FinancialReportsController,
    ProductCategoriesController,
    CustomerDeliveryChallanController,
    DesignationController,
    EmployeeTypeController,
    RateListController,
    SalesTaxInvoiceController,
    ThermoformingProductionController,
    ThermoformingProductionReportController,
    OpeningPetRollController,
    SlittingProductionController,
    SlittingStockTransferController,
    PackingProductionController,
    JournalLedgerController,
    PackingProductionReportController,
    ProductsReportController,
    InventoryReport1Controller,
    IssuanceController,
    IssuanceReturnController,
    ProductionOneController,
    BatchStockController,
    RequestReportController,
    IGPReportController,
    PurchaseReportController,
    PurchaseTaxController,
    GRNReportController,
    POReportController,
    PurchaseTaxReportController,
    BatchStockReportController,
    CashBookController,
    RequestWiseReportController,
    DCPOStatusReportController,
    DCDemandPOStatusReportController,
    OrderDemandReportController,
    GeneralJournalController,
    TrialBalanceController,
    PurchaseRollController,
    CashBookSingleDateController,
    ProductionReportController,
    AttendanceController,
    AdvanceSalaryController,
    DirectSalesController,
    QuotationController,
    SalesReportController,
    DirectPurchaseController,
    SalarySheetController,
    DirectSalesTaxInvoiceController,
    DirectPurchaseTaxVoucherController,
    SalesTaxReturnController,
    PurchaseTaxReturnController,
    PetRollProductionController,
    StockConsumptionController,
    StockTransferReportController,
    CustomerCareOfController,
    DirectDeliveryChallanController,
    DirectDCSalesController,
    EmailController,
    SaleTaxReportController,
    SubPartyDemandPOReportController,
    SubPartyOrderPOReportController
};


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

    // Route::resource('thermoforming-production', ThermoformingProductionController::class);
    
    // Route::get('/videos', [WebsiteController::class, 'Videos']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('/send-test-email', [EmailController::class, 'sendTestEmail']);


Route::group(['middleware' => 'prevent-back-history'],function(){
Route::get('/', function () {
    return view('auth.login');
});
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout']);
Auth::routes();


//Account Settings
Route::resource('account', AccountSettingController::class);

// Inventory Reports
Route::controller(InventoryReportController::class)->prefix('inventory/')->group(function () {
    Route::get('ledger', 'InventoryReport');
    Route::get('stock-report', 'StockReport');
    Route::get('print/voucher', 'PrintStock');
    Route::get('print1/voucher', 'PrintStock1');
    // Route::get('print/signle-product', 'PrintSingleProduct');
    Route::get('load-stock-products', 'LoadProducts');
    
});

Route::controller(StockTransferReportController::class)->prefix('stock-transfer-report')->group(function () {
    Route::get('/', 'TransferReport');
    Route::get('ledger', 'InventoryReport');
    Route::get('print/report', 'PrintStock');
    
    
});


// Route::controller(SaleReportController::class)->prefix('sales-report')->group(function () {
//     Route::get('', 'index');
//     Route::get('', 'store');
//     Route::get('print/voucher', 'PrintStock');
// });

// Order Demand Reports
Route::controller(OrderDemandReportController::class)->prefix('order-demand-report')->group(function () {
    Route::get('/', 'report');
    Route::get('report/print', 'PrintReport');
    
});



// Inventory Reports
Route::controller(InventoryReport1Controller::class)->prefix('inventory1/')->group(function () {
    Route::get('ledger', 'InventoryReport');
    Route::get('stock-report1', 'StockReport');
    Route::get('print/voucher', 'PrintStock');
    
});

// Batch Stock Reports
Route::controller(BatchStockReportController::class)->prefix('batch-stock-report')->group(function () {
    Route::get('', 'index');
        Route::get('report', 'report');
        Route::get('report1', 'report1');
        Route::get('report-print', 'printPDF');
    
});

// Request Reports
Route::controller(RequestReportController::class)->prefix('request-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'printPDF');
    
});

// Request Reports
Route::controller(RequestWiseReportController::class)->prefix('request-wise-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'printPDF');
    
});

Route::controller(POReportController::class)->prefix('po-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'printPDF');
    
});

Route::controller(IGPReportController::class)->prefix('igp-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'printPDF');
});

Route::controller(GRNReportController::class)->prefix('grn-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'PrintReport');
});

Route::controller(DCDemandPOStatusReportController::class)->prefix('dc-demand-po-status-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'PrintReport');
    Route::get('load-po', 'loadPO');
    Route::get('load-product', 'loadProduct');
});

Route::controller(DCPOStatusReportController::class)->prefix('dc-po-status-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'PrintReport');
    Route::get('load-po', 'loadPO');
    Route::get('load-product', 'loadProduct');
});

Route::controller(SubPartyDemandPOReportController::class)->prefix('sub-party-demand-po-status-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'PrintReport');
    Route::get('load-po', 'loadPO');
    Route::get('load-product', 'loadProduct');
});

Route::controller(SubPartyOrderPOReportController::class)->prefix('sub-party-order-po-status-report')->group(function () {
    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'PrintReport');
    Route::get('load-po', 'loadPO');
    Route::get('load-product', 'loadProduct');
});


        // RECIPE LIST
        Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['RECIPE CREATION', 3]], function () {
            Route::controller(RecipeListController::class)->prefix('recipe-list/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('delete-voucher', 'DeleteVoucher');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('list/load-list', 'LoadList');
            });
        });

    // STOCK TRANSFER (PRODUCTION)
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['STOCK TRANSFER', 3]], function () {
        Route::controller(StockTransferController::class)->prefix('stock-transfer/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('destroy', 'destroy');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getProdut', 'productRecord');
            // Route::get('report', 'StockReport');
            Route::get('report', 'report');
        });
        
    });

        // STOCK TRANSFER (BATCH)
        Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['STOCK TRANSFER', 3]], function () {
            Route::controller(BatchStockController::class)->prefix('batch-stock-transfer')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('destroy', 'destroy');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('getProdut', 'productRecord');
                // Route::get('report', 'StockReport');
                Route::get('report', 'report');
            });
            
        });

        // ISSUANCE
        Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['ISSUANCE', 5]], function () {
            Route::controller(IssuanceController::class)->prefix('issuance')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('destroy', 'destroy');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('getProdut', 'productRecord');
                // Route::get('report', 'StockReport');
                Route::get('report', 'report');
                Route::get('{id}', 'edit');
            });

            // Route::middleware('checkSubMenu2Access:STOCK CONSUMPTION')->controller(StockConsumptionController::class)->prefix('stock-consumption')->group(function () {
            //     Route::get('', 'index');
            //     Route::post('', 'store');
            //     Route::post('delete-voucher', 'DeleteVoucher');
            //     Route::get('print/voucher', 'PrintVoucher');
            //     Route::get('load/record', 'editData');
            //     Route::get('load/next/record', 'LoadNextData');
            //     Route::get('load/previous/record', 'LoadPreviousData');
            //     Route::post('report', 'report');
            //     Route::get('change/warehouse', 'LoadProducts');
            //     Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            // });

            Route::controller(StockConsumptionController::class)->prefix('stock-consumption')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('destroy', 'destroy');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('getProdut', 'productRecord');
                // Route::get('report', 'StockReport');
                Route::get('report', 'report');
                Route::get('{id}', 'edit');
                Route::get('change/warehouse', 'LoadProducts');
                Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            });
            
        });

        
           // ISSUANCE RETURN
           Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['STOCK TRANSFER', 3]], function () {
            Route::controller(IssuanceReturnController::class)->prefix('issuance-return')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('destroy', 'destroy');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('getProdut', 'productRecord');
                // Route::get('report', 'StockReport');
                Route::get('report', 'report');
                Route::get('{id}', 'edit');
            });
            
        });

    // inventory/stock-report
    // stock-transfer/report

// Route::resource('location', LocationController::class);
// Route::get('location/{id}/destroy', [LocationController::class, 'destroy']);

// Departments
// Route::resource('departments', DepartmentsController::class);
// Route::get('departments/destroy/{id}', [DepartmentsController::class, 'destroy']);


// Route::get('rawmaterial-to-salepoint', [RawMaterialToSalePointController::class, 'index']);
// Route::get('rawmaterial-to-salepoint/{id}/edit', [RawMaterialToSalePointController::class, 'edit']);
// Route::patch('rawmaterial-to-salepoint/{id}', [RawMaterialToSalePointController::class, 'update']);
// Route::get('rawmaterial-to-salepoint/{id}/destroy', [RawMaterialToSalePointController::class, 'destroy']);


// Route::resource('product-rates', ProductRateController::class);
// Route::get('product-rates/{id}/destroy', [ProductRateController::class, 'destroy']);



//Tax
// Route::resource('taxes', TaxController::class);
// Route::get('taxes/{id}/destroy', [TaxController::class, 'destroy']);

// //Saletax Report
// Route::resource('salestax-report/all-party', SaleTaxReportController::class);
// Route::get('salestax-report/single-party/add', [SaleTaxReportController::class, 'SingleParty']);
// Route::post('salestax-report/single-party/add/report', [SaleTaxReportController::class, 'ShowSingleParty']);
Route::controller(SaleTaxReportController::class)->prefix('salestax-report')->group(function () {
    Route::get('', 'index');
    // Route::post('', 'store');
    Route::get('print/report', 'PrintReport');
});

//Stock Report
// Route::get('stock-report/all-items', [StockReportController::class, 'AllItems']);

// //Expense Report
// Route::resource('expense-report', ExpenseReportController::class);



// LEDGERS | REPORTS
// CUSTOMER LEDGER
Route::controller(CustomerReportsController::class)->prefix('customer-reports')->group(function () {
    Route::get('ledger', 'CustomerReport');
    Route::get('ledger/pdf', 'LedgerPDF');
    Route::get('balance', 'CustomerBalance');
    Route::get('aging', 'CustomerAging');
    
});

// CUSTOMER LEDGER ACCOUNT (SPI-parity new module — does not replace customer-reports)
Route::controller(CustomerLedgerAccountController::class)->prefix('customer-ledger-account')->group(function () {
    Route::get('', 'index');
    Route::get('pdf', 'LedgerPDF');
});

Route::controller(CashBookController::class)->prefix('cash-book')->group(function () {
    Route::get('ledger', 'CustomerReport');
    Route::get('ledger/pdf', 'LedgerPDF');
    Route::get('balance', 'CustomerBalance');
    Route::get('aging', 'CustomerAging');
});

Route::controller(CashBookSingleDateController::class)->prefix('cash-book-single')->group(function () {
    Route::get('ledger', 'CustomerReport');
    Route::get('ledger/pdf', 'LedgerPDF');
    Route::get('balance', 'CustomerBalance');
    Route::get('aging', 'CustomerAging');
});

Route::controller(SupplierReportsController::class)->prefix('supplier-reports')->group(function () {
    Route::get('create', 'CustomerReport');
    Route::get('ledger', 'CustomerReport');
    Route::get('ledger/pdf', 'LedgerPDF');
    Route::get('balance', 'CustomerBalance');
    Route::get('aging', 'CustomerAging');
});

Route::controller(PurchaseReportController::class)->prefix('purchase-report')->group(function () {
    // Route::get('', 'index');
    // Route::get('ledger', 'store');
    // Route::get('ledger/pdf', 'LedgerPDF');

    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'printPDF');
    // Route::get('balance', 'CustomerBalance');
    // Route::get('aging', 'CustomerAging');
});

Route::controller(PurchaseTaxReportController::class)->prefix('purchasetax-report')->group(function () {
    // Route::get('', 'index');
    // Route::get('ledger', 'store');
    // Route::get('ledger/pdf', 'LedgerPDF');

    Route::get('', 'index');
    Route::get('report', 'report');
    Route::get('report-print', 'printPDF');
    // Route::get('balance', 'CustomerBalance');
    // Route::get('aging', 'CustomerAging');
});

    // PURCHASE REPORTS
    // Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['REPORTS', 5]], function () {
    //     Route::get('purchase/report', [PurchaseReportController::class, 'index'])->middleware('checkSubMenu2Access:PURCHASE REPORT');
    //     Route::get('purchase-return/report', [PurchaseReturnController::class, 'report'])->middleware('checkSubMenu2Access:PURCHASE RETURN REPORT');
    // });

Route::controller(JournalLedgerController::class)->prefix('financial-reports/')->group(function () {
    // Route::get('ledger', 'LedgerReport');
    Route::get('journal-ledger', 'JournalLedger');
    Route::get('ledger/pdf', 'LedgerPDF');
    // Route::get('balance', 'CustomerBalance');
    // Route::get('aging', 'CustomerAging');
});

Route::controller(GeneralJournalController::class)->prefix('general-journal/')->group(function () {
    // Route::get('ledger', 'LedgerReport');
    Route::get('/', 'index');
    Route::get('report', 'report');
    // Route::get('balance', 'CustomerBalance');
    // Route::get('aging', 'CustomerAging');
});

Route::controller(AllPartyBalanceController::class)->prefix('all-parties-balance')->group(function () {
    // Route::get('ledger', 'LedgerReport');
    Route::get('/', 'index');
    Route::get('report', 'report');
    // Route::get('balance', 'CustomerBalance');
    // Route::get('aging', 'CustomerAging');
});
Route::controller(TrialBalanceController::class)->prefix('trial-balance/')->group(function () {
    // Route::get('ledger', 'LedgerReport');
    Route::get('/', 'index');
    Route::get('report', 'report');
    // Route::get('balance', 'CustomerBalance');
    // Route::get('aging', 'CustomerAging');
});
// financial-reports/journal-ledger

///////////////////////////// MIDDLEWARES START ////////////////////////////////////////

// DEFINATION MENU RIGHTS
Route::group(['middleware' => 'checkMenuAccess', 'menu' => 'DEFINITION'], function () {
    // ACCOUNT GROUPS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['ACCOUNT GROUPS', 1]], function () {
        // ACCOUNT GROUP 1
        Route::resource('account-group', AccountGroupController::class)->middleware('checkSubMenu2Access:ACCOUNT GROUP 1');
        Route::get('account-group/destroy/{id}', [AccountGroupController::class, 'destroy'])->middleware('checkSubMenu2Access:ACCOUNT GROUP 1');

        // ACCOUNT GROUP 2
        Route::resource('account-group2', AccountGroup2Controller::class)->middleware('checkSubMenu2Access:ACCOUNT GROUP 2');
        Route::get('account-group2/destroy/{id}', [AccountGroup2Controller::class, 'destroy'])->middleware('checkSubMenu2Access:ACCOUNT GROUP 2');

        // ACCOUNT GROUP 3
        Route::resource('account-group3', AccountGroup3Controller::class)->middleware('checkSubMenu2Access:ACCOUNT GROUP 3');
        Route::get('account-group3/destroy/{id}', [AccountGroup3Controller::class, 'destroy'])->middleware('checkSubMenu2Access:ACCOUNT GROUP 3');
        Route::get('/get-ag1-data', [AccountGroup3Controller::class, 'GetAg1Data']);
        Route::get('/get-ag2-data', [AccountGroup3Controller::class, 'GetAg2Data']);
    });

    // PRODUCTS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCT INFORMATION', 1]], function () {
        //PRODUCTS GROUP | CATEGORIES
        Route::resource('product-group', ProductCategoriesController::class)->middleware('checkSubMenu2Access:PRODUCT GROUP');
        Route::get('product-group/destroy/{id}', [ProductCategoriesController::class, 'destroy'])->middleware('checkSubMenu2Access:PRODUCT GROUP');

        //PRODUCTS
        Route::resource('products', ProductController::class)->middleware('checkSubMenu2Access:PRODUCT INFORMATION');
        Route::get('products/warehouse-products', [ProductController::class, 'WareHouseProducts'])->middleware('checkSubMenu2Access:PRODUCT INFORMATION');


        // Route::resource('products-mapping', ProductMappingController::class)->middleware('checkSubMenu2Access:UTILITIES');
        Route::resource('products-mapping', ProductMappingController::class);
        
        Route::resource('products-report', ProductsReportController::class)->middleware('checkSubMenu2Access:PRODUCTS REPORT');
        Route::get('products-report/print/voucher', [ProductsReportController::class, 'PrintProducts'])->middleware('checkSubMenu2Access:PRODUCTS REPORT');

        Route::get('products/destroy/{id}', [ProductController::class, 'destroy'])->middleware('checkSubMenu2Access:PRODUCT INFORMATION');
        Route::get('products/godown/record', [ProductController::class, 'deptt']);
        Route::post('import-products', [ProductController::class, 'ImportProducts'])->middleware('checkSubMenu2Access:PRODUCT INFORMATION');
        Route::get('products/print/voucher', [ProductController::class, 'PrintProducts'])->middleware('checkSubMenu2Access:PRODUCT INFORMATION');
        
        // Route::get('products/warehouse-products', [ProductController::class, 'WareHouseProducts']);

        //RATE LIST
        Route::middleware('checkSubMenu2Access:RATE LIST')
        ->controller(RateListController::class)
        ->prefix('rate-list/')
        ->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('report', 'Report');
            Route::get('report/order-by', 'OrderByReport');
            Route::get('report/category-products', 'CategoryProductsReport');
            Route::get('rate-list/report/product', 'SingleProductReport')->name('single-product-ratelist-report');
        });
    });

    // ACCOUNTS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['ACCOUNTS', 1]], function () {
        //PARTIES || CHART OF ACCOUNT
        Route::resource('parties', PartyController::class)->middleware('checkSubMenu2Access:CHART OF ACCOUNT');
        Route::get('parties/destroy/{id}', [PartyController::class, 'destroy'])->middleware('checkSubMenu2Access:CHART OF ACCOUNT');
        Route::get('parties/export/excel', [PartyController::class, 'ExportExcel'])->name('export-party-excel');
        Route::get('parties/export/pdf', [PartyController::class, 'ExportPDF'])->name('export-party-pdf');

        // BANKS INFORMATION
        Route::resource('banks', BanksController::class)->middleware('checkSubMenu2Access:BANKS');
        Route::get('banks/destroy/{id}', [BanksController::class, 'destroy'])->middleware('checkSubMenu2Access:BANKS');

        // CITIES INFORMATION
        Route::resource('cities', CitiesController::class)->middleware('checkSubMenu2Access:CITIES');
        Route::get('cities/destroy/{id}', [CitiesController::class, 'destroy'])->middleware('checkSubMenu2Access:CITIES');

        // WAREHOUSE || GODOWN
        Route::resource('warehouses', WarehouseController::class)->middleware('checkSubMenu2Access:GODOWN');
        Route::get('warehouses/destroy/{id}', [WarehouseController::class, 'destroy'])->middleware('checkSubMenu2Access:GODOWN');
        // Department
        Route::resource('departments', DepartmentsController::class)->middleware('checkSubMenu2Access:DEPARTMENTS');
        Route::get('departments/destroy/{id}', [DepartmentsController::class, 'destroy'])->middleware('checkSubMenu2Access:DEPARTMENTS');
    });

    // PARTY PROFILE
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PARTY PROFILE', 1]], function () {
        Route::middleware('checkSubMenu2Access:LEADS')->controller(LeadController::class)->prefix('leads')->group(function () {
            Route::get('', 'index')->name('leads.index');
            Route::post('', 'store')->name('leads.store');
            Route::get('data', 'data')->name('leads.data');
            Route::get('events', 'events')->name('leads.events');
            Route::get('duplicates', 'duplicates')->name('leads.duplicates');
            Route::get('{party}', 'show')->name('leads.show');
            Route::patch('{party}', 'update')->name('leads.update');
            Route::post('{party}/follow-ups', 'followUp')->name('leads.follow-ups.store');
            Route::post('{party}/convert', 'convert')->name('leads.convert');
        });

        // CUSTOMERS
        Route::resource('customers', CustomersController::class)->middleware('checkSubMenu2Access:CUSTOMERS');
        Route::get('customers/destroy/{id}', [CustomersController::class, 'destroy'])->middleware('checkSubMenu2Access:CUSTOMERS');
        Route::post('import-customers', [CustomersController::class, 'ImportCustomers'])->middleware('checkSubMenu2Access:CUSTOMERS');
        // Route::get('import-customers/create', [CustomersController::class, 'destroy'])->middleware('checkSubMenu2Access:CUSTOMERS');
        Route::get('export-customers-pdf', [CustomersController::class, 'ExportPDF'])->name('export-customers-pdf');


        // CUSTOMERS
        Route::resource('customer-careof', CustomerCareOfController::class)->middleware('checkSubMenu2Access:CUSTOMER CAREOF');
        Route::get('customer-careof/destroy/{id}', [CustomerCareOfController::class, 'destroy'])->middleware('checkSubMenu2Access:CUSTOMER CAREOF');
        // Route::post('import-customers', [CustomerCareOfController::class, 'ImportCustomers'])->middleware('checkSubMenu2Access:CUSTOMER CAREOF');
        // Route::get('import-customers/create', [CustomerCareOfController::class, 'destroy'])->middleware('checkSubMenu2Access:CUSTOMERS');
        Route::get('export-customers-careof-pdf', [CustomerCareOfController::class, 'ExportPDF'])->name('export-customers-pdf');
        Route::get('customer-careof/edit/record', [CustomerCareOfController::class, 'EditCustomer']);
        Route::get('customer-careof/destroy/{id}', [CustomerCareOfController::class, 'destroy'])->middleware('checkSubMenu2Access:CUSTOMER CAREOF');
        // SUPPLIERS
        Route::resource('supplier', SupplierController::class)->middleware('checkSubMenu2Access:SUPPLIERS');
        Route::get('supplier/destroy/{id}', [SupplierController::class, 'destroy'])->middleware('checkSubMenu2Access:SUPPLIERS');
        Route::post('import-suppliers', [SupplierController::class, 'ImportSuppliers'])->middleware('checkSubMenu2Access:CUSTOMERS');
        Route::get('export-suppliers-pdf', [SupplierController::class, 'ExportPDF'])->name('export-suppliers-pdf');

        // PURCHASER
        Route::resource('purchasers', PurchaserController::class)->middleware('checkSubMenu2Access:PURCHASER');
        Route::get('purchasers/destroy/{id}', [PurchaserController::class, 'destroy'])->middleware('checkSubMenu2Access:PURCHASER');

        // EMPLOYEES
        Route::resource('employees', EmployeeController::class)->middleware('checkSubMenu2Access:EMPLOYEES');
        Route::get('employees/destroy/{id}', [EmployeeController::class, 'destroy'])->middleware('checkSubMenu2Access:EMPLOYEES');
        Route::get('employees/print/record', [EmployeeController::class, 'PrintEmployee'])->middleware('checkSubMenu2Access:EMPLOYEES');

        // DESIGNATIONS
        Route::resource('designations', DesignationController::class)->middleware('checkSubMenu2Access:DESIGNATION');
        Route::get('designations/destroy/{id}', [DesignationController::class, 'destroy'])->middleware('checkSubMenu2Access:DESIGNATION');

        // EMPLOYEE TYPES
        Route::resource('employee-types', EmployeeTypeController::class)->middleware('checkSubMenu2Access:EMPLOYEE TYPES');
        Route::get('employee-types/destroy/{id}', [EmployeeTypeController::class, 'destroy'])->middleware('checkSubMenu2Access:EMPLOYEE TYPES');
    });


    // OPENINGS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['OPENING', 1]], function () {
        // OPENING STOCK VOUCHER
        Route::middleware('checkSubMenu2Access:OPENING STOCK')->controller(OpeningStockController::class)->prefix('opening-stock/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::post('report', 'report');
            Route::get('change/warehouse', 'LoadProducts');
        });


        // OPENING BALANCE VOUCHER
        Route::middleware('checkSubMenu2Access:OPENING BALANCE')->controller(OpeningBalanceVoucher::class)->prefix('opening-balance/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::post('report', 'report');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            Route::get('{id}', 'edit');
        });

        // OPENING BALANCE VOUCHER
        Route::middleware('checkSubMenu2Access:OPENING PET ROLLS')->controller(OpeningPetRollController::class)->prefix('opening-pet-rolls/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::post('report', 'report');
            Route::get('change/warehouse', 'LoadProducts');
        });

        Route::middleware('checkSubMenu2Access:PURCHASE ROLLS')->controller(PurchaseRollController::class)->prefix('purchase-rolls/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::post('report', 'report');
            Route::get('change/warehouse', 'LoadProducts');
            Route::get('load-igp-qty', 'LoadIGPqty');
        });
    });
});
// HR
Route::group(['middleware' => 'checkMenuAccess', 'menu' => 'HR'], function () {
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['EMPLOYEES', 7]], function () {
        // EMPLOYEES
        Route::resource('addemployees', EmployeeController::class)->middleware('checkSubMenu2Access:ADD EMPLOYEES');
        Route::get('employees/destroy/{id}', [EmployeeController::class, 'destroy'])->middleware('checkSubMenu2Access:ADD EMPLOYEES');
        Route::get('employees/print/record', [EmployeeController::class, 'PrintEmployee'])->middleware('checkSubMenu2Access:ADD EMPLOYEES');
        Route::get('employees/edit/record', [EmployeeController::class, 'EditEmployee']);
    });
});
// TRADE GROUP
Route::middleware('checkSubMenu2Access:TRADE GROUP')->group(function () {
    Route::resource('trade-group', TradeGroupController::class);
    Route::get('trade-group/destroy/{id}', [TradeGroupController::class, 'destroy']);
});


// SYSTEM MENU RIGHTS
Route::group(['middleware' => 'checkMenuAccess', 'menu' => 'SYSTEM'], function () {
    // USERS MANAGEMENT
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['USER INFORMATION', 2]], function () {
        Route::resource('users', UserController::class);
        Route::get('users/destroy/{id}', [UserController::class, 'destroy']);
    });

  

    // USERS RIGHTS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['USER RIGHTS', 2]], function () {
        Route::get('user-rights', [UserController::class, 'UserRights']);
        Route::get('user-rights/menus-rights/{userId}', [UserController::class, 'UserMenuRightsView']);
        Route::post('menus-rights', [UserController::class, 'MenusStore']);
        Route::get('user-rights/vouchers-rights/{userId}', [UserController::class, 'VoucherRightsView']);
        Route::post('vouchers-rights', [UserController::class, 'VoucherRigthStore']);
    });
});


// TRANSACTIONS
Route::group(['middleware' => 'checkMenuAccess', 'menu' => 'TRANSACTIONS'], function () {

    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['REQUEST GENERATE', 3]], function () {
        // REQUEST GENERATE
        Route::controller(RequestGenerateController::class)->prefix('request-generate/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('destroy', 'destroy');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            // Route::get('request-report', 'report');
            Route::get('all-request-generate', 'allRequestGenerate');
        });
    });


    // Route::middleware('checkSubMenu2Access:GRN')->controller(GRNController::class)->prefix('grn/')->group(function () {
    //     Route::get('', 'index');
    //     Route::post('', 'store');
    //     Route::post('destroy', 'destroy');
    //     Route::get('print/voucher', 'PrintVoucher');
    //     Route::get('load/record', 'editData');
    //     Route::get('load/next/record', 'LoadNextData');
    //     Route::get('load/previous/record', 'LoadPreviousData');
    //     Route::get('load/igp/record', 'LoadIGPData');
    //     Route::get('grn-report', 'report');
    //     Route::get('grn-report-print', 'PrintReport');
    // });
    
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['REQUEST GENERATE', 3]], function () {
        // PURCHASE ORDER
        Route::controller(PurchaseOrderController::class)->prefix('purchase-order/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('destroy', 'destroy');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/request', 'LoadRequest');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
        });
    });
    // RECIPE CREATION
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['RECIPE CREATION', 3]], function () {
        Route::controller(RecipeCreationController::class)->prefix('recipe-creation/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getProdutRocord', 'ProductRecord');
            Route::get('getWarehouseproduct', 'WarehouseProduct');
        });
    });
    // PRODUCTION
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
        Route::controller(ProductionController::class)->prefix('production/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('recipe/products', 'RecipeProducts');
            Route::get('recipeshow/recipename', 'RecipeNames');
            
            // Route::get('production-report', 'ProductionReport');
        });

        Route::controller(PetRollProductionController::class)->prefix('petroll-production')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('recipe/products', 'RecipeProducts');
            Route::get('recipeshow/recipename', 'RecipeNames');
            
            // Route::get('production-report', 'ProductionReport');
        });
    });
    
    Route::get('/store', [HomeController::class, 'store']);
    Route::get('recipeprint/recipepro', [HomeController::class, 'ProPrint']);
        // PRODUCTION
        Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
            Route::controller(ProductionOneController::class)->prefix('production-one')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('delete-voucher', 'DeleteVoucher');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('recipe/products', 'RecipeProducts');
                Route::get('recipeshow/recipename', 'RecipeNames');
                // Route::get('production-report', 'ProductionReport');
            });
        });

        // THERMOFORMING PRODUCTION
        Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
            Route::controller(ThermoformingProductionController::class)->prefix('thermoforming-production/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::get('load-role-data', 'RoleData');
                Route::post('delete-voucher', 'destroy');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('production-report', 'ProductionReport');
                Route::get('production-report/print', 'ProductionReportPrint');
            });

            // Route::resource('thermoforming-production', ThermoformingProductionController::class)->middleware('checkSubMenu2Access:THERMOFORMING PRODUCTION');
        });


          // SLITTING PRODUCTION
          Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
            Route::controller(SlittingProductionController::class)->prefix('slitting-production/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::get('load-role-data', 'RoleData');
                Route::post('delete-voucher', 'destroy');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('production-report', 'ProductionReport');
                Route::get('production-report/print', 'ProductionReportPrint');
            });

            // Route::resource('thermoforming-production', ThermoformingProductionController::class)->middleware('checkSubMenu2Access:THERMOFORMING PRODUCTION');
        });

        
               // SLITTING PRODUCTION
               Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
                Route::controller(SlittingStockTransferController::class)->prefix('slitting-stock-transfer/')->group(function () {
                    Route::get('', 'index');
                    Route::post('', 'store');
                    Route::get('load-role-data', 'RoleData');
                    Route::get('load/products', 'LoadProducts');
                    Route::post('delete-voucher', 'destroy');
                    Route::get('print/voucher', 'PrintVoucher');
                    Route::get('load/record', 'editData');
                    Route::get('load/next/record', 'LoadNextData');
                    Route::get('load/previous/record', 'LoadPreviousData');
                    Route::get('production-report', 'ProductionReport');
                    Route::get('production-report/print', 'ProductionReportPrint');
                });
    
                // Route::resource('thermoforming-production', ThermoformingProductionController::class)->middleware('checkSubMenu2Access:THERMOFORMING PRODUCTION');
            });

                    // Packing PRODUCTION
                    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
                        Route::controller(PackingProductionController::class)->prefix('packing-production')->group(function () {
                            Route::get('', 'index');
                            Route::post('', 'store');
                            Route::get('load-thermoforming-production', 'ThermoformingProduction');
                            Route::get('load/products', 'LoadProducts');
                            Route::post('delete-voucher', 'destroy');
                            Route::get('print/voucher', 'PrintVoucher');
                            Route::get('load/record', 'editData');
                            Route::get('load/next/record', 'LoadNextData');
                            Route::get('load/previous/record', 'LoadPreviousData');
                            Route::get('production-report', 'ProductionReport');
                            Route::get('production-report/print', 'ProductionReportPrint');
                        });
            
                        // Route::resource('thermoforming-production', ThermoformingProductionController::class)->middleware('checkSubMenu2Access:THERMOFORMING PRODUCTION');
                    });


                       // PACKING REPORT PRODUCTION
                    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
                        Route::controller(PackingProductionReportController::class)->prefix('packing-production-report')->group(function () {
                            Route::get('', 'index');
                            Route::get('print', 'ProductionReportPrint');
                        });
                    });


                    // Route::get('production-report', 'ProductionReport');
                    // Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION REPORT', 3]], function () {
                    //     Route::controller(ProductionReportController::class)->prefix('production-report')->group(function () {
                    //         Route::get('', 'ProductionReport');
                    //         Route::get('print', 'ProductionReportPrint');
                    //     });
                    // });

                    Route::controller(ProductionReportController::class)->prefix('production-report')->group(function () {
                        Route::get('', 'index');
                        // Route::get('report', 'report');
                        Route::get('print', 'printPDF');
                    });

                // THERMOFORMING PRODUCTION REPORT
                Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PRODUCTION', 3]], function () {
                    Route::controller(ThermoformingProductionReportController::class)->prefix('thermoforming-production/production-report')->group(function () {
                        Route::get('', 'ProductionReport');
                        Route::get('print', 'ProductionReportPrint');
                    });
        
                    // Route::resource('thermoforming-production', ThermoformingProductionController::class)->middleware('checkSubMenu2Access:THERMOFORMING PRODUCTION');
                });




    // Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['THERMOFORMING PRODUCTION', 3]], function () {
    //     Route::resource('thermoforming-production', ThermoformingProductionController::class);
    //     Route::get('thermoforming-production/destroy/{id}', [ThermoformingProductionController::class, 'destroy']);
    // });
});
Route::get('productionlisting/listings', [HomeController::class, 'Listings_pro']);
// FINANCIAL MENU RIGHTS
Route::group(['middleware' => 'checkMenuAccess', 'menu' => 'FINANCIAL'], function () {
    // FINANCIAL VOUCHERS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['VOUCHERS', 4]], function () {
        // CASH RECEIPTS VOUCHER
        Route::middleware('checkSubMenu2Access:CASH RECEIPT VOUCHER')->group(function () {
            Route::controller(CashReceiptController::class)->prefix('cash-receipts/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                // Route::post('delete-voucher', 'DeleteVoucher')->middleware('VoucherRightAccess:CASH RECEIPT VOUCHER,DELETE');
                Route::post('delete-voucher', 'DeleteVoucher');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
                // Route::get('load/record', 'editData')->middleware('VoucherRightAccess:CASH RECEIPT VOUCHER,EDIT');
                Route::get('load/record', 'editData');
                // Route::get('load/next/record', 'LoadNextData')->middleware('VoucherRightAccess:CASH RECEIPT VOUCHER,EDIT');
                Route::get('load/next/record', 'LoadNextData');
                // Route::get('load/previous/record', 'LoadPreviousData')->middleware('VoucherRightAccess:CASH RECEIPT VOUCHER,EDIT');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('{id}', 'edit');
            });
        });

        // CASH PAYMENTS VOUCHER
        Route::middleware('checkSubMenu2Access:CASH PAYMENT VOUCHER')->group(function () {
            Route::controller(CashPaymentController::class)->prefix('cash-payments/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                // Route::post('delete-voucher', 'DeleteVoucher')->middleware('VoucherRightAccess:CASH PAYMENT VOUCHER,DELETE');
                Route::post('delete-voucher', 'DeleteVoucher');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('{id}', 'edit');
                // Route::get('load/record', 'editData')->middleware('VoucherRightAccess:CASH PAYMENT VOUCHER,EDIT');
                // Route::get('load/next/record', 'LoadNextData')->middleware('VoucherRightAccess:CASH PAYMENT VOUCHER,EDIT');
                // Route::get('load/previous/record', 'LoadPreviousData')->middleware('VoucherRightAccess:CASH PAYMENT VOUCHER,EDIT');
            });
        });

        // BANK RECEIPT VOUCHER
        Route::middleware('checkSubMenu2Access:BANK RECEIPT VOUCHER')->group(function () {
            Route::controller(BankReceiptController::class)->prefix('bank-receipts/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('delete-voucher', 'DeleteVoucher')->middleware('VoucherRightAccess:BANK RECEIPT VOUCHER,DELETE');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('{id}', 'edit');
                // Route::get('load/record', 'editData')->middleware('VoucherRightAccess:BANK RECEIPT VOUCHER,EDIT');
                // Route::get('load/next/record', 'LoadNextData')->middleware('VoucherRightAccess:BANK RECEIPT VOUCHER,EDIT');
                // Route::get('load/previous/record', 'LoadPreviousData')->middleware('VoucherRightAccess:BANK RECEIPT VOUCHER,EDIT');
            });
        });

        // BANK PAYMENTS VOUCHER
        Route::middleware('checkSubMenu2Access:BANK PAYMENT VOUCHER')->group(function () {
            Route::controller(BankPaymentController::class)->prefix('bank-payments/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('delete-voucher', 'DeleteVoucher')->middleware('VoucherRightAccess:BANK PAYMENT VOUCHER,DELETE');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                // Route::get('load/record', 'editData')->middleware('VoucherRightAccess:BANK PAYMENT VOUCHER,EDIT');
                // Route::get('load/next/record', 'LoadNextData')->middleware('VoucherRightAccess:BANK PAYMENT VOUCHER,EDIT');
                // Route::get('load/previous/record', 'LoadPreviousData')->middleware('VoucherRightAccess:BANK PAYMENT VOUCHER,EDIT');
                Route::post('report', 'report');
                Route::get('{id}', 'edit');
            });
        });

        // JOURNAL VOUCHER
        Route::middleware('checkSubMenu2Access:JOURNAL VOUCHER')->group(function () {
            // JOURNAL VOUCHER
            Route::controller(JournalVoucherController::class)->prefix('journal-voucher/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                // Route::post('delete-voucher', 'DeleteVoucher')->middleware('VoucherRightAccess:JOURNAL VOUCHER,DELETE');
                Route::post('delete-voucher', 'DeleteVoucher');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                // Route::get('load/record', 'editData')->middleware('VoucherRightAccess:JOURNAL VOUCHER,EDIT');
                // Route::get('load/next/record', 'LoadNextData')->middleware('VoucherRightAccess:JOURNAL VOUCHER,EDIT');
                // Route::get('load/previous/record', 'LoadPreviousData')->middleware('VoucherRightAccess:JOURNAL VOUCHER,EDIT');
                Route::post('report', 'report');
                Route::get('{id}', 'edit');
            });
        });
    });

    // PENDING VOUCHERS FOR APPROVAL
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['APPROVAL', 4]], function () {
        // PENDING VOUCHER
        Route::middleware('checkSubMenu2Access:PENDING VOUCHER')->controller(PendingVouchersController::class)->prefix('pending-voucher/')->group(function () {
            Route::get('', 'index');
            Route::get('approve/{voucher_id}', 'UpdateStatus');
            Route::get('delete/{voucher_id}', 'DeleteVoucher');
        });
    });
});


// FINANCIAL REPORTS
Route::controller(FinancialReportsController::class)->prefix('financial-reports/')->group(function () {
    Route::get('activity', 'AccountActivity');
});


Route::controller(AttendanceController::class)->prefix('employees-attendance')->group(function () {
    Route::get('/', 'create');
    Route::post('add-attendce', 'store');
    Route::get('getEmployeeAttendence', 'getEmployeeAttendence');
    Route::post('update-attendce', 'update_attendce');
    Route::get('getDeptEmployee', 'get_dept_employee');
});

Route::controller(SalesReportController::class)->prefix('sales-report')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('print/report', 'PrintReport');
});

//SALARY SHEET REPORT
Route::controller(SalarySheetController::class)->prefix('salary-sheet-report')->group(function () {
    Route::get('/', 'index');
    Route::get('salary/pdf', 'SalarysheetPDF');
    
});

        // Route::get('sales-voucher/report', [SalesController::class, 'report'])->middleware('checkSubMenu2Access:SALES');
        // Route::get('sales-voucher/report', [SalesReturnController::class, 'report'])->middleware('checkSubMenu2Access:SALE RETURN');

Route::controller(AdvanceSalaryController::class)->prefix('advance-salary/')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::post('delete-voucher', 'DeleteVoucher');
    Route::get('print/voucher', 'PrintVoucher');
    Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
    Route::get('load/record', 'editData');
    Route::get('load/next/record', 'LoadNextData');
    Route::get('load/previous/record', 'LoadPreviousData');
    Route::get('{id}', 'edit');
});

//SALARY SHEET REPORT
Route::controller(SalarySheetController::class)->prefix('salary-sheet-report')->group(function () {
    Route::get('/', 'index');
    Route::get('salary/pdf', 'SalarysheetPDF');
    
});


Route::controller(DirectSalesController::class)->prefix('direct-sales')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::post('delete-voucher', 'DeleteVoucher');
    Route::get('print/voucher', 'PrintVoucher');
    Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
    Route::get('party/last-invoice', 'partyLastInvoice');
    Route::get('load/record', 'editData');
    Route::get('load/next/record', 'LoadNextData');
    Route::get('load/previous/record', 'LoadPreviousData');
    Route::get('{id}', 'edit');
});

Route::controller(QuotationController::class)->prefix('quotation')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::post('delete-voucher', 'DeleteVoucher');
    Route::get('print/voucher', 'PrintVoucher');
    Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
    Route::get('party/info', 'partyInfo');
    Route::get('load/record', 'editData');
    Route::get('load/next/record', 'LoadNextData');
    Route::get('load/previous/record', 'LoadPreviousData');
});

Route::controller(DirectPurchaseController::class)->prefix('direct-purchases')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::post('delete-voucher', 'DeleteVoucher');
    Route::get('print/voucher', 'PrintVoucher');
    Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
    Route::get('load/record', 'editData');
    Route::get('load/next/record', 'LoadNextData');
    Route::get('load/previous/record', 'LoadPreviousData');
    Route::get('{id}', 'edit');
    Route::get('product/keyup', 'getProduct');
});

// INVENTORY TAB
Route::group(['middleware' => 'checkMenuAccess', 'menu' => 'INVENTORY'], function () {
    // GATEPASS VOUCHERS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['GATEPASS', 5]], function () {
        // INWARD GATEPASS
        Route::middleware('checkSubMenu2Access:INWARD GATEPASS')->controller(InwardGatePassController::class)->prefix('inward-gatepass/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('destroy', 'destroy');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('requestgenerate/record', 'requestgenerate');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('warehouse-requests', 'WarehouseRequests');
            Route::get('load-requests-data', 'LoadRequestData');
            Route::get('load-edit-requests-data', 'LoadEditRequestData');
            // Route::get('test/test/test', 'test');
        });

        // GRN (GOODS RECEIPT NOTE)
        Route::middleware('checkSubMenu2Access:GRN')->controller(GRNController::class)->prefix('grn/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('destroy', 'destroy');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('load/igp/record', 'LoadIGPData');
            // Route::get('grn-report', 'report');
            // Route::get('grn-report-print', 'PrintReport');
        });
    });

    // PURCHASE VOUCHERS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PURCHASE', 5]], function () {
        // PURCHASE VOUCHER
            Route::middleware('checkSubMenu2Access:PURCHASE VOUCHER')->controller(PurchaseController::class)->prefix('purchase/')->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::post('delete-voucher', 'DeleteVoucher');
                Route::get('print/voucher', 'PrintVoucher');
                Route::get('load/record', 'editData');
                Route::get('load/next/record', 'LoadNextData');
                Route::get('load/previous/record', 'LoadPreviousData');
                Route::get('grn/record', 'LoadGrnRcord');
                Route::get('{id}', 'edit');
                // Route::get('report', 'report');
            });

            // PURCHASE VOUCHERS
    // Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['PURCHASE', 5]], function () {
        // PURCHASE VOUCHER
        Route::middleware('checkSubMenu2Access:PURCHASE TAX VOUCHER')->controller(PurchaseTaxController::class)->prefix('purchase-tax')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('grn/record', 'LoadGrnRcord');
            Route::get('{id}', 'edit');
            // Route::get('report', 'report');
        });

        // PURCHASE RETURN VOUCHER
        Route::middleware('checkSubMenu2Access:PURCHASE RETURN')->controller(PurchaseReturnController::class)->prefix('purchase-return/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('{id}', 'edit');
            Route::get('load/invoice', 'LoadInvoices');
            // Route::get('report', 'report');
        });

        // PURCHASER STOCK
        Route::middleware('checkSubMenu2Access:PURCHASER STOCK')->controller(PurchaserStockController::class)->prefix('purchaser-stock/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('destroy', 'destroy');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/igp/record', 'LoadIGPData');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
        });

        // PURCHASER REPORTS
        Route::get('purhcaser-report', [PurchaserReportController::class, 'Report'])->middleware('checkSubMenu2Access:PURCHASER REPORT');
    });



    // SUPPLIER REPORTS
    // Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['SUPPLIER REPORTS', 5]], function () {
    //     Route::get('purchase/report', [PurchaseController::class, 'report']);
    //     Route::get('purchase-return/report', [PurchaseReturnController::class, 'report']);
    // });
});


// SALES TAB
Route::group(['middleware' => 'checkMenuAccess', 'menu' => 'SALES'], function () {
    // SALES VOUCHERS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['VOUCHERS', 6]], function () {
        // SALE ORDER VOUCHER
        Route::middleware('checkSubMenu2Access:SALE ORDER')->controller(SaleOrderController::class)->prefix('sales-order/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('report', 'report');
            Route::get('getcustomer/product', 'getCustomerProduct');
            Route::get('product/keyup', 'getProduct');
        });

        // SALE DEMAND VOUCHER
        Route::middleware('checkSubMenu2Access:SALE DEMAND')->controller(SaleDemandController::class)->prefix('sales-demand/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            // Route::get('report', 'report');
            // Route::get('report/print', 'PrintReport');
            Route::get('getcustomer/product', 'getCustomerProduct');
            Route::get('product/keyup', 'getProduct');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
        });


        // DELIVERY CHALLAN (GST)VOUCHER
        Route::middleware('checkSubMenu2Access:DC ORDER')->controller(DeliveryChallanController::class)->prefix('delivery-challan/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('report', 'Report');
            Route::get('sale-order/recorde/fetch', 'dataFetch');
        });
        // DELIVERY CHALLAN (NON GST)VOUCHER
        Route::middleware('checkSubMenu2Access:DC DEMAND')->controller(DeliveryChallanNoGSTController::class)->prefix('delivery-challan-non-gst/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('report', 'Report');
            Route::get('sale-order/recorde/fetch', 'dataFetch');
            Route::get('load-sale-demands', 'LoadSaleDemands');
            Route::get('load-data', 'LoadData');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            // Route::get('load-product', 'loadProduct');
            Route::get('load-customer-careof', 'loadCustomerCareOf');
        });

        // DELIVERY CHALLAN DIRECT
        Route::middleware('checkSubMenu2Access:DIRECT DELIVERY CHALLAN')->controller(DirectDeliveryChallanController::class)->prefix('direct-delivery-challan')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('report', 'Report');
            Route::get('sale-order/recorde/fetch', 'dataFetch');
            Route::get('load-sale-demands', 'LoadSaleDemands');
            Route::get('load-data', 'LoadData');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            // Route::get('load-product', 'loadProduct');
            Route::get('load-customer-careof', 'loadCustomerCareOf');
        });

                        // SALES VOUCHER
        Route::middleware('checkSubMenu2Access:SALES INVOICE 1')->controller(DirectDCSalesController::class)->prefix('direct-dc-sales-voucher/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('{id}', 'edit');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            // Route::get('report', 'report');
        });

        // CUSTOMER DELIVERY CHALLAN VOUCHER
        Route::middleware('checkSubMenu2Access:CUSTOMER DELIVERY CHALLAN')->controller(CustomerDeliveryChallanController::class)->prefix('customer-delivery-challan/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('report', 'report');
            Route::get('getCustomerProduct', 'getCustomerProduct');
        });


        // SALES VOUCHER
        Route::middleware('checkSubMenu2Access:SALES INVOICE')->controller(SalesController::class)->prefix('sales-voucher/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('{id}', 'edit');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            // Route::get('report', 'report');
        });

        // SALES RETURN VOUCHER
        Route::middleware('checkSubMenu2Access:SALE RETURN')->controller(SalesReturnController::class)->prefix('sales-return/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/invoice', 'LoadInvoices');
            Route::get('load/pono', 'LoadPONO');
            Route::get('load/record', 'editData');
            Route::get('load/record1', 'editData1');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            Route::get('{id}', 'edit');
            });



                    // SALES RETURN VOUCHER
        Route::middleware('checkSubMenu2Access:SALESTAX RETURN')->controller(SalesTaxReturnController::class)->prefix('salestax-return/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/invoice', 'LoadInvoices');
            Route::get('load/pono', 'LoadPONO');
            Route::get('load/record', 'editData');
            Route::get('load/record1', 'editData1');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            Route::get('{id}', 'edit');
            });


                                // SALES RETURN VOUCHER
        Route::middleware('checkSubMenu2Access:PURCHASETAX RETURN')->controller(PurchaseTaxReturnController::class)->prefix('purchasetax-return')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/invoice', 'LoadInvoices');
            Route::get('load/pono', 'LoadPONO');
            Route::get('load/record', 'editData');
            Route::get('load/record1', 'editData1');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            Route::get('{id}', 'edit');
            });


        // SALESTAX-INVOICE VOUCHER
        Route::middleware('checkSubMenu2Access:SALESTAX INVOICE')->controller(SalesTaxInvoiceController::class)->prefix('salestax-invoice/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('{id}', 'edit');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            // Route::get('report', 'report');
        });

          // DIRECT SALESTAX-INVOICE VOUCHER
          Route::middleware('checkSubMenu2Access:DIRECT SALESTAX INVOICE')->controller(DirectSalesTaxInvoiceController::class)->prefix('direct-saletax-invoice/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('print/dc-voucher', 'PrintDCVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('{id}', 'edit');
            Route::get('warehouse/voucherno', 'Warehouse_voucherNo');
            // Route::get('report', 'report');
        });

        Route::middleware('checkSubMenu2Access:DIRECT PURCHASETAX VOUCHER')->controller(DirectPurchaseTaxVoucherController::class)->prefix('direct-purchasetax-voucher/')->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::post('delete-voucher', 'DeleteVoucher');
            Route::get('print/voucher', 'PrintVoucher');
            Route::get('load/record', 'editData');
            Route::get('load/next/record', 'LoadNextData');
            Route::get('load/previous/record', 'LoadPreviousData');
            Route::get('getdcRecord', 'getdcRecord');
            Route::get('{id}', 'edit');
            // Route::get('report', 'report');
        });
    });

    // CUSTOMER REPORTS
    Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['CUSTOMER REPORTS', 6]], function () {
    });

    // SALES REPORT
    // Route::group(['middleware' => 'checkSubMenu1Access', 'subMenu' => ['SALES REPORT', 6]], function () {

    // });
});




// RIGHTS LEVEL 1,2,3
// Route::resource('right-level1', RightsLevel1Controller::class)->middleware('test:abc,xyz');
Route::resource('right-level1', RightsLevel1Controller::class);
Route::get('right-level1/print1/report', [RightsLevel1Controller::class, 'rights_level1']);
Route::get('right-level1/destroy/{id}', [RightsLevel1Controller::class, 'destroy']);

Route::resource('right-level2', RightsLevel2Controller::class);

Route::get('right-level2/destroy/{id}', [RightsLevel2Controller::class, 'destroy']);
Route::get('right-level2/print', [RightsLevel2Controller::class, 'show']);
Route::resource('right-level3', RightsLevel3Controller::class);
Route::get('right-level3/destroy/{id}', [RightsLevel3Controller::class, 'destroy']);
Route::get('right-level3/des/report', [RightsLevel3Controller::class, 'rights_level3']);
// VOUCHERS CRUD
Route::resource('voucher-name', VoucherNamesController::class);
Route::get('voucher-name/vouchers/{id}', [VoucherNamesController::class, 'vnames']);
//Customer Products  CRUD
Route::resource('customer-products', CustomerProductController::class);

Route::post('customer-products-import', [CustomerProductController::class, 'ImportProducts']);

Route::get('customer-products/destroy/{id}', [CustomerProductController::class, 'destroy']);
Route::get('customer-products/detail', [CustomerProductController::class, 'show']);

//POS
Route::get('pointofsale/create', [POSController::class, 'create']);
Route::get('pointofsale', [POSController::class, 'index']);
Route::post('pointofsale/store', [POSController::class, 'store']);
Route::get('pointofsale/categoryrecord', [POSController::class, 'categoryrecord']);
Route::get('pos/search-product', [POSController::class, 'SearchProduct']);
Route::get('pos/fetchsearchproduct', [POSController::class, 'fetchsearchProduct']);
Route::post('find-sales', [POSController::class, 'findsales']);

Route::get('pointofsale/print/{id}', [POSController::class, 'print']);
Route::get('pointofsale/{id}/destroy', [POSController::class, 'destroy']);
}); 
