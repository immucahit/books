<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\BookService\BookService;
use App\Services\BookService\Exceptions\NotFoundException;
use App\Services\BookService\Exceptions\UnauthorizedException;

class UserController extends Controller
{
    public function login(Request $request,BookService $bookService){
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(),[
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validator->fails()){
                return back()->withInput($request->input())->withErrors($validator);
            }

            try{
                $token = $bookService->login($request->input('username'),$request->input('password'));
                session()->flush();
                session(['access_token'=>$token]);
                return redirect()->route('dashboard');
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
        return view('login');
    }

    public function register(Request $request,BookService $bookService){
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(),[
                'displayName' => 'required',
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validator->fails()){
                return back()->withInput($request->input())->withErrors($validator);
            }

            try{
                $bookService->register($request->input('displayName'),$request->input('username'),$request->input('password'));
                return redirect()->route('user.login');
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
        return view('register');
    }

    public function signOut(){
        session()->flush();
        return redirect()->route('user.login');
    }
}
