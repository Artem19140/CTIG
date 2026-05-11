<?php

namespace App\Support\Metric;
use Carbon\Carbon;
use DB;

class Metric{

    public static function increase(string $name){

        DB::table('metrics')
            ->insert([
                'center_id' => auth()->user()->center_id,
                'name' => $name,
                'created_at' => Carbon::now()
            ]);   
    }

    public static function get(string $name){
        return DB::table('metrics')->where('name', $name)
            ->value('value');
        
    }

    public static function all(){
        return DB::table('metrics')->select('id', 'name', 'value')
            ->orderBy('value')
            ->get();
    }
}