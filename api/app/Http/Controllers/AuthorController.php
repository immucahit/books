<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Http\Resources\AuthorResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Events\Logging;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cache::remember('author',now()->addHour(),function(){
            return AuthorResource::collection(Author::all());
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->input('user');

        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()
                ->json([
                    'message' => 'Lütfen tüm alanları doldurunuz!'
                ],400);
        }

        $author = Author::create(['name' => $request->input('name')]);

        Cache::forget('author');

        Logging::dispatch($user,'insert',$author);

        return new AuthorResource($author);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Cache::remember('author:id:'.$id,now()->addHour(),function() use ($id){
            $author = Author::find($id);
            if($author){
                return new AuthorResource($author);
            }
            return response()->noContent(404);
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->input('user');
        
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()
                ->json([
                    'message' => 'Lütfen tüm alanları doldurunuz!'
                ],400);
        }

        $author = Author::find($id);
        if(!$author){
            return response()->noContent(404);
        }

        $author->name = $request->input('name');
        if($author->save()){
            Cache::forget('author');
            Cache::forget('author:id:'.$id);
        }
        Logging::dispatch($user,'update',$author);
        return new AuthorResource($author);
    }
}
