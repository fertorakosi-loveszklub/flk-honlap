<?php

use SammyK\FacebookQueryBuilder\FacebookQueryBuilderException;

class AccountController extends BaseController
{
    /**
     * GET method, belepes route (/belepes)
     * Callback for the facebook login.
     * @return mixed Redirection
     */
    public function getBelepes()
    {
        // Get Facebook access token
        try
        {
            $token = Facebook::getTokenFromRedirect();

            if ( ! $token)
            {
                return Redirect::to('/')->with('message', array( 'message' => 'A Facebook belépés sikertelen. <em>Az access_token nem elérhető</em>.',
                    'type' => 'danger'));
            }
        }
        catch (FacebookQueryBuilderException $e)
        {
            return Redirect::to('/')->with('message', array( 'message' => $e->getPrevious()->getMessage(),
                'type' => 'danger'));
        }

        // Extend access token if necessary
        if ( ! $token->isLongLived())
        {
            try
            {
                $token = $token->extend();
            }
            catch (FacebookQueryBuilderException $e)
            {
                return Redirect::to('/')->with('message', array( 'message' => $e->getPrevious()->getMessage(),
                                                                'type' => 'danger'));
            }
        }

        // Set access token
        Facebook::setAccessToken($token);

        //Get user info
        try
        {
            $facebook_user = Facebook::object('me')->fields('id','name')->get();
            // $groups = Facebook::object('me/groups')->fields('id')->get();
        }
        catch (FacebookQueryBuilderException $e)
        {
            return Redirect::to('/')->with('message', array( 'message' => $e->getPrevious()->getMessage(),
                'type' => 'danger'));
        }

        // Loop through groups, check if user is a member of the group
        /*
        $member = false;
        foreach($groups as $g) {
            if ($g['id'] == "361318767380357") {
                $member = true;
                break;
            }
        }

        // User is not a member, redirect him to join (requires JS SDK)
        if (!$member) {
            return Redirect::to('/felhasznalo/uj');
        }
        */
        
        User::CreateOrUpdate($facebook_user);

        // Create the user if not exists or update existing
        $user = User::createOrUpdate($facebook_user);

        Session::put('user_full_name', $user->real_name);
        Session::put('member', true);

        // Log the user into Laravel
        Facebook::auth()->login($user);

        // Check admin privileges
        try {
            Facebook::setAccessToken(Config::get('laravel-facebook-sdk::app_id') . '|' . Config::get('laravel-facebook-sdk::app_secret'));
            $roles = Facebook::object('228336310678604/roles')->get();
            Facebook::setAccessToken($token);
        }
        catch (Exception $e)
        {
            return Redirect::to('/')->with('message', array( 'message' => 'Az admin jogok ellenőrzése sikertelen.',
                'type' => 'danger'));
        }

        // Loop through roles, check for user id with 'administrators' role
        foreach($roles as $r) {
            if ($user->id == $r['user'] && $r['role'] == 'administrators') {
                Session::put('admin', true);
            }
        }

        return Redirect::to('/');
    }

    /**
     * POST method, uj-nev route(/uj-nev/)
     * Updates the real name of the currently logged in user.
     * @return mixed JSON
     */
    public function postUjNev()
    {
        $response = array(
            'success'   => false,
            'message'   => null,
            'newName'   => null
        );

        // Check if logged in
        if (!Auth::check()) {
            $response['message'] = 'Nem vagy bejelentkezve';
            return Response::json($response);
        }

        // Validate name
        $validation = array(
            'NewName'       => 'required|min:4|regex:/[a-zA-Z ]+/',
        );

        $validator = Validator::make(Input::all(), $validation);

        if ($validator->fails()) {
            $response['message'] = 'Érvénytelen név.';
            return Response::json($response);
        }

        // Change name
        $user = Auth::user();
        $user->real_name = Input::get('NewName');
        $user->save();

        Session::put('user_full_name', $user->real_name);

        $response['success'] = true;
        $response['newName'] = $user->real_name;

        return Response::json($response);
    }

    /**
     * GET method, uj route (/uj)
     * Displays a join request for new members.
     */
    public function getUj()
    {
        return View::make('layouts.account.new', array());
    }
}
