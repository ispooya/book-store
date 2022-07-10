<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookRequest;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookReviewResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BooksController extends Controller
{
    public function getCollection(Request $request)
    {
        $sortColumns = [
            'avg_review' => 'reviews_avg_review',
            'title' => 'title',
        ];
        $sortColumn = array_key_exists($request->sortColumn, $sortColumns) ? $sortColumns[$request->sortColumn] : 'id';
        $books = Book::when(
            // Author filter
            $request->has('authors'),
            function ($q) use ($request) {
                $authors = explode(',', $request->authors);
                foreach ($authors as $author) {
                    $q->orWhereHas('authors', function (Builder $q) use ($author) {
                        $q->where('id', $author);
                    });
                }
            }
        )
            ->when(
                // Title filter
                $request->has('title'),
                function ($q) use ($request) {
                    $title = $request->title;
                    $q->where('title', 'LIKE', "%$title%");
                }
            )
            ->orderBy($sortColumn, $request->sortDirection ? $request->sortDirection : 'asc')
            ->with(['authors'])->withAvg('reviews', 'review')->paginate(15);

        return BookResource::collection($books);
    }

    public function post(PostBookRequest $request)
    {
        $book = new Book();
        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->save();
        $book->authors()->attach($request->authors);
        $book->load('authors');
        return new BookResource($book);
    }

    public function postReview(Book $book, PostBookReviewRequest $request)
    {
        $bookReview = new BookReview();
        $bookReview->book_id = $book->id;
        $bookReview->user_id = $request->user()->id;
        $bookReview->review = $request->review;
        $bookReview->comment = $request->comment;
        $bookReview->save();
        $bookReview->load('user');
        return response()->json(['data' => $bookReview], 201);
    }
}
