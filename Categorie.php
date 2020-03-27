<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;

class Categorie extends Model
{

  public static function getCategories($curl, &$data)
  {
    $categories = DB::table('products as p')
      ->join('categories as c', 'c.id', '=', 'p.categorie_id')
      ->where('c.curl', '=',  $curl)
      ->select('p.*', 'c.ctitle', 'c.carticle', 'c.curl')
      ->get()
      ->toArray();
      
    if ($categories) {
      $data['categories'] = $categories;
      $data['pages_title'] = $categories[0]->ctitle;
      $data['categ_article'] = $categories[0]->carticle;
    } else {
      abort(404);
    }
  }
  static public function save_new($request){

    $category=new self();
    $category->carticle=$request['carticle'];
    $category->curl=$request['curl'];
    $category->ctitle=$request['ctitle'];
    $category->save();
    Session::flash('sm','Category created successfuly');

  }
  
  static public function update_item($request,$id){
        
    $category= self::find($id);
    $category->carticle=$request['carticle'];
    $category->curl=$request['curl'];
    $category->ctitle=$request['ctitle'];
    $category->save();
    Session::flash('sm','Category created successfuly');
  }
}