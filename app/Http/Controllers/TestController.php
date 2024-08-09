<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function homepage(){
        // data imported from database
        $Name = 'Juxn';
        $ListOfNames  = ['A', 'B', 'C', 'D'];
        return view("homepage",['list' => $ListOfNames, 'name' => $Name]);
    }

    public function about(){
        return view("single-post");
    }


    public function welcome(){
        // data imported from database
        $Name = 'Juxn';
        $ListOfNames  = ['A', 'B', 'C', 'D'];
        return view("welcome",['list' => $ListOfNames, 'name' => $Name]);
    }
}
