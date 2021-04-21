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
            // get the comment (layer 2) replies
            $replies = $this->replies($comment->id);
                   
		   	array_push($comments_data,[
                "user_name"     => $comment->user_name,
                "id"            => $comment->id,
                "comment"       => $comment->comment,
                "reply"         => $comment->reply,
                "replies"       => $replies,
                "created_at"    => $comment->created_at,
                "date"          => $comment->created_at->diffForHumans() // e.g. 1 second ago
            ]);
        }
           
        $comms = collect($comments_data);
           
	   	return $comms->sortByDesc('created_at');
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
        $comment->date = $comment->created_at->diffForHumans(); // e.g. 1 second ago

        if($comment)
            return [ 
                        "status" => true,
                        "comment" => $comment 
                    ];
    }

    /**
     * List the replies to a comment.
     *
     * @param  integer $id
     * @return collect
     */
    private function replies ($id) {
        $comments = Comment::where('reply_id', $id)->get();
       	$comments_data = [];
       	foreach ($comments as $comment) {
            // get the comment (layer 3) replies
            $replies = $this->replies($comment->id);

            array_push($comments_data,[
                "user_name"     => $comment->user_name,
                "id"            => $comment->id,
                "comment"       => $comment->comment,
                "reply"         => $comment->reply, // level indicator of the reply
                "replies"       => $replies,
                "created_at"    => $comment->created_at,
                "date"          => $comment->created_at->diffForHumans() // e.g. 1 second ago
            ]);
        }

       	$comms = collect($comments_data);
       	return $comms->sortByDesc('created_at');
    }
}
