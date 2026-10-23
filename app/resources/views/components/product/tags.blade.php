<!-- Tags -->
<?php if ($product->design?->tags?->tags) { ?>

<section
	id="tag-sec"
	class=""
>
	<div
		id="tag-block"
		class="main-wrap pt-12 text-center"
	>

		<?php
			$tags = explode(',', $product->design->tags->tags);

			foreach ($tags as $tag) {
				$tag = str_replace('#', '', $tag);
			?>

		<a href="{{ route('search', ['search' => $tag]) }}">
			<p class="mx-2 my-1 inline-block cursor-pointer rounded-[3px] bg-surface-500 px-4 py-2.5 font-medium text-neutral-heading transition-colors hover:bg-primary-400"><?= $tag ?></p>
		</a>

		<?php } ?>

	</div>
</section>

<?php } ?>
