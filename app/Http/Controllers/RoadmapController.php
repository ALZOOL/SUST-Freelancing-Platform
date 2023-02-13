<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Roadmap;
class RoadmapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //SHOW ROAD-MAPS

    public function show_roadmaps(){
        $users = DB::select('select * from roadmaps');
        return view('ManagerDashboard.roadmaps',['users'=>$users]);
        }

    //##################
    
    //ADD NEW ROAD-MAP 
    
    public function addRoadmap(Request $request)
    {
        $data=new Roadmap;
        $data->title=$request->title;
        $data->category=$request->category;
        $data->description=$request->description;
        
        $data->save();

        //return redirect()->back();
        return view('test');
    }
    
    public function test_fun()
    {
        return view('test');
    }
    
    //################

    //EDIT DELETE ROAD-MAPS
    public function edit_roadmap($id){
    	$myRoadmap = DB::table('roadmaps')->where('id',$id)->first();
    	return view('ManagerDashboard.roadmaps.edit',compact('myRoadmap'));
    }

    public function update_roadmap(Request $request,$id){

    	DB::table('roadmaps')->where('id',$id)->update([

    		'title'=>$request->title,
    		'description'=>$request->description
    	]);
    	return redirect()->route('/Roadmaps');
    } 

    public function delete_roadmap($id){

        DB::table('roadmaps')->where('id',$id)->delete();
        //DB::table('posts')->truncate();
        return redirect()->route('/Roadmaps');

    }

    //####################

}
