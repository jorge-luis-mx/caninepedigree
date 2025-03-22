<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


//traits
use App\Traits\GuideTrait;

class UserGuideController extends Controller
{

    use GuideTrait;

    public function index(){


        $tutorials =  $this->getTutorials();

        return view('userGuide.user-guide',compact('tutorials'));
    }
    
}
