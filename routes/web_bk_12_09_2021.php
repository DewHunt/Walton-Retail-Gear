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




Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

// Route::get('/api/{methodName}/{term}',function(){
// 	$where = array('id'=>1);
// 	dd(getApiTableWhere('users',$where));
// });

//////////sa///Route::get('OutSourceApi/api/{methodName}/{term}','Api\OutSourceApiController@GetByInfo');
//Route::get('OutSourceApi/api/GetByInfoImeNumber/{imeNumber}','Api\OutSourceApiController@GetByInfoImeNumber');

Route::post('OutSourceApi/api/{methodName}','Api\OutSourceApiController@getImeList');

Route::group(['middleware' =>'auth'], function() {
	Route::resource('dealerinfo','Api\DealerInformationController');
	//Route::get('dealerStatus/{id}','Api\DealerInformationController@ChangeStatus');
	Route::get('CheckDealerFromApi/{DealerCode}','Api\DealerInformationController@CheckDealerFromApi');
	Route::get('DealerStatus/{id}','Api\DealerInformationController@ChangeStatus');
	Route::get('AddToDealerFormApi','Api\DealerInformationController@AddToDealerFormApi')->name('AddToDealerFormApi.AddToDealerFormApi');
	Route::resource('distribution','Api\DelarDistributionController');
	Route::resource('rsm', 'Api\RsmController');
	Route::resource('product','Api\ProductController');
	Route::get('apiproduct/{id}','Api\ProductController@CheckProduct');
	Route::get('productStatus/{id}','Api\ProductController@ChangeStatus');
	Route::get('apilistaddproduct','Api\ProductController@ApiListProductInsert')->name('apilistaddproduct.ApiListProductInsert');
	Route::get('color',[ColorController::class,'index']);
	Route::resource('employee','Api\EmployeeController');
	Route::get('apiemployee/{id}','Api\EmployeeController@CheckEmployee');
	Route::get('employeeStatus/{id}','Api\EmployeeController@ChangeStatus');
	Route::post('addEmployee','Api\EmployeeController@store')->name('addEmployee.store');
	Route::resource('zone','Api\ZoneController');
	Route::get('apizone/{id}','Api\ZoneController@CheckZone');
	Route::get('zoneStatus/{id}','Api\ZoneController@ChangeStatus');
	Route::get('apilistaddzone','Api\ZoneController@ApiListZoneInsert')->name('apilistaddzone.ApiListZoneInsert');
	Route::resource('retailer','Api\RetailerController');
	//Route::get('apiretailer/{id}/{mobile}','Api\RetailerController@CheckRetailer');
	Route::get('apiretailer/{mobile}','Api\RetailerController@CheckRetailer');
	Route::get('retailerStatus/{id}','Api\RetailerController@ChangeStatus');
	Route::get('apilistaddretailer','Api\RetailerController@ApiListRetailerInsert')->name('apilistaddretailer.ApiListRetailerInsert');
	Route::resource('bpromoter','Api\BrandPromoterController');
	Route::get('CheckBPromoterFromApi/{phone}','Api\BrandPromoterController@CheckBPromoterFromApi');
	Route::get('BPromoterStatus/{id}','Api\BrandPromoterController@ChangeStatus');
	Route::get('AddBPromoterFromApi','Api\BrandPromoterController@AddBPromoterFromApi')->name('AddBPromoterFromApi.AddBPromoterFromApi');
	Route::get('getUser','UserController@GetUserList')->name('getUser.GetUserList');
	Route::post('AddToUser','UserController@CreateUser')->name('AddToUser.CreateUser');
	Route::get('ShowUser/{id}','UserController@edit')->name('ShowUser.edit');
	//Route::post('UpdateUser','UserController@update')->name('UpdateUser.update');
	Route::resource('UpdateUser','UserController');
	Route::get('incentive','Api\IncentiveController@index');
	Route::get('IncentiveCreate/{groupId}','Api\IncentiveController@IncentiveCreate');
	//Route::post('IncentiveList','Api\IncentiveController@IncentiveList');
	Route::get('IncentiveEdit/{id}','Api\IncentiveController@IncentiveEdit');

	Route::get('IncentiveDestroy/{id}','Api\IncentiveController@IncentiveDestroy');
	Route::get('IncentiveList/{id}','Api\IncentiveController@IncentiveList');

	Route::post('IncentiveStore','Api\IncentiveController@IncentiveStore');
	Route::put('IncentiveUpdate/{id}','Api\IncentiveController@IncentiveUpdate');
	Route::get('IncentiveStatus/{id}','Api\IncentiveController@IncentiveStatus');
	Route::get('SpecialAwardCreate/{groupId}','Api\SpecialAwardController@SpecialAwardCreate');
	//Route::post('SpecialAwardList','Api\SpecialAwardController@SpecialAwardList');
	Route::get('SpecialAwardEdit/{id}','Api\SpecialAwardController@SpecialAwardEdit');
	Route::post('SpecialAwardStore','Api\SpecialAwardController@SpecialAwardStore');
	Route::post('specialAwardModify','Api\SpecialAwardController@specialAwardModify');
	Route::get('SpecialAwardStatus/{id}','Api\SpecialAwardController@SpecialAwardStatus');

	Route::get('SpecialAwardList/{id}','Api\SpecialAwardController@SpecialAwardList');
	Route::get('SpecialAwardDestroy/{id}','Api\SpecialAwardController@SpecialAwardDestroy');
    Route::get('report','Api\ReportController@report_dashboard');
	Route::get('salesReportForm','Api\ReportController@salesReportForm');
	Route::post('dateRangesalesReport','Api\ReportController@dateRangesalesReport');
	Route::get('SaleOrderDetails/{sale_id}','Api\ReportController@SaleOrderDetails');
	Route::get('incentiveReportFrom','Api\ReportController@incentiveReportFrom');
	Route::post('incentive_report','Api\ReportController@incentiveReport');
	Route::get('bpAttendanceForm','Api\ReportController@bpAttendanceForm');
	Route::post('bp_attendance_report','Api\ReportController@bpAttendanceReport');
	Route::get('bpAttendanceDetails/{bpId}/{attendanceDate}','Api\ReportController@bpAttendanceDetails');
	Route::get('bpLeaveReportForm','Api\ReportController@bpLeaveReportForm');
	Route::post('bp_leave_report','Api\ReportController@bpLeaveReport');
	Route::get('bp_search','Api\ReportController@bpSearch');
	Route::get('retailer_search','Api\ReportController@retailerSearch');
	Route::get('SaleIncentiveDetails','Api\ReportController@SaleIncentiveDetails');
	Route::get('imeSoldReport','Api\ReportController@imeSoldReport');
    Route::get('imeProductDetails/{id}','Api\ReportController@imeProductDetails');
    Route::get('OrderDetailsView/{sale_id}','Api\ReportController@OrderDetailsView');
    Route::get('promoOfferForm','Api\PromoOfferController@index');
    Route::post('addOffer','Api\PromoOfferController@addOffer')->name('addOffer.addOffer');
    Route::get('offerStatus/{id}','Api\PromoOfferController@ChangeStatus');
    Route::get('editOffer/{id}','Api\PromoOfferController@editOffer');
    Route::put('updateOffer/{id}','Api\PromoOfferController@updateOffer')->name('updateOffer.updateOffer');
    Route::resource('promoOffer','Api\PromoOfferController');

    Route::resource('message','Api\AuthorityMessageController');
    Route::get('edit_message/{id}','Api\AuthorityMessageController@edit');
    Route::put('update_message/{id}','Api\AuthorityMessageController@update');
    Route::get('MessageDetails/{replyId}/{messageId}','Api\ReportController@MessageDetails');
    //Route::get('MessageDetails/{messageId}','Api\ReportController@MessageDetails');
    Route::get('editLeave/{id}','Api\ReportController@editLeave');
    Route::put('updateLeave/{id}','Api\ReportController@updateLeave')->name('updateLeave.updateLeave');
    Route::get('attendanceDetailsView/{id}','Api\ReportController@attendanceDetailsView');
    Route::get('incentiveDetailsView/{bp_id}/{retail_id}','Api\ReportController@incentiveDetailsView');
    Route::resource('imei', 'Api\ImeiController');
    Route::get('checkImei/{id}','Api\ImeiController@checkImei');
    Route::post('reply_message','Api\ReportController@reply_message');
    Route::resource('banner', 'Api\BannerController');
    Route::get('bannerStatus/{id}','Api\BannerController@ChangeStatus');
    Route::get('productSalesReport','Api\ReportController@productSalesReport');
    Route::post('modelSalesReport','Api\ReportController@modelSalesReport');
    Route::get('productSalesReportDetails/{modelSellerId}','Api\ReportController@productSalesReportDetails');
    Route::get('sellerProductSalesReport/{id}','Api\ReportController@sellerProductSalesReport');
    
    
	Route::get('retailer_stock','Api\ReportController@getRetailerStock');
	Route::post('retailer_stock','Api\ReportController@searchRetailerStock');
	Route::get('salesReturn/{orderId}','Api\ReportController@salesReturn');
	Route::get('imeidisputeList','Api\ReportController@getIMEIdisputeNumber');
    Route::get('pending-order','Api\ReportController@getAllPendingOrder');
    Route::get('PendingOrderStatus/{id}','Api\ReportController@PendingOrderStatus');
    Route::get('pending-message','Api\ReportController@getAllPendingMessage');
    Route::get('pending-leave','Api\ReportController@getAllPendingLeave');
    Route::get('searchRetailer','Api\SearchController@SearchRetailer');
    
    Route::resource('prebooking', 'Api\PreBookingController');
    Route::get('prebookingStatus/{id}','Api\PreBookingController@ChangeStatus');
    Route::get('expirePreBooking','Api\PreBookingController@expirePreBooking');
    Route::get('getPreBookingOrderList','Api\ReportController@getPreBookingOrderList');
    Route::get('preOrderReportDetails/{model}','Api\ReportController@preOrderReportDetails');
    Route::post('preBookingReport','Api\ReportController@preBookingReport');
    Route::resource('pushNotification', 'Api\PushNotificationController');
    Route::get('pushNotificationStatus/{id}','Api\PushNotificationController@ChangeStatus');
    //Route::get('SendPushNotification/{id}','Api\OutSourceApiController@SendPushNotification');
    Route::post('storeToken', 'Api\PushNotificationController@storeToken');
	Route::post('sendWebNotification','Api\PushNotificationController@sendWebNotification');
	
	Route::get('getUserProfile/{userId}','UserController@getUserProfile')->name('getUserProfile.getUserProfile');
	Route::post('userProfileUpdate','UserController@userProfileUpdate')->name('userProfileUpdate.userProfileUpdate');
    Route::get('getUserLog','UserController@getUserLog')->name('getUserLog.getUserLog');
    Route::get('imeiDisputeNumber/{id}','Api\ReportController@editIMEIdisputeNumber');
    Route::post('imeiDisputeNumberUpdate','Api\ReportController@updateIMEIdisputeNumber')->name('imeiDisputeNumberUpdate');
    Route::get('getEmployeeInfo/{id}','Api\EmployeeController@getEmployeeInfo')->name('getEmployeeInfo');
    Route::get('UserStatus/{id}','UserController@ChangeStatus');
    Route::get('getPendingOrderList','Api\ReportController@getPendingOrderList')->name('getPendingOrderList');
    Route::post('pendingOrderStatusUpdate','Api\ReportController@pendingOrderStatusUpdate')->name('pendingOrderStatusUpdate');
    Route::get('bpSalesReportForm','Api\ReportController@bpSalesReportForm')->name('bpSalesReportForm');
    Route::post('bpDateRangesalesReport','Api\ReportController@bpDateRangesalesReport')->name('bpDateRangesalesReport');
    Route::get('BpOrderDetailsView/{bpId}','Api\ReportController@BpOrderDetailsView');
    
    Route::get('retailerShopTimeEdit/{id}','Api\RetailerController@retailerShopTimeEdit')->name('retailerShopTimeEdit');
    Route::post('saveShopWorkingTime','Api\RetailerController@saveShopWorkingTime')->name('saveShopWorkingTime');
    
    Route::get('productStockEdit/{pid}','Api\ProductController@productStockEdit')->name('productStockEdit');
    Route::post('saveProductStockMaintain','Api\ProductController@saveProductStockMaintain')->name('saveProductStockMaintain');
    Route::post('get_stock','Api\ReportController@getStockResult')->name('get_stock');
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
    Route::get('distributor', [DistributorController::class, 'index']);
    Route::get('distributor-add', [DistributorController::class, 'store']);

	Route::fallback(function () {
		return view("404");
	});
});

Route::get('employee-activation/{activationkey}','Api\EmployeeController@create');
Route::post('employee','Api\EmployeeController@account_update')->name('employee.account_update');
Route::fallback(function () {
	return view("404");
});


Route::get('/clear',function(){
	Artisan::call('cache:clear');
	Artisan::call('config:clear');
	Artisan::call('config:cache');
	Artisan::call('view:clear');
	Artisan::call('route:clear');
	return view("cache_clear");
});

Route::get('/status', 'UserController@show');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('change/lang', [LocalizationController::class, 'lang_change'])->name('LangChange');

Route::get('user',function(){
	return view('admin.user_list');
});

