<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookRequest;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookReviewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
    public function getCollection(Request $request)
    {
        $books = Book::with(['authors'])->paginate(15);
        $books = collect($books);
        $books->put(
            'links',
            [
                "first" => $books->get("first_page_url"),
                "last" => $books->get("last_page_url"),
                "next" => $books->get("next_page_url"),
                "prev" => $books->get("prev_page_url"),

            ]
        );
        $books->put(
            'meta',

            [
                'current_page' => $books->get("current_page"),
                'from' => $books->get("from"),
                'last_page' => $books->get("last_page"),
                'path' => $books->get("path"),
                'per_page' => $books->get("per_page"),
                'to' => $books->get("to"),
                'total' => $books->get("total"),
            ]
        );
        $books->forget([
            'current_page', 'first_page_url', 'from', 'last_page', 'last_page_url', 'next_page_url', 'path', 'per_page',
            'prev_page_url', 'to', 'total'
        ]);
        return response()->json($books->all());
    }

    public function post(PostBookRequest $request)
    {
        //@todo code here
    }

    public function postReview(Book $book, PostBookReviewRequest $request)
    {
        //@todo code here
    }
}
