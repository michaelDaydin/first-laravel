<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Menu;
use Session;

class Content extends Model{
    
  static public function getAll($url, &$data)
    {
        if ($menu = Menu::where('url', '=', $url)->first()) {
            $data['contents'] = Menu::find($menu->id)->contents;
            $data['pages_title'] = $menu->title;
            $data['url'] = $url;
        } else {
            abort(404);
        }
    }

    static public function save_new($request){

        $content= new self();
        $content->menu_id=$request['menu_id'];
        $content->con_title=$request['con_title'];
        $content->article=$request['article'];
        $content->save();
        Session::flash('sm','Content created successfuly'); 
    }

    static public function update_item($request,$id){
        
        $content= self::find($id);
        $content->menu_id=$request['menu_id'];
        $content->con_title=$request['con_title'];
        $content->article=$request['article'];
        $content->save();
        Session::flash('sm','Content created successfuly'); 
    } 
}