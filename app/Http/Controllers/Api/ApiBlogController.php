<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Review;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Session;
use DB;


class ApiBlogController extends Controller
{
    public function __construct(){
        // header('Access-Control-Allow-Origin: *');
    }
    public function category(){
        $category = BlogCategory::where('status',1)->with('subs')->get();
        return response()->json($category);
    }


    public function allBlog(){
        $data = Blog::orderBy('id','DESC')->paginate(20);
        return response()->json($data);
    }
    public function frontBlog(){
        $data = Blog::orderBy('id','DESC')->take(2)->get();
        return response()->json($data);
    }

    public function blogshow($id){
        $data = Blog::where('id',$id)->first();
        return response()->json($data);
    }

    public function allProgramm(){
        $data = Service::orderBy('id','DESC')->paginate(20,
            ['id','title','sub_title','photo','details']);
        return response()->json($data);
    }
    public function frontProgramm(){
        $data = Service::orderBy('id','DESC')->take(4)->get();
        return response()->json($data);
    }
    public function Programmshow($id){
        $data = Service::where('id',$id)->first();
        return response()->json($data);
    }

    public function allSlider(){
        $data = Slider::orderBy('id','DESC')->take(4)->get();
        return response()->json($data);
    }

    public function testimonials(){
        $data = Review::orderBy('id','DESC')->take(5)->get();
        return response()->json($data);
    }

}