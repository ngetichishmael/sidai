@extends('layouts.app')
@section('title','Survey | Questions')
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"> Survey | {!! $survey->title !!} - Questions </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Survey</a></li>
                     <li class="breadcrumb-item active">Questions</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
      <div class="col-md-6">
         @include('app.survey._menu')
      </div>
      <div class="col-md-12">
         <a href="{!! route('survey.questions.create',$survey->code) !!}" class="btn btn-success btn-sm float-right mt-2"> Add a question</a>
      </div>
      <div class="col-md-12">
         <div class="card mt-2">
            <div class="card-body">
               <table class="table table-striped table-bordered zero-configuration">
                  <thead>
                     <tr>
                        <th width="1%">#</th>
                        <th>Question</th>
                        <th width="9%">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($questions as $count=>$question)
                        <tr>
                           <td>{!! $count+1 !!}</td>
                           <td>
                              {!! $question->question !!}<br>
                              <b>Type:</b> {!! $question->name !!}<br>
                              <b>Answer:</b> {!! $question->answer !!}
                           </td>
                           <td>
                              <a href="{!! route('survey.questions.edit',[$survey->code,$question->questionID]) !!}" class="btn btn-primary btn-sm"><i class="fad fa-edit"></i>Edit</a>
                              <a href="" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>Delete</a>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
@endsection
