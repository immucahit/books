<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService\BookService;
use Illuminate\Support\Facades\Validator;
use App\Services\BookService\Exceptions\BadRequestException;
use App\Services\BookService\Exceptions\NotFoundException;
use App\Services\BookService\Exceptions\UnauthorizedException;
use App\Services\BookService\Exceptions\UnexceptedErrorException;
use Exception;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BookService $bookService)
    {
        try{
            $books = $bookService->getBooks(session('access_token'));
            return view('book.index',compact('books'));
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
    public function create(BookService $bookService)
    {
        try{
            $authors = $bookService->getAuthors(session('access_token'));
            return view('book.create',compact('authors'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,BookService $bookService)
    {
        $validator = Validator::make($request->all(),[
            'title' =>  'required',
            'isbn' => 'required'
        ]);

        if($validator->fails()){
            return back()
            ->withInput($request->input())
            ->withErrors($validator);
        }

        try{
            $bookService->addBook(session('access_token'),$request->input('title'),$request->input('isbn'),$request->input('description'),$request->input('author'));
            return redirect()->route('books.index');
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
    public function edit(Request $request, BookService $bookService,$id)
    {
        try{
            $book = $bookService->getBook(session('access_token'),$id);
            $authors = $bookService->getAuthors(session('access_token'));
            return view('book.edit',compact('book','authors'));
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
            'title' =>  'required',
            'isbn' => 'required'
        ]);

        if($validator->fails()){
            return back()
            ->withInput($request->input())
            ->withErrors($validator);
        }

        try{
            $bookService->updateBook(session('access_token'),$id,$request->input('title'),$request->input('isbn'),$request->input('description'),$request->input('author'));
            return redirect()->route('books.index');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookService $bookService, $id)
    {
        try{
            $bookService->deleteBook(session('access_token'),$id);
            return redirect()->route('books.index');
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
}
