<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TemplateController extends Controller
{
   public function index(){

   	return view('template.index');
   }

   public function create(){
   	return view('template.create');
   }
}
