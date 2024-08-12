<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $products= Product::orderBy('created_at','DESC')->paginate(12);

        return view('index', compact('products'));
    }
}
