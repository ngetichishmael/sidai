<?php

namespace App\Http\Controllers\app\survey;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\survey\category;
use App\Models\survey\survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class surveyController extends Controller
{
   //index
   public function index()
   {
      return view('app.survey.survey.index');
   }
   public function active()
   {
      $surveries = survey::orderby('id', 'desc')->get();
      return view('app.survey.survey.active', compact('surveries'));
   }

   public function responses() {
      $surveries = DB::table('survey_responses')->join('survey','survey.code','=','survey_responses.survey_code')->join('survey_questions','survey_questions.question_code','=','survey_responses.question_code')
      ->join('customers','customers.id','=','survey_responses.customer_id')->orderby('survey_responses.id', 'desc')->get();

      return view('app.survey.survey.responses', compact('surveries'));
   }
   //create trivia
   public function create()
   {
      $users = User::pluck('name');
      $category = category::orderby('id', 'desc')->pluck('title', 'id')->prepend('choose category', '');
      return view('app.survey.survey.create', compact('category', 'users'));
   }

   //store trivia
   public function store(Request $request)
   {

      $request->validate([
         'title' => 'required',
         'type' => 'required',
         'status' => 'required',
         'start_date' => 'required',
         'end_date' => 'required',
         'description' => 'required',


      ]);

      $survey = new survey;
      // if(!empty($request->image)){
      //    $file = $request->image;
      //    // SET UPLOAD PATH
      //    $destinationPath = base_path().'/public/survey/survey/';
      //    // GET THE FILE EXTENSION
      //    $extension = $file->getClientOriginalExtension();

      //    // RENAME THE UPLOAD WITH RANDOM NUMBER
      //    $fileName = Helper::generateRandomString(10). '.' . $extension;
      //    // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
      //    $file->move($destinationPath, $fileName);

      //    $survey->image = $fileName;
      // }
      $survey->title = $request->title;
      $survey->code = Helper::generateRandomString(9);
      $survey->description = $request->description;
      $survey->status = $request->status;
      $survey->start_date = $request->start_date;
      $survey->end_date = $request->end_date;
      $survey->type = $request->type;
      $survey->visibility = $request->visibility;
      $survey->correct_answers = 0;
      $survey->wrong_answers = 0;
      $survey->created_by = Auth::user()->id;
      $survey->save();

      Session::flash('success', 'Survey successfully created');

      return redirect()->route('survey.index');
   }


   //edit trivia
   public function edit($code)
   {
      $category = category::orderby('id', 'desc')->pluck('title', 'id')->prepend('choose category', '');
      $edit = survey::where('code', $code)->first();

      return view('app.survey.survey.edit', compact('category', 'edit'));
   }

   //update trivia
   public function update(Request $request, $code)
   {
      $survey = survey::where('code', $code)->first();
      // if(!empty($request->image)){
      //    $old = survey::where('code','=',$code)->select('image')->first();

      //    $directory = base_path().'/public/trivia/trivia/'.$old->image;

      //    if(File::exists($directory)) {
      //       unlink($directory);
      //    }

      //    $file = $request->image;
      //    // SET UPLOAD PATH
      //    $destinationPath = base_path().'/business/trivia/trivia/';
      //    // GET THE FILE EXTENSION
      //    $extension = $file->getClientOriginalExtension();

      //    // RENAME THE UPLOAD WITH RANDOM NUMBER
      //    $fileName = Helper::generateRandomString(10). '.' . $extension;
      //    // MOVE THE UPLOADED FILES TO THE DESTINATION DIRECTORY
      //    $file->move($destinationPath, $fileName);

      //    $survey->image = $fileName;
      // }
      $survey->title = $request->title;
      $survey->description = $request->description;
      $survey->category = $request->category;
      $survey->status = $request->status;
      $survey->start_date = $request->start_date;
      $survey->end_date = $request->end_date;
      $survey->type = $request->type;
      $survey->visibility = $request->visibility;
      $survey->updated_by = Auth::user()->id;
      $survey->save();

      Session::flash('success', 'Survey successfully updated');

      return redirect()->back();
   }

   //trivia details
   public function show($code)
   {
      $survey = survey::where('code', $code)->first();

      return view('app.survey.survey.show', compact('survey'));
   }

   public function delete($id)
   {

      $slider = survey::select('id')->where('code', $id)->first();
      $slider->delete();

      Session::flash('success', 'The survey was successfully deleted !');

      return redirect()->route('survey.index');
   }
}
