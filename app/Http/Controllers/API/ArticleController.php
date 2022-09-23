<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\GetArticlesRequest;
use App\Http\Requests\CreateVote;
use App\Models\Article;
use App\Models\Vote;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json([
            'articles' => Article::where('user_id', $request->user('api')->id)
                ->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateArticleRequest $request)
    {
        $validatedDate = $request->validated();

        try {
            $article = new Article;

            $article->title = $validatedDate['title'];
            $article->description = $validatedDate['description'];
            $article->category_id = $validatedDate['category_id'];
            $article->user_id = $request->user('api')->id;

            $article->save();
        } catch(\Exception $e) {
            return $this->responseServerError($e);
        }

        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, $id)
    {
        $validatedDate = $request->validated();

        try {
            $article = Article::findOrFail($id);
            $article->update($validatedDate);
        } catch(\Exception $e) {
            return $this->responseServerError($e);
        }

        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json($article);
    }

    /**
     * @param CreateVote $request
     * @param Integer $articleId
     *
     * @return \Illuminate\Http\Response
     */
    public function votes(CreateVote $request, $articleId)
    {
        $validatedDate = $request->validated();

        $article = Article::findOrFail($articleId);

        $vote = Vote::where('user_id', $request->user('api')->id)
            ->where('article_id', $article->id)
            ->first();

        try {
            if (!$vote) { // add
                $newVote = new Vote;

                $newVote->status = $validatedDate['status'];
                $newVote->article_id = $article->id;
                $newVote->user_id = $request->user('api')->id;

                $newVote->save();

                return response()->json($newVote);
            } else { // update
                $vote->update([
                    'status' => $validatedDate['status']
                ]);
            }
        } catch (\Exception $e) {
            return $this->responseServerError($e);
        }

        return response()->json($vote);
    }

    /**
     * @return GetArticlesRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function allArticles(GetArticlesRequest $request)
    {
        $validatedDate = $request->validated();

        try {
            $articles = Article::query();

            if ($validatedDate['title'] ?? false) {
                $articles
                    ->orWhere('title', 'like', '%' . $validatedDate['title'] . '%');
            }

            if ($validatedDate['description'] ?? false) {
                $articles
                    ->orWhere('description', 'like', '%' . $validatedDate['description'] . '%');
            }

            if ($validatedDate['category_id'] ?? false) {
                $articles
                    ->orWhere('category_id', $validatedDate['category_id']);
            }

            $articles = $articles
                ->orderByDesc('created_at')
                ->offset($validatedDate['offset'])
                ->limit($validatedDate['limit'])
                ->get();
        } catch (\Exception $e) {
            return $this->responseServerError($e);
        }

        return response()->json([
            'count' => Article::count(),
            'articles' => $articles
        ]);
    }

    /**
     * @return Response
     */
    public function getTopCategories() {
        $minNumberArticles = 2;
        $limit = 5;

        $categories = Category::has('articles', '>=', $minNumberArticles)
            ->with('articles')
            ->withCount('articles')
            ->limit($limit)
            ->orderByDesc('articles_count')
            ->get();

        return response()->json($categories);
    }

    /**
     * @param String
     * @param String
     *
     * @return Response 500
     */
    public function responseServerError($e, $responseMessage = 'The server is temporarily not responding')
    {
        \Log::debug($e->getMessage());

        return response()->json(['message' => $responseMessage], 500);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
}
