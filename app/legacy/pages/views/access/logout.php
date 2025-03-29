{% block main %}

<form id="form" class="form" method="POST">
	<div class="form-head">
		<h1 class="form-title">Logout</h1>
	</div>

	<div class="form-body">
		<p class="pad-v-2 text-center "><?= ('Are you sure you want to leave?') ?></p>
	</div>

	<div class="form-footer">

		<button type="submit" name="submit" class="pbtn">Log Out</button>
		<a href="/"><button type="button" class="sbtn"><?= ('Back') ?></button></a>

	</div>
</form>

{% end main %}