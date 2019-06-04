<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;

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
        $questions = Question::with('user')->latest()->paginate(2);
        //echo "<pre>"; print_r($questions); die;
        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $question = new Question();
        return view('questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        //can get the current user by this $request->user()
        //this will add the approriate user id value to the question model
        //and pass the question that we enter in the form to the create method
        //$request->user()->questions()->create($request->all());
        $request->user()->questions()->create($request->only('title','body'));
        return redirect()->route('questions.index')->with('success', "Your Question has been submitted");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->increment('views');
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        // if(\Gate::allows('update-question', $question)) {
        //    return view("questions.edit", compact('question'));
        // }
        // abort(403, "Access Denied");

        if(\Gate::denies('update-question', $question)) {
           abort(403, "Access Denied");
        }

        return view("questions.edit", compact('question'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        if(\Gate::denies('update-question', $question)) {
           abort(403, "Access Denied");
        }

        $question->update($request->only('title', 'body'));
        return redirect('/questions')->with('success', "Your question has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        if(\Gate::denies('update-question', $question)) {
           abort(403, "Access Denied");
        }

        $question->delete();
        return redirect('/questions')->with('success', "Your Question has been deleted.");

    }
}
