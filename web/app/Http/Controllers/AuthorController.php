<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BookService\BookService;
use Illuminate\Support\Facades\Validator;
use App\Services\BookService\Exceptions\NotFoundException;
use App\Services\BookService\Exceptions\UnauthorizedException;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BookService $bookService)
    {
        try{
            $authors = $bookService->getAuthors(session('access_token'));
            return view('author.index',compact('authors'));
        }
        catch(NotFoundException $exception){
            return abort(404);
        }
        catch(UnauthorizedException $exception){
            return redirect()->route('user.sign-out');
        }
        catch(\Exception $exception){
            throw $exception;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('author.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,BookService $bookService)
    {
        $validator = Validator::make($request->all(),[
            'name' =>  'required'
        ]);

        if($validator->fails()){
            return back()
            ->withInput($request->input())
            ->withErrors($validator);
        }

        try{
            $bookService->addAuthor(session('access_token'),$request->input('name'));
            return redirect()->route('authors.index');
        }
        catch(NotFoundException $exception){
            return abort(404);
        }
        catch(UnauthorizedException $exception){
            return redirect()->route('user.sign-out');
        }
        catch(\Exception $exception){
            return back()
            ->withInput($request->input())
            ->withErrors(['message' => 'Unexcepted error please try later!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BookService $bookService,$id)
    {
        try{
            $author = $bookService->getAuthor(session('access_token'),$id)['data'];
            return view('author.edit',compact('author'));
        }
        catch(NotFoundException $exception){
            return abort(404);
        }
        catch(UnauthorizedException $exception){
            return redirect()->route('user.sign-out');
        }
        catch(\Exception $exception){
            throw $exception;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,BookService $bookService, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' =>  'required'
        ]);

        if($validator->fails()){
            return back()
            ->withInput($request->input())
            ->withErrors($validator);
        }

        try{
            $bookService->updateAuthor(session('access_token'),$id,$request->input('name'));
            return redirect()->route('authors.index');
        }
        catch(NotFoundException $exception){
            return abort(404);
        }
        catch(UnauthorizedException $exception){
            return redirect()->route('user.sign-out');
        }
        catch(\Exception $exception){
            return back()
            ->withInput($request->input())
            ->withErrors(['message' => 'Unexcepted error please try later!']);
        }
    }
}
