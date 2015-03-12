<?php

class Album extends Eloquent
{
    use SoftDeletingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'albums';

    protected $dates = ['deleted_at'];
}
