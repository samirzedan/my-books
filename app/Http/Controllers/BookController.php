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

    public function changeCurrentPage(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'current_page' => 'required|numeric|integer|min:1',
            ]);

            $book = Book::findOrFail($id);

            $book->update([
                'current_page' => $request->current_page,
            ]);

            return response()->json([
                'message' => 'Book current page changed successfully',
                'data' => $book,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    } // ainda posso inserir um número de páginas maior do que a quantidade total de páginas do livro

    public function changeCurrentPage2(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'current_page' => 'required|numeric|integer|min:1',
            ]);

            $book = Book::findOrFail($id);

            if ($request->current_page > $book->total_pages) {
                return response()->json([
                    'message' => 'The current page cannot be greater than the total number of pages',
                ], 400);
            }

            $book->update([
                'current_page' => $request->current_page,
            ]);

            return response()->json([
                'message' => 'Book current page changed successfully',
                'data' => $book,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    } // agora não posso inserir um número de páginas maior do que a quantidade total de páginas do livro, porém ainda não altera os campos 'started_at' e 'finished_at' da tabela 'books'

    public function changeCurrentPage3(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'current_page' => 'required|numeric|integer|min:1',
            ]);

            $book = Book::findOrFail($id);

            if ($request->current_page > $book->total_pages) {
                return response()->json([
                    'message' => 'The current page cannot be greater than the total number of pages',
                ], 400);
            }

            $book->current_page = $request->current_page;

            if ($book->started_at == null) {
                $book->started_at = now();
            }

            if ($request->current_page == $book->total_pages) {
                $book->finished_at = now();
            }

            $book->save();

            return response()->json([
                'message' => 'Book current page changed successfully',
                'data' => $book,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    } // agora não posso inserir um número de páginas maior do que a quantidade total de páginas do livro, e também altera os campos 'started_at' e 'finished_at' da tabela 'books'
}
