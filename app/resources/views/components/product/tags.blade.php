<!-- Tags -->
<?php if ($product->design?->tags?->tags) { ?>

<section
	id="tag-sec"
	class=""
>
	<div
		id="tag-block"
		class="main-wrap pad-t-3 text-center"
	>

		<?php
			$tags = explode(',', $product->design->tags->tags);

			foreach ($tags as $tag) {
				$tag = str_replace('#', '', $tag);
			?>

		<a href="{{ route('search', ['search' => $tag]) }}">
			<p class="tag inlb"><?= $tag ?></p>
		</a>

		<?php } ?>

	</div>
</section>

<?php } ?>
