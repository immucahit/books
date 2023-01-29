<?php

namespace App\Http\Controllers;

use App\Services\BookService\BookService;
use Illuminate\Http\Request;
use App\Services\BookService\Exceptions\NotFoundException;
use App\Services\BookService\Exceptions\UnauthorizedException;

class DashboardController extends Controller
{
    public function index(BookService $bookService){
        try{
            $authors = collect($bookService->getAuthors(session('access_token'))['data'])->sortByDesc('id')->take(5)->toArray();
            $books = collect($bookService->getBooks(session('access_token'))['data'])->sortByDesc('id')->take(5)->toArray();
            return view('dashboard',compact('authors','books'));
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
