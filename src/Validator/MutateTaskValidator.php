<?php


namespace App\Validator;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class MutateTaskValidator
{

    public function __construct(Request $request)
    {
        $this->title   = $request->get('title');
        $this->project = $request->get('project');
        $this->status  = $request->get('status');
        $this->user    = $request->get('user');
    }

    /**
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @Assert\NotBlank()
     */
    public $project;

    /**
     * @Assert\NotBlank()
     */
    public $user;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice({"open", "done"}, message="Invalid status, valid option for status are: open / done")
     */
    public $status;
}