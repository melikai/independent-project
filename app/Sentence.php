<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sentence extends Model
{
    /**
     * Each sentence has one style
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function style()
    {
        return $this->hasOne(Style::class, 'id', 'style_id');
    }

    /**
     * Each sentence has one emotion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function emotion()
    {
        return $this->hasOne(Emotion::class, 'id', 'emotion_id');
    }

    /**
     * Each sentence has many records
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function records()
    {
        return $this->hasMany(Record::class, 'sentence_id', 'id');
    }

    /**
     * Returns the aver
     * @return mixed
     */
    public function averageScore()
    {
        return $this->records()->get()->pluck('correct')->average();
    }

    /**
     * Average score attribute
     * @return mixed
     */
    public function getAverageScoreAttribute()
    {
        return $this->averageScore();
    }
}
