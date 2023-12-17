{% block content %}

<div class="grid4 grid3-1440w grid2-1024w grid1-768w grid-pad1">
	<div class="panel">

		<h1 class="title">Visibilità del sito:</h1>

		<form class="sett-slid" method="POST">
			<label class="sett-slid-dot <?php if (!$public) echo 'active';?>">
				<input type="checkbox" class="hide" name="cbx_public[]" value="0" <?php if (!$public) echo 'checked';?>>
				<p class="sett-slid-txt">Nascosto</p>
			</label>
			
			<div class="sett-slid-line"></div>

			<label class="sett-slid-dot <?php if ($public) echo 'active';?>">
				<input type="checkbox" class="hide" name="cbx_public[]" value="1" <?php if ($public) echo 'checked';?>>
				<p class="sett-slid-txt">Visibile</p>
			</label>
			<div class="sett-slid-focus no-trans bgr-c1"></div>

			<button type="submit" name="public" class="hide"></button>
		</form>

	</div>
</div>

{% end content %}