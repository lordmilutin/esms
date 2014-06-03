<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array(
    'as' => 'index',
    'uses' => 'HomeController@index'
));


/*
 *  Authenticated group
 */
Route::group(array('before' =>"auth"),function(){
    /*
    *  Sign out
    */
    
    Route::get('sign-out', array(
       'as' => "signout",
       'uses' => "UserController@signoutView"
    ));
    
});

/*
 *  ADMIN routes
 */

Route::group(array('before' => "admin"), function(){
    
    Route::get('admin/dashboard', array(
        "as" => "adminDashboard",
        "uses" => "AdminController@dashboard"
    ));
    
    Route::get('admin/new-tournament', array(
        "as" => "adminNewTournament",
        "uses" => "AdminController@newTournamentView"
    ));
    
    Route::post('admin/new-tournament',array(
       "as" => "adminNewTournament",
        "uses" => "AdminController@newTournamentData"
    ));
    
});


/*
 *  Unauthenticated group
 */
Route::group(array('before' => "guest"), function() {

    /*
     *  CSRF protection
     */
    Route::group(array('before' => 'csrf'), function() {
        /*
         *  Accept crate account form data
         */
        Route::post('register',array(
            'as' => "createAccount",
            'uses' => "UserController@createAccount"
        ));
        
        // Accept data for login
        Route::post('login', array(
            'as' => "loginData",
            'uses' => "UserController@loginData"
        ));
    });
    
    
    /*
     *  Display create account form
     */
    Route::get('register', array(
        'as' => "register",
        'uses' => "UserController@register"
    ));
    
    Route::get('activate/{code}',array(
        'as' => "activate-account",
        'uses' => "UserController@activate"
    ));
    
    
    Route::get('login', array(
    'as' => "loginView",
    'uses' => "UserController@loginView"
    ));
    
    
    
});

 
/*
 *  Team related routes
 */

Route::get('teams',array(
    'as' => 'teams',
    'uses' => "TeamController@allTeams"
));

Route::get('team/{id}',array(
    'as' => 'team',
    'uses' => "TeamController@teamProfile"
));



/**
 * player profile
 */
Route::get('player/{id}', array(
        'as' => "player-profile",
        'uses' => "PlayerController@showProfile"
    )); 


/**
 * player settings view
 */
Route::get('player-settings', array(
        'as' => "playerSettingsView",
        'uses' => "PlayerController@showPlayerSettings"
    )); 

/**
 * all player list
 */
Route::get('players', array(
        'as' => "players",
        'uses' => "PlayerController@allTeams"
    )); 


/**
 * player settings save post
 */
Route::post('player-settings/save', array(
        'as' => "savePlayerSettings",
        'uses' => "PlayerController@saveSettingsData"
));
