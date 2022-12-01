<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerRaiseController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\DiscountCustomerController;
use App\Http\Controllers\FavoriteProductController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SecondrayClassificationController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\FavoriteStoreController;
use App\Http\Controllers\OptioinValueController;
use App\Http\Controllers\OptionTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\OrderStatuseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductOptionController;
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\RatingStoreController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreVisitoreController;
use App\Models\Discount;
use App\Models\DiscountCustomer;
use App\Models\FavoriteProduct;
use App\Models\FavoriteStore;
use App\Models\OptioinValue;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


//////////////////Boushra//////////////////////////////
////Route Of Stores
Route::resource('stores', StoreController::class);
Route::get('stores/order/reviews', [StoreController::class, 'order_by_review']);
Route::get('stores/order/sales', [StoreController::class, 'order_by_sales']);


////Routes for products
Route::resource('products', ProductController::class);
Route::get('similar_products/{id}', [ProductController::class, 'similar_products']);
Route::get('product_with_class/{id}', [StoreController::class, 'product_with_class']);


/////////Option_product
Route::get('option_for_product/{id}', [OptionTypeController::class, 'option_product']);
Route::get('values_for_option/{id}', [OptioinValueController::class, 'options_type_with_value']);


/////search_product
Route::get('search/product/{name}/{i}', [ProductController::class, 'search_by_name']);

/////search_product
Route::get('search/store/{name}', [StoreController::class, 'search_by_name']);

//////All_material
Route::get('All_material', [OptioinValueController::class, 'All_material']);
Route::get('Gift_request/{d1}/{d2}/{d3}/{d4}/{d5}', [ProductController::class, 'Gift_request']);

////
Route::post('store/visit', [StoreVisitoreController::class, 'store']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('EndRaise/{raise_id}/{price}/',[CustomerRaiseController::class,'EndRaise']);

    //notifiction
    Route::get('/notification/getCustomer/{id}', [NotificationController::class, 'getCustomer']);

    ///add favourite store
    Route::post('FavoriteStore/Add_Favorite', [FavoriteStoreController::class, 'Add_Favorite']);
    Route::delete('FavoriteStore/Delete_Favorite/{id}/{cus_id}', [FavoriteStoreController::class, 'Delete_Favorite']);

    /////Routes for rating products
    Route::resource('rating_product', ProductRatingController::class)->except('show', 'edit', 'destroy', 'update');
    Route::get('isRating/product/{pr_id}/{cus_id}', [ProductRatingController::class, 'isRating']);


    /////Routes for rating stors
    Route::resource('rating_store', RatingStoreController::class)->except('show', 'edit', 'destroy', 'update');
    Route::get('isRating/store/{store_id}/{cus_id}', [RatingStoreController::class, 'isRating']);


    /////Routes for Orders
    Route::resource('orders', OrderController::class);
    Route::resource('order_product', OrderProductController::class)->except('edit', 'index', 'update', 'create');
    Route::resource('option_product', ProductOptionController::class);
    Route::get('product_orders/check/{id}/{id2}', [OrderProductController::class, 'check_of_order']);

    ////Routes for order
    Route::resource('order_status', OrderStatuseController::class);
    Route::get('accept_orders/{id}', [OrderController::class, 'acceptence_orders']);
    Route::get('waiting_orders/{id}', [OrderController::class, 'waiting_orders']);
    Route::get('received_orders/{id}', [OrderController::class, 'received_orders']);
    Route::get('order_product/options/{id}', [ProductOptionController::class, 'get_options']);
    Route::post('orderproduct/update/{id}', [ProductOptionController::class, 'update_choice']);

    ////////My_Favourite_store
    Route::get('myFavorite/{id}', [FavoriteStoreController::class, 'myFavorite']);

    //////add favourite prodcut
    Route::post('FavoriteProduct/Add_Favorite/{id}', [FavoriteProductController::class, 'store']);
    Route::get('isFavourite/product/{product_id}/{customer_id}', [FavoriteProductController::class, 'isFavourite']);
    Route::get('isFavourite/store/{store_id}/{customer_id}', [FavoriteStoreController::class, 'isFavouriteStore']);

    /////bill
    Route::get('bill/{id}', [OrderProductController::class, 'bill']);
    Route::get('all_products_bill/{id}', [OrderProductController::class, 'all_products_bill']);
    Route::get('all_orderproduct/{id}', [OrderProductController::class, 'all_orderproduct']);
    Route::post('edit/order_product', [OrderProductController::class, 'edit_order_product']);
    Route::post('edit/option_product/{id}', [ProductOptionController::class, 'edit_option_product']);
    Route::delete('delete/wating_order/{id}', [OrderProductController::class, 'delete_wating_order']);
    Route::get('discounts_codes/{id}', [DiscountCustomerController::class, 'myDiscount']);
    Route::post("apply_disount/{id}", [DiscountCustomerController::class, 'apply_dis']);
    Route::get('discount_store/{id}/{store_id}', [DiscountCustomerController::class, 'discount_store']);
    Route::delete("delete_discount/{id}", [DiscountCustomerController::class, 'delete_discount']);


    ////edit profile
    Route::post('edit_profile/{id}', [CustomerController::class, 'EditMyProfile']);

    //////report on store
    Route::post('report', [ReportController::class, 'store']);
    Route::get('shop_names', [StoreController::class, 'shop_names']);


});

/////////////tasnem////////////////
Route::get("allCustomer", [CustomerController::class, 'allCustomers']);
Route::get("all_stores", [StoreController::class, 'all_stores']);
Route::get("all_reports", [ReportController::class, 'index']);
Route::get("all_classiffications", [ClassificationController::class, 'All_classifications']);
Route::get("dashboared/admin", [StoreController::class, 'dashBoardAdmin']);
Route::get("review/prodcuts", [ProductController::class, 'review_products']);
Route::get('store/profile/rating/{id}', [StoreController::class, 'rating_profile']);


//////////////////////////////////////////////////////////////
/////////////////////batool_new/////////
Route::get('/Show_p', [App\Http\Controllers\SecondrayClassificationController::class, 'Show_p']);

Route::group(['prefix' => 'Product'], function () {

    Route::post('/temp', [ProductController::class, 'temp']);
    Route::get('/Order_sales', [ProductController::class, 'Order_sales']);
    Route::get('/Order_discount', [ProductController::class, 'Order_discount']);
    Route::get('/Order_Salary', [ProductController::class, 'Order_Salary']);
    Route::get('/Product/Product_All', [ProductController::class, 'Product_All']);
    Route::get('/Product_All', [ProductController::class, 'Product_All']);
    Route::get('/Product_Allf', [ProductController::class, 'Product_Allf']);
    Route::post('/P2', [ProductController::class, 'store']);
    Route::get('/Show_Secondray', [SecondrayClassificationController::class, 'Show_Secondray']);
    Route::get('/ShowClassification2/{id}', [SecondrayClassificationController::class, 'ShowClassification2']);
    Route::get('/ShowClassification/{id}/{title}', [SecondrayClassificationController::class, 'ShowClassification']);
    Route::get('/Show_Detalis/{id}', [ProductController::class, 'Show_Detalis']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('FavoriteProduct/show', [FavoriteProductController::class,'Show']);
    Route::get('Product/Order_favorite', [ProductController::class, 'Order_favorite']);

    Route::post('/StartRaise/{raise_id}/{number}', [CustomerRaiseController::class, 'StartRaise']);

    Route::prefix("FavoriteStore")->namespace('App\Http\Controllers')->group(function () {

        Route::post('/Add_Favorite/{id}', 'App\Http\Controllers\FavoriteStoreController@Add_Favorite');
        Route::get('/index', 'App\Http\Controllers\FavoriteStoreController@index');
        Route::resource('f2', 'FavoriteStoreController');
        Route::get('/Show_Favorite', 'App\Http\Controllers\FavoriteStoreController@Show_Favorite');
    });
    Route::prefix("FavoriteProduct")->namespace('App\Http\Controllers')->group(function () {
        Route::get('/index', 'FavoriteProductController@index');
        Route::resource('f', 'FavoriteProductController');
        Route::post('/store/{id}', 'FavoriteProductController@store');
        //  Route::get('index' , 'FavoriteStoreController@index');

    });


});

//Route::post('FavoriteProduct/store/{id}', [FavoriteProductController::class,'store']);

//,'middleware' => ['auth:sanctum']
Route::prefix("Customer")->group(function () {
    Route::post('/html_email/{name}/{code}/{email}/{title}', 'App\Http\Controllers\CustomerController@html_email');
    Route::post('/changepassword', 'App\Http\Controllers\CustomerController@changepassword');
    Route::post('/login', 'App\Http\Controllers\CustomerController@login');
    Route::post('/logout', 'App\Http\Controllers\CustomerController@logout');
    Route::post('/register', 'App\Http\Controllers\CustomerController@register');
});


Route::prefix("SecondrayClassification")->group(function () {

    Route::post('/ShowClassification/{id}/{title}', 'App\Http\Controllers\SecondrayClassificationController@shwoo');
});


Route::get('/index', [App\Http\Controllers\OrderProductController::class, 'index']);
Route::post('/ChangeToCommit/{productid}/{orderid}', [App\Http\Controllers\OrderProductController::class, 'ChangeToCommit']);
Route::post('/ChangeAmount/{productid}/{orderid}/{amount}', [App\Http\Controllers\OrderProductController::class, 'ChangeAmount']);
Route::post('/DeleveryTime/{orderid}/{date}', [App\Http\Controllers\OrderProductController::class, 'DeleveryTime']);
/////////////////////////////////////////////////////////////tasneem/////////

Route::prefix("settings")->group(function () {

    Route::post('store/create', [App\Http\Controllers\StoreController::class, 'store']);
    Route::post('person/unique', [App\Http\Controllers\StoreManagerController::class, 'unique_email']);
    Route::post('storeManager/login', [App\Http\Controllers\StoreManagerController::class, 'login']);
    Route::post('helper/register', [App\Http\Controllers\HelperController::class, 'accept_help']);

    Route::post('storeManager/verify_email', [App\Http\Controllers\StoreManagerController::class, 'verify_email']);
    Route::post('storeManager/forget_password', [App\Http\Controllers\StoreManagerController::class, 'forget_password']);
    Route::post('storeManager/reset_password/{id}/{new_password}', [App\Http\Controllers\StoreManagerController::class, 'reset_password']);


    Route::get('store/show/{id}', [App\Http\Controllers\StoreController::class, 'myshow']);
    Route::get('storeManager/index/{id}', [App\Http\Controllers\StoreManagerController::class, 'index']);

    Route::post('store/update', [App\Http\Controllers\StoreController::class, 'update']);
    Route::post('storeManager/true_password', [App\Http\Controllers\StoreManagerController::class, 'true_password']);
    Route::get('PrivilladgeHelperController/my_helper/{id}', [App\Http\Controllers\PrivilladgeHelperController::class, 'my_helper']);

    Route::get('storeManager/my_Store_manager/{id}', [App\Http\Controllers\StoreManagerController::class, 'my_Store_manager']);
});

Route::prefix("collection")->group(function () {

    Route::post('create', [App\Http\Controllers\CollectionController::class, 'store']);
    Route::get('collectionNane/{id}', [App\Http\Controllers\CollectionController::class, 'collectionNane']);
    Route::post('update', [App\Http\Controllers\CollectionController::class, 'update']);
    Route::post('delete', [App\Http\Controllers\CollectionController::class, 'delete']);
    Route::get('show/{id}', [App\Http\Controllers\CollectionController::class, 'show']);
});

Route::get('SecondrayClassification/list_seconderay', [App\Http\Controllers\SecondrayClassificationController::class, 'list_seconderay']);

Route::prefix("product")->group(function () {

    Route::post('create', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('delete', [App\Http\Controllers\ProductController::class, 'delete']);
    Route::post('update', [App\Http\Controllers\ProductController::class, 'update']);
    Route::get('index/{id}', [App\Http\Controllers\CollectionController::class, 'index']);
    Route::get('collection/index/{id}', [App\Http\Controllers\CollectionController::class, 'index2']);
    Route::get('show/{id}', [App\Http\Controllers\ProductController::class, 'myshow']);
});

Route::prefix("discountproduct")->group(function () {


    Route::post('create/{id}/{h}', [App\Http\Controllers\DiscountController::class, 'store']);
    Route::post('update', [App\Http\Controllers\DiscountController::class, 'update']);
    Route::get('show/{id}/{type}', [App\Http\Controllers\DiscountController::class, 'show']);
    Route::get('index/{id}', [App\Http\Controllers\DiscountController::class, 'index']);
    Route::get('indexP/{id}', [App\Http\Controllers\DiscountController::class, 'indexP']);
    Route::get('indexC/{id}', [App\Http\Controllers\DiscountController::class, 'indexC']);

    Route::post('delete', [App\Http\Controllers\DiscountController::class, 'delete']);
});

Route::prefix("myorder")->group(function () {

    Route::get('all_my_order/{store_id}/{id}', [App\Http\Controllers\OrderController::class, 'orderstatus']);
    Route::post('accept_order/{id}', [App\Http\Controllers\OrderController::class, 'accept_order']);
    Route::post('delete_order/{id}', [App\Http\Controllers\OrderController::class, 'delete_order']);
    Route::post('deliver_order/{id}', [App\Http\Controllers\OrderController::class, 'deliver_order']);
    Route::get('order_product/{id}', [App\Http\Controllers\OrderProductController::class, 'order_product']);
    Route::get('bill/{id}/{store_maneger_id}', [App\Http\Controllers\OrderProductController::class, 'mybill']);
});

Route::prefix("mycustomer")->group(function () {


    Route::get('myCustomer/{id}', [App\Http\Controllers\CustomerController::class, 'myCustomer']);
    Route::get('myCustomer_most_buy/{id}', [App\Http\Controllers\CustomerController::class, 'myCustomer_most_buy']);
    Route::get('myCustomer_salles/{id}', [App\Http\Controllers\CustomerController::class, 'myCustomer_salles']);
});


Route::prefix("report")->group(function () {

    Route::get('selles/{id}', [App\Http\Controllers\CollectionController::class, 'selles']);
    Route::get('orders/{id}', [App\Http\Controllers\CollectionController::class, 'orders']);
    Route::get('rate_store/{id}', [App\Http\Controllers\CollectionController::class, 'rate_store']);
    Route::get('rate_product/{id}', [App\Http\Controllers\CollectionController::class, 'rate_product']);
});

Route::get('dashbord/{id}', [App\Http\Controllers\CollectionController::class, 'dashbord']);


Route::prefix("admin")->group(function () {

    Route::post('classification/create', [App\Http\Controllers\ClassificationController::class, 'store']);
    Route::get('classification/Show_Classification', [App\Http\Controllers\ClassificationController::class, 'Show_Classification']);
    Route::get('classification/show/{id}', [App\Http\Controllers\ClassificationController::class, 'show']);

    Route::get('Store/show_waite', [App\Http\Controllers\WaitingStoreController::class, 'show_waite']);
    Route::get('Store/show_active', [App\Http\Controllers\WaitingStoreController::class, 'show_active']);
    Route::get('Store/show_deactive', [App\Http\Controllers\WaitingStoreController::class, 'show_deactive']);

    Route::post('WaitingStore/accept_store/{store}', [App\Http\Controllers\WaitingStoreController::class, 'accept_store']);
    Route::post('WaitingStore/reject_store/{store}', [App\Http\Controllers\WaitingStoreController::class, 'reject_store']);
    Route::post('WaitingStore/deactivate_store/{store}', [App\Http\Controllers\WaitingStoreController::class, 'deactivate_store']);
    Route::post('WaitingStore/activate_store/{store}', [App\Http\Controllers\WaitingStoreController::class, 'activate_store']);
});

Route::post('Raise/store', [App\Http\Controllers\RaiseController::class, 'store']);
Route::get('Raise/has_A_raise/{product_id}', [App\Http\Controllers\RaiseController::class, 'has_A_raise']);

Route::post('/alert', [NotificationController::class, 'alert']);
Route::get('/notification/getStore/{id}', [NotificationController::class, 'getStore']);
Route::post('/message/store', [MessageController::class, 'store']);
Route::get('/message/index/{chat_id}/{number}', [MessageController::class, 'index']);
Route::get('/chat/index/{sender_id}', [ChatController::class, 'index']);
Route::post('/chat/singelChat', [ChatController::class, 'singelChat']);


//Route::post('/chatting',[ChatRealController::class,'chatting']);

\Illuminate\Support\Facades\Broadcast::routes(['middleware' => ['auth:sanctum']]

);


Route::post('/chatting',[ChatController::class,'chatting']);
Route::get('/batool/{store_id}',[StoreManagerController::class,'batool']);
Route::get('chatt/{sender_id}/{receiver_id}/',[MessageController::class,'chatt']);
