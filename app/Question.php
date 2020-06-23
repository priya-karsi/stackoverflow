<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    //
    protected $guarded =[];
    /*
	Mutators - These are special fcuntions which have setXXXAttribute($value)
    */

    public function settitleAttribute($title){
    	$this->attributes['title'] = $title;
    	$this->attributes['slug']=Str::slug($title);
    }
    /*Accessors*/
    public function getUrlAttribute(){
    	return "questions/{$this->slug}";
    }
    public function getCreatedDateAttribute(){
    	return $this->created_at->diffForHumans();
    }
    public function getAnswerStyleAttribute(){
    	if($this->answers_count>0){
    		if($this->best_answer_id){
    			return "has-best-answer";
    		}
    		return "answered";
    	}
    	return "unanswered";
    }

    public function getFavoritesCountAttribute(){
        return $this->favorites->count();
    }

    public function getIsFavoriteAttribute(){
        return $this->favorites()->where(['user_id'=>auth()->id()])->count()>0;
    }

    /*
		relationship functions!
    */
		public function owner(){
			return $this->belongsTo(User::class, 'user_id');
		}
        public function answers(){
        return $this->hasMany(Answer::class);
    }
    public function favorites(){
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Helper functions
     */
    public function votes(){
        return $this->morphToMany(User::class,'vote')->withTimestamps();
    }
    public function markBestAnswer(Answer $answer){
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function vote(int $vote){
        $this->votes()->attach(auth()->id(),['vote'=>$vote]);
        if($vote<0){
            $this->decrement('votes_count');
        }
        else{
            $this->increment('votes_count');
        }
    }

    public function updateVote(int $vote){
        $this->votes()->updateExistingPivot(auth()->id(), ['vote'=>$vote]);
        if($vote<0){
            $this->decrement('votes_count');
            $this->decrement('votes_count');
        }
        else{
            $this->increment('votes_count');
            $this->increment('votes_count');
        }
    }
}
