<?php

Route::view('/', 'welcome');
Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', '2fa', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Payments
    Route::delete('payments/destroy', 'PaymentsController@massDestroy')->name('payments.massDestroy');
    Route::resource('payments', 'PaymentsController');

    // Credits
    Route::delete('credits/destroy', 'CreditsController@massDestroy')->name('credits.massDestroy');
    Route::resource('credits', 'CreditsController');

    // Project
    Route::delete('projects/destroy', 'ProjectController@massDestroy')->name('projects.massDestroy');
    Route::resource('projects', 'ProjectController');

    // Avatars
    Route::delete('avatars/destroy', 'AvatarsController@massDestroy')->name('avatars.massDestroy');
    Route::post('avatars/media', 'AvatarsController@storeMedia')->name('avatars.storeMedia');
    Route::post('avatars/ckmedia', 'AvatarsController@storeCKEditorImages')->name('avatars.storeCKEditorImages');
    Route::resource('avatars', 'AvatarsController');

    // Audios
    Route::delete('audios/destroy', 'AudiosController@massDestroy')->name('audios.massDestroy');
    Route::post('audios/media', 'AudiosController@storeMedia')->name('audios.storeMedia');
    Route::post('audios/ckmedia', 'AudiosController@storeCKEditorImages')->name('audios.storeCKEditorImages');
    Route::resource('audios', 'AudiosController');

    // Videos
    Route::delete('videos/destroy', 'VideosController@massDestroy')->name('videos.massDestroy');
    Route::post('videos/media', 'VideosController@storeMedia')->name('videos.storeMedia');
    Route::post('videos/ckmedia', 'VideosController@storeCKEditorImages')->name('videos.storeCKEditorImages');
    Route::resource('videos', 'VideosController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
        Route::post('profile/two-factor', 'ChangePasswordController@toggleTwoFactor')->name('password.toggleTwoFactor');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth', '2fa']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Payments
    Route::delete('payments/destroy', 'PaymentsController@massDestroy')->name('payments.massDestroy');
    Route::resource('payments', 'PaymentsController');

    // Credits
    Route::delete('credits/destroy', 'CreditsController@massDestroy')->name('credits.massDestroy');
    Route::resource('credits', 'CreditsController');

    // Project
    Route::delete('projects/destroy', 'ProjectController@massDestroy')->name('projects.massDestroy');
    Route::resource('projects', 'ProjectController');

    // Avatars
    Route::delete('avatars/destroy', 'AvatarsController@massDestroy')->name('avatars.massDestroy');
    Route::post('avatars/media', 'AvatarsController@storeMedia')->name('avatars.storeMedia');
    Route::post('avatars/ckmedia', 'AvatarsController@storeCKEditorImages')->name('avatars.storeCKEditorImages');
    Route::resource('avatars', 'AvatarsController');

    // Audios
    Route::delete('audios/destroy', 'AudiosController@massDestroy')->name('audios.massDestroy');
    Route::post('audios/media', 'AudiosController@storeMedia')->name('audios.storeMedia');
    Route::post('audios/ckmedia', 'AudiosController@storeCKEditorImages')->name('audios.storeCKEditorImages');
    Route::resource('audios', 'AudiosController');

    // Videos
    Route::delete('videos/destroy', 'VideosController@massDestroy')->name('videos.massDestroy');
    Route::post('videos/media', 'VideosController@storeMedia')->name('videos.storeMedia');
    Route::post('videos/ckmedia', 'VideosController@storeCKEditorImages')->name('videos.storeCKEditorImages');
    Route::resource('videos', 'VideosController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
    Route::post('profile/toggle-two-factor', 'ProfileController@toggleTwoFactor')->name('profile.toggle-two-factor');
});
Route::group(['namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Two Factor Authentication
    if (file_exists(app_path('Http/Controllers/Auth/TwoFactorController.php'))) {
        Route::get('two-factor', 'TwoFactorController@show')->name('twoFactor.show');
        Route::post('two-factor', 'TwoFactorController@check')->name('twoFactor.check');
        Route::get('two-factor/resend', 'TwoFactorController@resend')->name('twoFactor.resend');
    }
});
