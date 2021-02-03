<?php


namespace App\Validator;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProjectValidator
{

    public function __construct(Request $request)
    {
        $this->title       = $request->get('title');
    }

    /**
     * @Assert\NotBlank()
     */
    public $title;
}