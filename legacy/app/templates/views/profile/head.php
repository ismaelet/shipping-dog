<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::find(Auth::id());

?>

<div id="slider" class="">
	<div id="bgr" class="bgr bgr-c2" <?= temp_bg($user->cover(1024)) ?>></div>
</div>

<div id="slider-data" class="main-wrap text-center">

	<img src="<?= public_asset($user->icon()) ?>" id="pic" class="img-fill push-center">

	<h1 id="username" class="txt-25rem"><?= $user->username ?></h1>

	<p class="mt-2">
		<?= $user->first_name ?>
		<?= empty($user->last_name) ? '' : ' ' . $user->last_name ?>
	</p>

</div>