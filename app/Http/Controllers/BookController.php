<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        try {
            $books = Book::all();

            return response()->json([
                'message' => 'Books retrieved successfully',
                'data' => $books,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);

            return response()->json([
                'message' => 'Book retrieved successfully',
                'data' => $book,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|string',
                'author' => 'required|string',
                'total_pages' => 'required|numeric|integer|min:1',
            ]);

            // $book = Book::create($request->all()); // Funciona, mas não é o ideal, pois permite que o usuário envie dados que não devem ser salvos no banco de dados.

            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'total_pages' => $request->total_pages,
            ]);

            return response()->json([
                'message' => 'Book created successfully',
                'data' => $book,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'title' => 'required|string',
                'author' => 'required|string',
                'total_pages' => 'required|numeric|integer|min:1',
            ]);

            $book = Book::findOrFail($id);

            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'total_pages' => $request->total_pages,
            ]);

            return response()->json([
                'message' => 'Book updated successfully',
                'data' => $book,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);

            $book->delete();

            return response()->json([
                'message' => 'Book deleted successfully',
                'data' => $book,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
