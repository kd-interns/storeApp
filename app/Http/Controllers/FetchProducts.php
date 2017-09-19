<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class FetchProducts extends Controller
{ 
    var $username = 'abaca78ca1af3bff4ed949a2af416e77';
    var $password = '660b7b9bc3be2aaad0a4980aa62b4284';
    var $products = [];
    function fetchProductsFromShopify(){
       $arrFinalProducts =[];


       $remote_url = 'https://govieninence.myshopify.com/admin/products.json';

        $data=$this->apiCall($remote_url);
        $data = json_decode($data);
        $products= $data->products;

        $this->products = $products;

        foreach($products as $product){
            $title = $product->title;
            $varients =  $product->variants;
            // print_r($product);
            foreach($varients as $variant)               
             $price= $variant->price;

            
            array_push($arrFinalProducts,["title"=>$title,"price"=>$price,"id"=>$product->id]);
        }

        return view('welcome',["products"=>$arrFinalProducts]);
    }
    function productDetails(Request $request){
        $pid = $request['pid'];
        // product type or category, price, explode(" ",$array);
         
        $remote_url = 'https://govieninence.myshopify.com/admin/products/'.$pid.'.json';
        $data = $this->apiCall($remote_url);
        $data = json_decode($data)->product;
        $product = [];
        $product['id']=$data->id;
        $product['title']=$data->title;
        $product['desc']=$data->body_html;
        $product['image']=$data->image->src;
        // $this->postToEventServer($product);
        // $varients = $data->variants;
        //  foreach($varients as $variant)               
        //      $price= $variant->price;
             
        $product['price']=$data->variants[0]->price;
        return view('productDetails',$product);
    }
     
        // $pid = $request['pid'];
        // product type or category, price, explode(" ",$array);
         
        //  $remote_url = 'https://govieninence.myshopify.com/admin/products/#'.$pid.'.json';
        //  $data = $this->apiCall($remote_url);
        //  $this->postToEventServer();
        //  Log::info($data);
         //return view('productDetails',["oneproduct"=>$oneproductdetail]
        //  ,["productIno"=>$arrProdInfo,"relatedProducts"=>$arrRelated]
        // );


    function apiCall($url){
        $ch = curl_init($url);
        // such as http://example.com/example.xml

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_USERPWD,$this->username.':'.$this->password);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
