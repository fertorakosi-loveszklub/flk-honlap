<?php

class RecordCategory extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'record_categories';

    public $timestamps = false;

    public function records()
    {
        return $this->hasMany('Record', 'category_id');
    }
}