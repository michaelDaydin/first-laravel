<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB,Cart,Session,Image,Categorie;

class Product extends Model
{

  public static function shopItem()
  {

    $products = DB::table('products as p')
      ->join('categories as c', 'c.id', '=', 'p.categorie_id')
      ->select('p.*', 'c.ctitle', 'c.carticle', 'c.curl')
      ->paginate(8);
    return $products;
  }

  public static function getItem($purl, &$data)
  {
    if ($item = Product::where('purl', '=', $purl)->first()) {

      $data['item'] = $item->toArray();
      $data['pages_title'] = $data['item']['ptitle'];
    } else {

      abort(404);
    }
  }
  public static function addToCart($pid)
  {
    if (!empty($pid) && is_numeric($pid)) {

      if ($products = Product::find($pid)) {

        $products = $products->toArray();
        if (!Cart::get($pid)) {
          Cart::add($pid, $products['ptitle'], $products['price'], 1, ['image' => $products['pimage']]);
          Session::flash('sm', $products['ptitle'] .  '- Added to cart!!!');
        }
      }
    }
  }

  public static function updateCart($op, $pid)
  {
    if (!empty($pid) && is_numeric($pid) && Cart::get($pid)) {

      if (!empty($op)) {

        if ($op === 'plus') {
          Cart::update($pid, [
            'quantity' => 1
          ]);
        } elseif ($op === 'minus') {

          Cart::update($pid, [
            'quantity' => -1
          ]);
        }
      }
    }
  }

  static public function save_new($request){

    $image_name='default-image.jpg';
    if($request->hasFile('pimage') && $request->file('pimage')->isValid()){

      $file=$request->file('pimage');
      
      $image_name = date('Y.m.d.H.i.s') . '-' . $file->getClientOriginalName();
  
   $request->file('pimage')->move(public_path().'/img/',$image_name);
      
    $img = Image::make(public_path().'/img/'. $image_name);
          
   $img->resize(300, null, function($constrain){

        $constrain->aspectRatio();
   });
  
     $img->save();
     
   }

    $product = new self();
    $product->categorie_id=$request['categorie_id'];
    $product->ptitle=$request['ptitle'];
    $product->particle=$request['particle'];
    $product->pimage=$image_name;
    $product->purl=$request['purl'];
    $product->price=$request['price'];
    $product->save();
    Session::flash('sm','Product created successfuly'); 
  }
  
  static public function update_item($request, $id){

    $image_name='';
    
    if($request->hasFile('pimage') && $request->file('pimage')->isValid()){

      $file=$request->file('pimage');
      
      $image_name = date('Y.m.d.H.i.s') . '-' . $file->getClientOriginalName();
  
   $request->file('pimage')->move(public_path().'/img/',$image_name);
      
    $img = Image::make(public_path().'/img/' . $image_name);
          
   $img->resize(300, null, function($constrain){

        $constrain->aspectRatio();
   });
  
     $img->save();
     
   }

    $product =self::find($id);
    $product->categorie_id=$request['categorie_id'];
    $product->ptitle=$request['ptitle'];
    $product->particle=$request['particle'];
   if($image_name){
     
    $product->pimage=$image_name;
   }
    $product->purl=$request['purl'];
    $product->price=$request['price'];
    $product->save();
    
    Session::flash('sm','Product update successfuly'); 
  }
}