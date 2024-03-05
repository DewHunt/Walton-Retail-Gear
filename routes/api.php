<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JwtAuthController;
use App\Http\Controllers\Api\DelarDistributionController;
use App\Http\Controllers\Api\DealerInformationController;
use App\Http\Controllers\Api\RsmController;
use App\Http\Controllers\Api\DistributorController;
use App\Http\Controllers\Api\OutSourceApiController;

Route::group(['prefix' => 'auth','middleware' => ['cors']], function() {
    //Route::group(['middleware' => ['cors']], function() {
        Route::post('signup', [JwtAuthController::class, 'register']);
        Route::post('login', [JwtAuthController::class, 'login']);
        Route::get('user', [JwtAuthController::class, 'user']);
        Route::get('refresh', [JwtAuthController::class, 'refresh']);
        Route::post('signout', [JwtAuthController::class, 'signout']);
        Route::post('payload', [JwtAuthController::class,'payload']);
    //});
});



Route::group(['middleware' => ['jwt.verify','cors']], function() {
    Route::post('GetByInfoImeNumbers',[OutSourceApiController::class, 'getImeList']);
    Route::post('SalesProduct',[OutSourceApiController::class, 'SalesProduct']);
    Route::post('salesProduct',[OutSourceApiController::class, 'SalesProduct']);
    Route::get('salesReport',[OutSourceApiController::class, 'salesReport']);
    Route::get('singleSalesReport',[OutSourceApiController::class, 'singleSalesReport']);
    Route::get('salesIncentiveReport',[OutSourceApiController::class, 'salesIncentiveReport']);
    Route::get('GetByInfoImeNumber/{imeListArray}',[OutSourceApiController::class, 'GetByInfoImeNumber']);
    Route::get('incentiveList',[OutSourceApiController::class, 'incentiveList']);
    Route::post('bpAttendance',[OutSourceApiController::class, 'bpAttendance']);
    Route::get('bpAttendanceReport',[OutSourceApiController::class, 'bpAttendanceReport']);
    Route::get('getLeaveType',[OutSourceApiController::class, 'getLeaveType']);
    Route::post('bpApplyLeave',[OutSourceApiController::class, 'bpApplyLeave']);
    Route::get('bpLeaveReport',[OutSourceApiController::class, 'bpLeaveReport']);
    Route::get('getLeaveReason',[OutSourceApiController::class, 'getLeaveReason']);
    Route::get('getProductList',[OutSourceApiController::class, 'getProductList']);
    Route::get('verifyBpAttendance',[OutSourceApiController::class, 'verifyBpAttendance']);
    Route::get('promoOffer',[OutSourceApiController::class, 'getPromoOffer']);
    Route::post('messageStore',[OutSourceApiController::class, 'messageStore']);
    Route::post('replyMessage',[OutSourceApiController::class, 'replyMessage']);
    Route::get('getMessageList',[OutSourceApiController::class, 'getMessageList']);
    Route::post('userProfileUpdate',[OutSourceApiController::class, 'userProfileUpdate']);
    Route::get('getMessageListByUserId',[OutSourceApiController::class, 'getMessageListByUserId']);
    Route::get('getBannerList',[OutSourceApiController::class, 'getBannerList']);
    Route::get('ModelWaiseSalesReport',[OutSourceApiController::class, 'ModelWaiseSalesReport']);
    Route::get('getRetailerStock',[OutSourceApiController::class, 'getRetailerStock']);
    Route::get('getTopSellerList',[OutSourceApiController::class, 'getTopSellerList']);
    Route::post('postIMEIdisputeNumber',[OutSourceApiController::class, 'postIMEIdisputeNumber']);
    Route::get('getIMEIdisputeList',[OutSourceApiController::class, 'getIMEIdisputeList']);
    Route::get('generalAndtargetIncentiveReport',[OutSourceApiController::class, 'generalAndtargetIncentiveReport']);
    Route::get('getSalesTarget',[OutSourceApiController::class, 'getSalesTarget']);
    Route::post('checkUserByPhone',[OutSourceApiController::class, 'checkUserByPhone']);
    Route::post('userOtpVerify',[OutSourceApiController::class, 'userOtpVerify']);
    Route::post('userPasswordUpdate',[OutSourceApiController::class, 'userPasswordUpdate']);
    Route::get('incentiveStatement',[OutSourceApiController::class, 'incentiveStatement']);
    Route::post('offLineSalesStore',[OutSourceApiController::class, 'offLineSalesStore']);
    Route::get('getRetailerLiftingIncentive',[OutSourceApiController::class, 'getRetailerLiftingIncentive']);
    Route::get('GetPreBookingList',[OutSourceApiController::class, 'getPreBookingList']);
    Route::post('OrderPreBooking',[OutSourceApiController::class, 'OrderPreBooking']);
    Route::get('GetPreBookingOrderList',[OutSourceApiController::class, 'getPreBookingOrderList']);
    Route::post('deviceRegistraction',[OutSourceApiController::class, 'deviceRegistraction']);
    Route::get('getMonthlySalesPercentage',[OutSourceApiController::class, 'getMonthlySalesPercentage']);
    Route::get('getPushNotificationList',[OutSourceApiController::class, 'getPushNotificationList']);
});



Route::get('dealer-distribution', [DelarDistributionController::class, 'ApiStore']);
Route::get('dealer', [DealerInformationController::class, 'index']);
Route::get('dealer-information', [DealerInformationController::class, 'ApiStore']);
Route::get('rsm', [RsmController::class, 'index']);
Route::get('rsm-list', [RsmController::class, 'create']);
Route::get('rsm-add', [RsmController::class, 'store']);

Route::get('distributor', [DistributorController::class, 'index']);
Route::get('distributor-add', [DistributorController::class, 'store']);

// Route::fallback(function() {
//     return response()->json(['message' => 'Not Found!','code'=>404], 404);
// });