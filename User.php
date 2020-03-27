<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use Hash;

class User extends Model
{

  public static function signin($email, $password)
  {
    $verify = false;
    $user = DB::table('users as u')
      ->join('users_roles as ur', 'u.id', '=', 'ur.user_id')
      ->select('u.*', 'ur.role_id')
      ->where('u.email', '=', $email)
      ->first();
    if ($user && Hash::check($password, $user->password)) {

      if ($user->role_id == 6) {

        Session::put('is_admin', true);
      }
      Session::put('user_name', $user->name);
      Session::put('user_id', $user->id);
      Session::flash('sm', ' welcome back ' . $user->name);
      $verify = true;
    }
    return $verify;
  }

  public static function save_new($request)
  {

    $user = new self();
    $user->name = $request['name'];
    $user->email = $request['email'];
    $user->password = bcrypt($request['password']);
    $user->save();
    $uid = $user->id;

    DB::insert("INSERT INTO users_roles VALUES($uid, 7)");
    Session::put('user_name', $user->name);
    Session::put('user_id', $user->id);
    Session::flash('sm', 'You are now signup to books');
  }


  
}