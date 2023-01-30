<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use App\Events\Logging;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->input('user');
        return Cache::remember('user:id:'.$user.':book',now()->addHour(),function() use ($user){
            return BookResource::collection(Book::with('author')->byUser($user)->isDeleted(false)->get());
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
            'title' => 'required',
            'isbn' => 'required'
        ]);

        if($validator->fails()){
            return response()
                ->json([
                    'message' => 'Lütfen tüm alanları doldurunuz!'
                ],400);
        }

        $book = Book::create([
            'user_id' => $request->input('user'),
            'author_id' => $request->input('authorId'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'isbn' => $request->input('isbn')
        ]);

        Cache::forget('user:id:'.$user.':book');

        Logging::dispatch($user,'insert',$book);

        return new BookResource($book);
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
            'title' => 'required',
            'isbn' => 'required'
        ]);

        if($validator->fails()){
            return response()
                ->json([
                    'message' => 'Lütfen tüm alanları doldurunuz!'
                ],400);
        }

        $book = Book::isDeleted(false)->find($id);
        if(!$book){
            return response()->noContent(404);
        }

        if(!$book->can($user)){
            return response()->noContent(401);
        }

        $book->author_id = $request->input('authorId');
        $book->title = $request->input('title');
        $book->description = $request->input('description');
        $book->isbn = $request->input('isbn');

        if($book->save()){
            Cache::forget('user:id:'.$user.':book');
            Cache::forget('user:id:'.$user.':book:id:'.$id);
        }

        Logging::dispatch($user,'update',$book);

        return new BookResource($book);
    }

    public function show(Request $request,$id)
    {
        $user = $request->input('user');
        return Cache::remember('user:id:'.$id.':book:id:'.$id,now()->addHour(),function() use ($user,$id){
            $book = Book::byUser($user)->isDeleted(false)->find($id);
            if(!$book){
                return response()->noContent(404);
            }
            return new BookResource($book);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->input('user');
        $book = Book::isDeleted(false)->find($id);
        if(!$book){
            return response()->noContent(404);
        }

        if(!$book->can($user)){
            return response()->noContent(401);
        }

        $book->is_deleted = true;

        if($book->save()){
            Cache::forget('user:id:'.$user.':book');
            Cache::forget('user:id:'.$user.':book:id:'.$id);
        }

        return response()->noContent(200);
    }

    public function updatePrice(){
        $exchange = 'default';
        $queue = 'update_book_price';

        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'), env('RABBITMQ_VHOST'));
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, 'direct', false, true, false);

        $channel->queue_bind($queue, $exchange);

        $books = Book::isDeleted(false)->get();
        foreach($books as $book){
            $message = new AMQPMessage(json_encode([$book->id]), array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            $channel->basic_publish($message, $exchange);
        }

        $channel->close();
        $connection->close();
    }
}
