<?php

use Phalcon\Mvc\Model;

class LaborCV extends Model
{
    public $id;
    public $user_id;

    public function initialize()
    {
        $this->setSource('labor_cv');

        $this->belongsTo(
            'user_id',
            Labor::class,
            'id',
            [
                'alias'=>'labor',
            ]
        );
    }
}