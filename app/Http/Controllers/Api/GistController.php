<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GistCreationRequest;
use App\Http\Resources\GistResource;
use App\Models\Gist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GistController extends Controller
{

    protected $user;

    public function __construct(){
        $this->user = JWTAuth::user();

    }


    public function index()
    {
        $gists = Gist::with('user')->get();
        return response()->json([
            'gists' =>GistResource::collection($gists)
        ],Response::HTTP_OK);
    }

    public function store(GistCreationRequest $request)
    {

        $this->user->gists()->create([
        	'title'=>$request->title,
        	'body'=>$request->body,
        ]);

        return response()->json([
            'message' => 'Gist Created Successfully',
        ], Response::HTTP_CREATED);
    }


    public function show($id)
    {
        try {

            $gist=Gist::findorfail($id);
            return response()->json([
                'gist' => new GistResource($gist),
            ], Response::HTTP_OK);


        } catch (Exception $e) {

            if ($e instanceof ModelNotFoundException) {
                return response()->json(['error' => 'cannot find gist'], Response::HTTP_NOT_FOUND);
            }
        }

    }


    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}
