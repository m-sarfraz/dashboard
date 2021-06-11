<?php
use App\Mail\WelcomeMail;
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

/**
 * Main view
 */

Route::view('/', 'main')->name('home');

/**
 * Registration and confirmation routes
 */

Route::get('/register', 'Hacker\RegisterController@create')->name('register');

Route::post('/store', 'Hacker\RegisterController@store')->name('store');

Route::post('/check', 'Hacker\TeamController@show')->name('check');

Route::get('/register/confirm/{token}','Hacker\RegisterController@update')->name('confirm');
/**
 * Admin login , logout routes
 */

Route::get('/admin/login','Auth\LoginController@showForm')->name('login') ;

Route::post('/admin/login','Auth\LoginController@login')->name('authenticate');

Route::post('/admin/logout', 'Auth\LoginController@logout')->name('logout');

/**
 * Admin options routes , it contains :
 *      Consulting hackers and set decisions
 *      Consulting confirmations
 *      Send emails for accepted , rejected and waiting list
 *      Update the application settings
 *      Consulting some statistics about hackers
 */

Route::group( ['middleware' => 'auth'], function() {

    Route::get('/admin', 'Admin\HomeController@index')->name('main');

    Route::get('/admin/hackers', 'Admin\ManageHackersController@index')->name('hackers');
    Route::post('/admin/hackers/{hacker}', 'Admin\ManageHackersController@update')->name('setDecision');

    Route::get('/admin/hackers/checkin', 'Admin\CheckinController@index')->name('checkin');
    Route::post('/admin/hackers/checkin/{hacker}', 'Admin\CheckinController@update')->name('checkHacker');

    Route::get('/admin/mailing', 'Admin\MailingController@index')->name('mailing');
    Route::post('/admin/mailWelcome/{id}', 'Admin\MailingController@mailWelcome')->name('mailWelcome');
    Route::get('/admin/mailWelcome', function () {
        Mail::to('muhammadsarfraz531@gmail.com')->send(new WelcomeMail());
        return new WelcomeMail();
    });
    


    Route::get('/admin/mailWelcome/{id}', 'Admin\MailingController@mailWelcome')->name('mailWelcome');
    Route::get('/admin/mWelcome/{id}', 'Admin\MailingController@mWelcome')->name('mWelcome');
    Route::post('/admin/mailing', 'Admin\MailingController@send')->name('sendMail');

    Route::get('/admin/settings', 'Admin\SettingsController@index')->name('settings');
    Route::post('/admin/settings/{settings}', 'Admin\SettingsController@update')->name('updateSettings');

    Route::get('/admin/statistics', 'Admin\StatisticsController@index')->name('statistics');
});
