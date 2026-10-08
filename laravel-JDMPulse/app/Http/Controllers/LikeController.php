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
	// L'utilisateur vient du token et la voiture de l'URL : on ne fait pas confiance au body
	public function like(Request $request, $carId)
	{
		$userId = $request->user()->id;
		$like = Like::where('user_id', $userId)->where('car_id', $carId)->first();
		if ($like) {
			return ApiResponse::error('Déjà aimé');
		}
		$like = Like::create(['user_id' => $userId, 'car_id' => $carId]);
		return ApiResponse::created('Like créé', new LikeResource($like));
	}

	public function unlike(Request $request, $carId)
	{
		$like = Like::where('user_id', $request->user()->id)->where('car_id', $carId)->first();
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