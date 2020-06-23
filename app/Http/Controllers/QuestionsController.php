<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Requests\Questions\UpdateQuestionRequest;



class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $questions = Question::with('owner')->latest()->paginate(10); //eager load
        return view('questions.index',compact([
            'questions'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        app('debugbar')->disable();
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware(['auth'])->only(['create,store,edit,update,delete']);
    }
    public function store(CreateQuestionRequest $request)
    {
        //
        auth()->user()->questions()->create([
            'title'=>$request->title,
            'body'=>$request->body
            ]);
        session()->flash('success','Question was added successfully!');
        return redirect(route('questions.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
        $question->increment('views_count');
        return view('questions.show',compact([
            'question'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
        // if(Gate::allows('update-question',$question)){
        // return view('questions.edit',compact(['question']));
        // }
        // abort(403);
        if($this->authorize('update',$question)){
            return view('questions.edit',compact(['question']));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        
        // if(Gate::allows('update-question',$question)){
        //     $question->update([
        //     'title'=>$request->title,
        //     'body'=>$request->body,

        // ]);
        // session()->flash('success','question has been updated successfully!');
        // return redirect(route('questions.index'));
        // }
        // abort(403);

        if($this->authorize('update',$question)){
            $question->update([
            'title'=>$request->title,
            'body'=>$request->body,

        ]);
        session()->flash('success','question has been updated successfully!');
        return redirect(route('questions.index'));
        }
        abort(403);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //second way of gate allows
       //  if(auth()->user()->can('delete-question',$question)){
       //       $question->delete();
       //  session()->flash('success','question has been deleted successfully!');
       //  return redirect(route('questions.index'));
       //  }
       // abort(403);

        if($this->authorize('delete',$question)){
            $question->delete();
        session()->flash('success','question has been deleted successfully!');
        return redirect(route('questions.index'));
        }
       abort(403);

    }
}
