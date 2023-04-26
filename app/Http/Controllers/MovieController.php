<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieRate;
use Illuminate\Http\Request;
use Auth;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $movies  = Movie::with('user:id,name,lastName', 'likes.user:id,name,lastName', 'hates.user:id,name,lastName')
        ->withCount('likes', 'hates')
        ->when($request->has('sortBy') && $request['sortBy'], function($query) use ($request) {
            if ($request['sortBy'] == 'likes') {
                $query->orderBy('likes_count', 'desc');
            }
            if ($request['sortBy'] == 'hates') {
                $query->orderBy('hates_count', 'desc');
            }
            if ($request['sortBy'] == 'date') {
                $query->orderBy('created_at');
            }
        })
        ->when($request->has('user') && $request['user'], function($query) use ($request) {
            $query->where('user_id', $request['user']);
        })
        ->get();

        return response()->json([
            'data' => $movies,
        ]);
    }

    public function rateMovie(Request $request)
    {
        $request = collect($request->validate([
            'type' => 'required|string|in:like,hate',
            'movie_id' => 'required|integer',
        ]));

        $movie = Movie::where('id', $request['movie_id'])
        ->firstOrFail();

        $like = $request['type'] == 'like' ? 1 : 0;

        $movieRate = MovieRate::where('user_id', Auth::id())
        ->where('movie_id', $request['movie_id'])
        ->first();

        if (!$movieRate) {
            // no rate yes for this user
            // add the vote
            $movieRate = new movieRate;
            $movieRate->user_id = Auth::id();
            $movieRate->movie_id = $movie->id;
            $movieRate->like = $like;
            $movieRate->save();
        } else {
            if ($movieRate->like == $like) {
                return response()->json([
                    'message' => 'You have already voted for this movie',
                ]);
            } else {
                $movieRate->like = $like;
                $movieRate->update();
            }
        }

        return response()->json([
            'message' => 'Voted',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request = collect($request->validate([
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:2000',
        ]));

        $movie = new Movie;
        $movie->title = $request['name'];
        $movie->description = $request['description'];
        $movie->user_id = Auth::id();
        $movie->save();

        return response()->json([
            'message' => 'Movie added',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
