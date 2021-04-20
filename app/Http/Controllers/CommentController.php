<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page_id)
    {
        $comments = Comment::where('page_id', $page_id)->get();
		$comments_data = [];
		foreach ($comments as $comment) {
            $replies = $this->replies($comment->id);
                   
		   	array_push($comments_data,[
                "user_name"     => $comment->user_name,
                "id"            => $comment->id,
                "comment"       => $comment->comment,
                "reply"         => $comment->reply,
                "replies"       => $replies,
                "created_at"    => $comment->created_at,
                "date"          => $comment->created_at->diffForHumans()
            ]);
        }
           
        $comms = collect($comments_data);
           
	   	return $comms->sortByDesc('created_at');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
            'reply' => 'required',
            'reply_id' => 'filled',
            'page_id' => 'filled',
            'user_name' => 'required',
        ]);

        $comment = Comment::create($request->all());

        $comment->user_name = $comment->user_name;
        $comment->date = $comment->created_at->diffForHumans();

        if($comment)
            return [ 
                        "status" => true,
                        "comment" => $comment 
                    ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }

    public function replies ($id) {
        $comments = Comment::where('reply_id', $id)->get();
       	$comments_data = [];
       	foreach ($comments as $comment) {
            $replies = $this->replies($comment->id);

            array_push($comments_data,[
                "user_name"     => $comment->user_name,
                "id"            => $comment->id,
                "comment"       => $comment->comment,
                "reply"         => $comment->reply,
                "replies"       => $replies,
                "created_at"    => $comment->created_at,
                "date"          => $comment->created_at->diffForHumans()
            ]);
        }

       	$comms = collect($comments_data);
       	return $comms->sortByDesc('created_at');
    }
}
