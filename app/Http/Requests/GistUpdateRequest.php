<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


use Illuminate\Http\Response;

use JWTAuth;

class GistUpdateRequest extends FormRequest
{


    protected $user;


    public function __construct(){
        $this->user = JWTAuth::user();

    }



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     *

     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'=>'required',
            'body'=>'required',
        ];
    }



}
