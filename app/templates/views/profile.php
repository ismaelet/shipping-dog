{% extends main %}

{% block main %}

<?php include_once('profile/head.php'); ?>

<div class="main-wrap">
	<div id="body" class="tbl mar-v-3">

		<?php include_once('profile/nav.php'); ?>

		<div id="content" class="cell">

			{% place content %}

		</div>
	</div>
</div>

{% end main %}