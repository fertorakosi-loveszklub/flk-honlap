<?php

class Record extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'records';

    public function category()
    {
        return $this->belongsTo('RecordCategory', 'category_id', 'id');
    }
}