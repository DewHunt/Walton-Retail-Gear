<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\DealerInformationController;
use App\Http\Controllers\Api\DelarDistributionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\RsmController;
use App\Http\Controllers\Api\DistributorController;
use App\Http\Controllers\ProductMasterPriceController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\RetailerController;
use App\Http\Controllers\Api\BrandPromoterController;
use App\Http\Controllers\Api\PaginationController;
use App\Http\Controllers\Api\BrandPromoterIncentiveController;
//use App\Http\Controllers\Api\RetailerIncentiveController;
//use App\Http\Controllers\Api\RetailSpecialAwardController;
use App\Http\Controllers\Api\IncentiveController;
use App\Http\Controllers\Api\SpecialAwardController;
use App\Http\Controllers\Api\OutSourceApiController;
use App\Http\Controllers\Api\ImeiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PromoOfferController;
use App\Http\Controllers\AuthorityMessageController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Api\PreBookingController;
use App\Http\Controllers\Api\PushNotificationController;
use App\Http\Controllers\CronjobController;


Auth::routes(['register' => false]);
Route::get('/', function () {
    return view('auth.login');
});
//,'MenuPermission'
Route::group(['middleware' =>['auth','checkstatus','MenuPermission']], function() {

	//Route::resource('dealerinfo','Api\DealerInformationController');
	Route::get('dealer.index','Api\DealerInformationController@index')->name('dealer.index');
	Route::post('dealer.add','Api\DealerInformationController@store')->name('dealer.add');
	Route::get('dealer.edit/{id}','Api\DealerInformationController@edit')->name('dealer.edit');
	Route::post('dealer.update','Api\DealerInformationController@update')->name('dealer.update');
	Route::get('dealer.status/{id}','Api\DealerInformationController@ChangeStatus')->name('dealer.status');
	Route::get('CheckDealerFromApi/{DealerCode}','Api\DealerInformationController@CheckDealerFromApi');
	Route::get('AddToDealerFormApi','Api\DealerInformationController@AddToDealerFormApi')->name('AddToDealerFormApi');

	//Route::resource('imei', 'Api\ImeiController');
	Route::get('imei.check', 'Api\ImeiController@index')->name('imei.check');

	//Route::resource('product','Api\ProductController');
	Route::get('product.index','Api\ProductController@index')->name('product.index');
	Route::post('product.add','Api\ProductController@store')->name('product.add');
	Route::get('product.edit/{id}','Api\ProductController@edit')->name('product.edit');
	Route::post('product.update','Api\ProductController@update')->name('product.update');
	Route::get('product.status/{id}','Api\ProductController@ChangeStatus')->name('product.status');
	Route::get('productStockEdit/{pid}','Api\ProductController@productStockEdit')->name('productStockEdit');
	Route::post('saveProductStockMaintain','Api\ProductController@saveProductStockMaintain')->name('saveProductStockMaintain');
	Route::get('apiproduct/{id}','Api\ProductController@CheckProduct');
	Route::get('apilistaddproduct','Api\ProductController@ApiListProductInsert')->name('apilistaddproduct.ApiListProductInsert');


	//Route::resource('employee','Api\EmployeeController');
	Route::get('employee.index','Api\EmployeeController@index')->name('employee.index');
	Route::post('employee.add','Api\EmployeeController@store')->name('employee.add');
	Route::get('employee.edit/{id}','Api\EmployeeController@edit')->name('employee.edit');
	Route::post('employee.update','Api\EmployeeController@update')->name('employee.update');
	Route::get('employee.status/{id}','Api\EmployeeController@ChangeStatus')->name('employee.status');
	Route::get('employee.searchApi/{id}','Api\EmployeeController@CheckEmployee')->name('employee.search_api');


	//Route::resource('zone','Api\ZoneController');
	Route::get('zone.index','Api\ZoneController@index')->name('zone.index');
	Route::post('zone.add','Api\ZoneController@store')->name('zone.add');
	Route::get('zone.edit/{id}','Api\ZoneController@edit')->name('zone.edit');
	Route::post('zone.update','Api\ZoneController@update')->name('zone.update');
	Route::get('zone.status/{id}','Api\ZoneController@ChangeStatus')->name('zone.status');
	Route::get('zone.add_by_api/{id}','Api\ZoneController@CheckZone')->name('zone.add_by_api');
	Route::get('apilistaddzone','Api\ZoneController@ApiListZoneInsert')->name('apilistaddzone.ApiListZoneInsert');


	//Route::resource('retailer','Api\RetailerController');
	Route::get('retailer.index','Api\RetailerController@index')->name('retailer.index');
	Route::post('retailer.add','Api\RetailerController@store')->name('retailer.add');
	Route::get('retailer.edit/{id}','Api\RetailerController@edit')->name('retailer.edit');
	Route::post('retailer.update','Api\RetailerController@update')->name('retailer.update');
	Route::get('retailer.status/{id}','Api\RetailerController@ChangeStatus')->name('retailer.status');
	Route::get('retailer.search_by_api/{mobile}','Api\RetailerController@CheckRetailer')->name('retailer.search_by_api');
	Route::get('apilistaddretailer','Api\RetailerController@ApiListRetailerInsert')->name('apilistaddretailer.ApiListRetailerInsert');
	Route::get('retailer.open_working_time_modal/{id}','Api\RetailerController@retailerShopTimeEdit')->name('retailer.open_working_time_modal');
    Route::post('retailer.save_working_time','Api\RetailerController@saveShopWorkingTime')->name('retailer.save_working_time');


    //Route::resource('bpromoter','Api\BrandPromoterController');
    Route::get('bpromoter.index','Api\BrandPromoterController@index')->name('bpromoter.index');
    Route::post('bpromoter.add','Api\BrandPromoterController@store')->name('bpromoter.add');
	Route::get('bpromoter.edit/{id}','Api\BrandPromoterController@edit')->name('bpromoter.edit');
	Route::post('bpromoter.update','Api\BrandPromoterController@update')->name('bpromoter.update');
	Route::get('bpromoter.status/{id}','Api\BrandPromoterController@ChangeStatus')->name('bpromoter.status');
	Route::get('bpromoter.search_by_api/{phone}','Api\BrandPromoterController@CheckBPromoterFromApi')->name('bpromoter.search_by_api');
	Route::get('AddBPromoterFromApi','Api\BrandPromoterController@AddBPromoterFromApi')->name('AddBPromoterFromApi.AddBPromoterFromApi');
	Route::get('bpromoter.focus_model_to_bp','Api\BrandPromoterController@focus_model_to_bp')->name('bpromoter.focus_model_to_bp');
	Route::post('bpromoter.focus_model_to_bp_save','Api\BrandPromoterController@focus_model_to_bp_save')->name('bpromoter.focus_model_to_bp_save');
	Route::get('bpromoter.focus_model_to_bp_by_cat/{catId}','Api\BrandPromoterController@focus_model_to_bp_by_cat')->name('bpromoter.focus_model_to_bp_by_cat');

	//Route::resource('UpdateUser','UserController');
	Route::get('user.index','UserController@GetUserList')->name('user.index');
	Route::post('user.add','UserController@CreateUser')->name('user.add');
	Route::get('user.edit/{id}','UserController@edit')->name('user.edit');
	Route::post('user.update','UserController@update')->name('user.update');
	Route::get('user.status/{id}','UserController@ChangeStatus')->name('user.status');
	Route::get('user.menu_permission_list/{id}','UserController@menuPermission')->name('user.menu_permission_list');
    Route::post('user.menu_permission_save','UserController@userMenuPermissionSave')->name('user.menu_permission_save');


    //Route::get('incentive','Api\IncentiveController@index');
    Route::get('incentive.index','Api\IncentiveController@index')->name('incentive.index');
    Route::get('incentive.addForm/{groupId}','Api\IncentiveController@IncentiveCreate')->name('incentive.addForm');
    Route::post('incentive.add','Api\IncentiveController@IncentiveStore')->name('incentive.add');
    Route::get('incentive.edit/{id}','Api\IncentiveController@IncentiveEdit')->name('incentive.edit');
    Route::put('incentive.update/{id}','Api\IncentiveController@IncentiveUpdate')->name('incentive.update');
    Route::get('incentive.list/{id}','Api\IncentiveController@IncentiveList')->name('incentive.list');
    Route::get('incentive.destroy/{id}','Api\IncentiveController@IncentiveDestroy')->name('incentive.destroy');
    Route::get('incentive.status/{id}','Api\IncentiveController@IncentiveStatus')->name('incentive.status');


    Route::get('award.addForm/{groupId}','Api\SpecialAwardController@SpecialAwardCreate')->name('award.addForm');
    Route::post('award.add','Api\SpecialAwardController@SpecialAwardStore')->name('award.add');
    Route::get('award.edit/{id}','Api\SpecialAwardController@SpecialAwardEdit')->name('award.edit');
    Route::post('award.update','Api\SpecialAwardController@specialAwardModify')->name('award.update');
    Route::get('award.list/{id}','Api\SpecialAwardController@SpecialAwardList')->name('award.list');
    Route::get('award.destroy/{id}','Api\SpecialAwardController@SpecialAwardDestroy')->name('award.destroy');
    Route::get('award.status/{id}','Api\SpecialAwardController@SpecialAwardStatus')->name('award.status');


    Route::get('report.dashboard','Api\ReportController@report_dashboard')->name('report.dashboard');
    Route::get('report.bp-sales','Api\ReportController@bpSalesReportForm')->name('report.bp-sales');
    Route::get('report.sales-invoice','Api\ReportController@salesReportForm')->name('report.sales-invoice');
	Route::get('report.incentive','Api\ReportController@incentiveReportFrom')->name('report.incentive');
	Route::get('report.bp-attendance','Api\ReportController@bpAttendanceForm')->name('report.bp-attendance');
	Route::get('report.bp-leave','Api\ReportController@bpLeaveReportForm')->name('report.bp-leave');
	Route::get('report.imei-sold','Api\ReportController@imeSoldReport')->name('report.imei-sold');
	Route::get('report.sales-product','Api\ReportController@productSalesReport')->name('report.sales-product');
	Route::get('report.pre-booking','Api\ReportController@getPreBookingOrderList')->name('report.pre-booking');
	Route::get('report.pending-bounce-order','Api\ReportController@getPendingOrderList')->name('report.pending-bounce-order');
	Route::post('report.pending-search-report','Api\ReportController@searchPendingOrderList')->name('report.pending-search-report');


	Route::post('dateRangesalesReport','Api\ReportController@dateRangesalesReport');
	Route::get('SaleOrderDetails/{sale_id}','Api\ReportController@SaleOrderDetails');
	Route::post('incentive_report','Api\ReportController@incentiveReport');
	Route::post('bp_attendance_report','Api\ReportController@bpAttendanceReport');
	Route::get('bpAttendanceDetails/{bpId}/{attendanceDate}','Api\ReportController@bpAttendanceDetails');
	Route::post('bp_leave_report','Api\ReportController@bpLeaveReport');
	Route::get('bp_search','Api\ReportController@bpSearch');
	Route::get('retailer_search','Api\ReportController@retailerSearch');
	Route::get('SaleIncentiveDetails','Api\ReportController@SaleIncentiveDetails');
    Route::get('imeProductDetails/{id}','Api\ReportController@imeProductDetails');
    Route::get('OrderDetailsView/{sale_id}','Api\ReportController@OrderDetailsView');


    //Route::resource('banner', 'Api\BannerController');
    Route::get('banner.index', 'Api\BannerController@index')->name('banner.index');
    Route::post('banner.add','Api\BannerController@store')->name('banner.add');
	Route::get('banner.edit/{id}','Api\BannerController@edit')->name('banner.edit');
	Route::post('banner.update','Api\BannerController@update')->name('banner.update');
	Route::get('banner.status/{id}','Api\BannerController@ChangeStatus')->name('banner.status');
	Route::get('banner.destroy/{id}','Api\BannerController@destroy')->name('banner.destroy');


	//Route::resource('promoOffer','Api\PromoOfferController');
	Route::get('promoOffer.index','Api\PromoOfferController@index')->name('promoOffer.index');
	Route::post('promoOffer.add','Api\PromoOfferController@addOffer')->name('promoOffer.add');
	Route::get('promoOffer.edit/{id}','Api\PromoOfferController@editOffer')->name('promoOffer.edit');
	//Route::put('promoOffer.update/{id}','Api\PromoOfferController@updateOffer')->name('promoOffer.update');
	Route::post('promoOffer.update','Api\PromoOfferController@updateOffer')->name('promoOffer.update');
	Route::get('promoOffer.status/{id}','Api\PromoOfferController@ChangeStatus')->name('promoOffer.status');
    Route::get('promoOffer.destroy/{id}','Api\PromoOfferController@destroy')->name('promoOffer.destroy');

	//Route::resource('message','Api\AuthorityMessageController');
	Route::get('message.index','Api\AuthorityMessageController@index')->name('message.index');
	Route::post('message.add','Api\AuthorityMessageController@store')->name('message.add');
	Route::get('message.edit/{id}','Api\AuthorityMessageController@edit')->name('message.edit');
	Route::put('message.update/{id}','Api\AuthorityMessageController@update')->name('message.update');
	Route::post('message.reply','Api\ReportController@reply_message')->name('message.reply');
	Route::get('message.details/{replyId}/{messageId}','Api\ReportController@MessageDetails')->name('message.details');

	
	Route::get('retailer.stockForm','Api\ReportController@getRetailerStock')->name('retailer.stockForm');
	Route::post('retailer.search-stock','Api\ReportController@searchRetailerStock')->name('retailer.search-stock');

	Route::get('imei.disputeList','Api\ReportController@getIMEIdisputeNumber')->name('imei.disputeList');
	Route::get('imei.dispute-edit/{id}','Api\ReportController@editIMEIdisputeNumber')->name('imei.dispute-edit');
    Route::post('imei.dispute-reply','Api\ReportController@updateIMEIdisputeNumber')->name('imei.dispute-reply');

	

    //Route::resource('prebooking', 'Api\PreBookingController');
    Route::get('prebooking.index','Api\PreBookingController@index')->name('prebooking.index');
    Route::post('prebooking.add','Api\PreBookingController@store')->name('prebooking.add');
	Route::get('prebooking.edit/{id}','Api\PreBookingController@edit')->name('prebooking.edit');
	Route::put('prebooking.update','Api\PreBookingController@update')->name('prebooking.update');
    Route::get('prebooking.status/{id}','Api\PreBookingController@ChangeStatus')->name('prebooking.status');
    Route::get('prebooking.expire','Api\PreBookingController@expirePreBooking')->name('prebooking.expire');



    //Route::resource('pushNotification', 'Api\PushNotificationController');
    Route::get('pushNotification.index','Api\PushNotificationController@index')->name('pushNotification.index');
    Route::post('pushNotification.add','Api\PushNotificationController@store')->name('pushNotification.add');
    Route::get('pushNotification.edit/{id}','Api\PushNotificationController@edit')->name('pushNotification.edit');
    Route::post('pushNotification.update','Api\PushNotificationController@update')->name('pushNotification.update');
    Route::get('pushNotification.status/{id}','Api\PushNotificationController@ChangeStatus')->name('pushNotification.status');
    
	Route::get('employee.stock-check','Api\ReportController@employeeStockCheck')->name('employee.stock-check');
	Route::get('user.loginLog','UserController@getUserLog')->name('user.loginLog');


	//Other's
	Route::get('editLeave/{id}','Api\ReportController@editLeave');
    Route::put('updateLeave/{id}','Api\ReportController@updateLeave')->name('updateLeave.updateLeave');
    Route::get('attendanceDetailsView/{id}','Api\ReportController@attendanceDetailsView');
    Route::get('incentiveDetailsView/{bp_id}/{retail_id}','Api\ReportController@incentiveDetailsView');
    Route::get('checkImei/{id}','Api\ImeiController@checkImei');
    Route::post('modelSalesReport','Api\ReportController@modelSalesReport');
    Route::get('productSalesReportDetails/{model}','Api\ReportController@productSalesReportDetails');

    //Menu Management
    Route::get('menu', 'MenuController@index')->name('menu');
    Route::post('menu.save', 'MenuController@save')->name('menu.save');
    Route::get('editMenu/{id}','MenuController@edit')->name('editMenu');
    Route::get('changeStatus/{id}','MenuController@changeStatus')->name('changeStatus');
    Route::post('updateMenu','MenuController@update')->name('updateMenu');
    
	/*Route::fallback(function () {
		return view("404");
	});*/
	
	Route::post('pendingOrderStatusUpdate','Api\ReportController@pendingOrderStatusUpdate')->name('pendingOrderStatusUpdate');
});
//Testing CronJob
Route::get('employeeStockNotifyEmail','CronjobController@index');
Route::post('OutSourceApi/api/{methodName}','Api\OutSourceApiController@getImeList');
Route::post('storeToken', 'Api\PushNotificationController@storeToken');
Route::post('sendWebNotification','Api\PushNotificationController@sendWebNotification');
Route::resource('distribution','Api\DelarDistributionController');
Route::resource('rsm', 'Api\RsmController');
Route::get('color',[ColorController::class,'index']);
Route::get('sellerProductSalesReport/{id}','Api\ReportController@sellerProductSalesReport');
Route::get('salesReturn/{orderId}','Api\ReportController@salesReturn');
Route::get('pending-order','Api\ReportController@getAllPendingOrder');
Route::get('PendingOrderStatus/{id}','Api\ReportController@PendingOrderStatus');
Route::get('pending-message','Api\ReportController@getAllPendingMessage');
Route::get('pending-leave','Api\ReportController@getAllPendingLeave');
Route::get('searchRetailer','Api\SearchController@SearchRetailer');
Route::get('preOrderReportDetails/{model}','Api\ReportController@preOrderReportDetails');
Route::post('preBookingReport','Api\ReportController@preBookingReport');

Route::get('getUserProfile/{userId}','UserController@getUserProfile')->name('getUserProfile.getUserProfile');
Route::post('userProfileUpdate','UserController@userProfileUpdate')->name('userProfileUpdate.userProfileUpdate');
Route::get('getUserLog','UserController@getUserLog')->name('getUserLog.getUserLog');
Route::get('getEmployeeInfo/{id}','Api\EmployeeController@getEmployeeInfo')->name('getEmployeeInfo');
Route::get('UserStatus/{id}','UserController@ChangeStatus');

Route::post('bpDateRangesalesReport','Api\ReportController@bpDateRangesalesReport')->name('bpDateRangesalesReport');
Route::get('BpOrderDetailsView/{bpId}','Api\ReportController@BpOrderDetailsView');
Route::post('get_stock','Api\ReportController@getStockResult')->name('get_stock');

Route::get('distributor', [DistributorController::class, 'index']);
Route::get('distributor-add', [DistributorController::class, 'store']);
Route::get('employee-activation/{activationkey}','Api\EmployeeController@create');
Route::post('employee','Api\EmployeeController@account_update')->name('employee.account_update');
Route::get('/status', 'UserController@show');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('change/lang', [LocalizationController::class, 'lang_change'])->name('LangChange');
Route::get('user',function(){
	return view('admin.user_list');
});

//Route::get('apiretailer/{id}/{mobile}','Api\RetailerController@CheckRetailer');
//Route::post('UpdateUser','UserController@update')->name('UpdateUser.update');
//Route::post('IncentiveList','Api\IncentiveController@IncentiveList');
//Route::post('SpecialAwardList','Api\SpecialAwardController@SpecialAwardList');
//Route::get('MessageDetails/{messageId}','Api\ReportController@MessageDetails');
//Route::get('SendPushNotification/{id}','Api\OutSourceApiController@SendPushNotification');
/*
Route::group(['middleware' => ['checkstatus']], function () {
	Route::get('/', function () {
	    return view('auth.login');
	});
});
*/
Route::get('/clear',function(){
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	Artisan::call('route:clear');
	return view("cache_clear");
});

//Testing Route Start
Route::resource('pagination', 'Api\PaginationController');
Route::get('menu/index','MenuController@index');
Route::post('menu/update-order','MenuController@updateOrder');
Route::get('mailform','MailController@index');
Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('sendattachmentemail','MailController@attachment_email');
Route::post('MailSendFrom','MailController@mail_attachment_email');
//Testing Route End
Route::fallback(function () {
	return view("404");
});

