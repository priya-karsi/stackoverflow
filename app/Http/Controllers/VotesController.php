<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;
class VotesController extends Controller
{
    //
    public function voteQuestion(Question $question, int $vote){
        //either i need to update the vote or i need to create the vote
        if(auth()->user()->hasVoteForQuestion($question)){
            $question->updateVote($vote);
        }else{
            $question->vote($vote);
        }
        return redirect()->back();
    }
    public function voteAnswer(Answer $answer, int $vote){
        //either i need to update the vote or i need to create the vote
        if(auth()->user()->hasVoteForAnswer($answer)){
            $answer->updateVote($vote);
        }else{
            $answer->vote($vote);
        }
        return redirect()->back();
    }
}
