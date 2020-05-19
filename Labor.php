<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength as StringLengthValidator;
use Phalcon\Http\Message\UploadedFile;

class Labor extends Model
{
    public function initialize()
    {
        $this->setSource('labordata');

        $this->hasMany(
            'id',
            LaborCV::class,
            'user_id',
            [
                'foreignKey' => 'labor'
            ]
        );
    }

    public function beforeValidationOnCreate()
    {
        $this->date_added = time();
        return true;

    }

    public function validation()
    {
        $validator = new Validation();

        $validator->add(['username', 'position'],

          new PresenceOf(
                [
                    "message" => ":field is required"
                ]
          ));

        $validator->add(
            [
                'username',
                'position'
            ],

          new StringLengthValidator(
                [
                    "max"             => 50,
                    "min"             => 2,
                    "messageMaximum"  => "We don't like really long names",
                    "messageMinimum"  => "We want more than just their initials",
                ]
          ));

        $validator->add(
            [
                'username',

            ],
             new Uniqueness(
                [
                    "message"=> "The username must be unique!",
                ]
            ));

        return  $this->validate($validator);

        if ($this->validationHasFailed() === true){
            return false;
        }
    }
}