<?php

use App\Models\User\Like;

$likes = Like::where('user_id', auth()->id())
	->with('design', 'design.seller', 'design.main_product')
	->get();
