<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Own;
use App\Http\Requests\LikeRequest;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
use App\Http\Librairies\ApiResponse;

class LikeController extends Controller
{
	public function like(Request $request)
	{
		$like = Like::where('user_id', $request->user_id)->where('car_id', $request->car_id)->first();
		if ($like) {
			return ApiResponse::error('Déjà aimé');
		}
		$like = Like::create($request->all());
		return ApiResponse::created('Like créé', new LikeResource($like));
	}

	public function unlike(Request $request)
	{
		$like = Like::where('user_id', $request->user_id)->where('car_id', $request->car_id)->first();
		if (!$like) {
			return ApiResponse::error('Pas aimé');
		}
		$like->delete();
		return ApiResponse::success('Like supprimé');
	}	public function getLikesByCar(Request $request)
	{
		$likes = Like::all();
		$likesByCarData = [];
		
		// Regrouper les likes par car_id et compter
		$groupedLikes = $likes->groupBy('car_id');
		
		foreach($groupedLikes as $carId => $carLikes) {
			$likesByCarData[] = [
				'car_id' => $carId,
				'likes_count' => $carLikes->count()
			];
		}
		
		return ApiResponse::success('Nombre de likes par voiture', $likesByCarData);
	}

	public function getLikesByUserId(Request $request, $userId)
	{
		$likes = Like::where('user_id', $userId)->get();
		return ApiResponse::success("Likes de l'utilisateur", LikeResource::collection($likes));
	}
}