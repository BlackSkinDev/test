<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GistCreationRequest;
use App\Http\Requests\GistUpdateRequest;
use App\Http\Resources\GistResource;
use App\Models\Gist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class GistController extends Controller
{

    protected $user;

    public function __construct(){
        $this->user = JWTAuth::user();

    }


    public function index()
    {
        $Gists = Gist::with('user')->get();
        return response()->json([
            'gists' =>GistResource::collection($Gists)
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
                return response()->json(['error' => 'gist does not exist'], Response::HTTP_NOT_FOUND);
            }
        }

    }


    public function update(GistUpdateRequest $request, $id)
    {

        try {

            $gist=Gist::findorfail($id);

            if ($this->user->cannot('update', $gist)) {
                return response()->json([
                    'error' => 'Action is Unathorized',
                ], Response::HTTP_FORBIDDEN);
            }

            $gist->update($request->validated());

            return response()->json([
                'message' => 'gist updated successfully',
            ], Response::HTTP_OK);


        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['error' => 'gist does not exist'], Response::HTTP_NOT_FOUND);
            }

        }



    }

    public function destroy($id)
    {

        try {

            $gist=Gist::findorfail($id);

            if ($this->user->cannot('delete', $gist)) {
                return response()->json([
                    'error' => 'Action is Unathorized',
                ], Response::HTTP_FORBIDDEN);
            }

            $gist->delete();
            return response()->json([
                'message' => 'gist deleted successfully',
            ], Response::HTTP_OK);


        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json(['error' => 'gist does not exist'], Response::HTTP_NOT_FOUND);
            }

        }


    }

    public function getTrash(){

        $trashed =  Gist::onlyTrashed()->get();

        return response()->json([
            'trashedGist' =>$trashed,
        ], Response::HTTP_OK);

    }

}
