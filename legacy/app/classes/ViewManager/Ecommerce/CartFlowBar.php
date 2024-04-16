<?php

namespace ViewManager\Ecommerce;

use ViewOld;


use function Ecommerce\ecommerce_config;

class CartFlowBar {

	public static function render(int $currentStepId = null, object $cart = null) {
		$cart = $cart ?: \App\Helpers\App::cartOld();
		$currentStepId = $currentStepId ?: (ViewOld::get('currentStepId') ?: $cart->stepId);

		ob_start();
?>

		<section id="flow-bar">

			<?php foreach (ecommerce_config('cart-page')['steps'] as $stepId => $stepData) { ?>

				<!-- Line -->
				<?php if ($stepId > 1) { ?>

					<div class="flow-line <?= $stepId <= $cart->stepId ? 'active' : '' ?>"></div>

				<?php } ?>

				<!-- Step -->
				<div class="flow-step <?= $stepId < $cart->stepId || $stepId == $currentStepId ? 'active' : '' ?> <?= $stepId == $currentStepId ? 'this' : '' ?>">

					<!-- Number -->
					<a <?= $stepId <= $cart->stepId && $stepId != $currentStepId ? 'href="' . client_url('/cart?step-id=' . $stepId) . '"' : '' ?> class="flow-circle center-items">

						<?php if ($stepId < $currentStepId) { ?>

							<div class="flow-icon icon-box">
								<?= embed_icon('correct') ?>
							</div>

						<?php } else { ?>

							<p class="flow-num"><?= $stepId ?></p>

						<?php } ?>

					</a>

					<!-- Text -->
					<p class="flow-txt"><?= $stepData[0] ?></p>

				</div>

			<?php } ?>

		</section>

<?php
		return ob_get_clean();
	}
}
