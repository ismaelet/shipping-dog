<?php

function element_search($search) {?>

	<div class="ajax-search" data-url="<?= $search['url'];?>" data-filter="<?= $search['filter'] ?? '';?>">
						
		<input type="hidden" class="ajax-search-id" name="<?= $search['name']?>" <?php if (isset($search['required'])) echo 'required';?>>

		<input type="text" class="inp ajax-search-txt" name="<?= $search['name_show']?>" placeholder="<?= $search['pholder']?>" <?php if (isset($search['required_show'])) echo 'required';?>>

		<div class="ajax-search-ddown">
			<div class="ajax-search-box"></div>
		</div>
	</div>

<?php }