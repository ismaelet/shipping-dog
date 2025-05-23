<!-- Tags -->
<?php if ($product->design?->tags?->tags) { ?>

	<section id="tag-sec" class="">
		<div id="tag-block" class="main-wrap text-center pad-t-3">

			<?php
			$tags = explode(',', $product->design->tags->tags);

			foreach ($tags as $tag) {
				$tag = str_replace('#', '', $tag);
			?>

				<a href="<?= client_url('/search?search=' . $tag) ?>">
					<p class="tag inlb"><?= $tag ?></p>
				</a>

			<?php } ?>

		</div>
	</section>

<?php } ?>