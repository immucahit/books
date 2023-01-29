<?php

namespace App\Services\BookService;

use Illuminate\Support\Facades\Http;
use App\Services\BookService\Exceptions\NotFoundException;
use App\Services\BookService\Exceptions\UnauthorizedException;
use App\Services\BookService\Exceptions\BadRequestException;
use App\Services\BookService\Exceptions\UnexceptedErrorException;

class BookService{

    public $endpoint;

    public function __construct($endpoint){
        $this->endpoint = $endpoint;
    }

    public function login($username,$password){
        $token = null;
        $response = Http::post($this->endpoint.'/login',[
            'username' => $username,
            'password' => $password
        ]);
        if($this->checkResponse($response)){
            $token = $response->json('access_token');
        }
        return $token;
    }

    public function register($displayName,$username,$password){
        $response = Http::post($this->endpoint.'/register',[
            'displayName' => $displayName,
            'username' => $username,
            'password' => $password
        ]);
        return $this->checkResponse($response);
    }

    public function getAuthors($token){
        $response = Http::withToken($token)->get($this->endpoint.'/authors');
        if($this->checkResponse($response)){
            return $response->json();
        }
    }

    public function addAuthor($token,$name){
        $response = Http::withToken($token)->post($this->endpoint.'/authors',[
            'name' => $name
        ]);
        return $this->checkResponse($response);
    }

    public function getAuthor($token,$id){
        $response = Http::withToken($token)->get($this->endpoint.'/authors/'.$id);
        if($this->checkResponse($response)){
            return $response->json();
        }
    }

    public function updateAuthor($token,$id,$name){
        $response = Http::withToken($token)->put($this->endpoint.'/authors/'.$id,[
            'name' => $name
        ]);
        return $this->checkResponse($response);
    }

    public function getBooks($token){
        $response = Http::withToken($token)->get($this->endpoint.'/books');
        if($this->checkResponse($response)){
            return $response->json();
        }
    }

    public function addBook($token,$title,$isbn,$description=null,$authorId=null){
        $response = Http::withToken($token)->post($this->endpoint.'/books',[
            'authorId' => $authorId,
            'title' => $title,
            'isbn' => $isbn,
            'description' => $description
        ]);
        return $this->checkResponse($response);
    }

    public function getBook($token,$id){
        $response = Http::withToken($token)->get($this->endpoint.'/books/'.$id);
        if($this->checkResponse($response)){
            return $response->json();
        }
    }

    public function updateBook($token,$id,$title,$isbn,$description=null,$authorId=null){
        $response = Http::withToken($token)->put($this->endpoint.'/books/'.$id,[
            'authorId' => $authorId,
            'title' => $title,
            'isbn' => $isbn,
            'description' => $description
        ]);
        return $this->checkResponse($response);
    }

    public function deleteBook($token,$id){
        $response = Http::withToken($token)->delete($this->endpoint.'/books/'.$id);
        return $this->checkResponse($response);
    }

    protected function checkResponse($response){
        switch($response->status()){
            case 200:
            case 201:
                return true;
                break;
            case 401:
                throw new UnauthorizedException();
                break;
            case 404:
                throw new NotFoundException();
                break;
            case 400:
                throw new BadRequestException($response->json('message'),$response->status());
                break;
            default:
                throw new UnexceptedErrorException('Unexcepted Error',$response->status());
        }
    }

}