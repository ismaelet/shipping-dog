<?php

namespace Legacy\Classes\ViewManager\Ecommerce;

use App\Helpers\EcommerceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CartFlowBar {

	public static function render() {
		$order = cart();
		$pageStepTag = explode('.', Route::currentRouteName())[1] ?? null;
		$pageStep = EcommerceHelper::stepFromTag($pageStepTag);
		$orderItemstep = $order->step;

		ob_start();
?>

		<section id="flow-bar">

			<?php foreach (config('ecommerce.cart.steps') as $step => $stepData) { ?>

				<!-- Line -->
				<?php if ($step > 1) { ?>

					<div class="flow-line <?= $step <= $orderItemstep ? 'active' : '' ?>"></div>

				<?php } ?>

				<!-- Step -->
				<div class="flow-step <?= $step <= $orderItemstep ? 'active' : '' ?> <?= $step == $pageStep ? 'this' : '' ?>">

					<!-- Number -->
					<a <?= $step <= $orderItemstep && $step != $pageStep && !($stepData['tag'] == 'access' && Auth::check()) ? 'href="' . route('cart.' . $stepData['tag']) . '"' : '' ?> class="flow-circle center-items">

						<?php if ($step < $pageStep) { ?>

							<div class="flow-icon icon-box">
								<?= embed_icon('correct') ?>
							</div>

						<?php } else { ?>

							<p class="flow-num"><?= $step ?></p>

						<?php } ?>

					</a>

					<!-- Text -->
					<p class="flow-txt"><?= $stepData['label'] ?></p>

				</div>

			<?php } ?>

		</section>

<?php
		return ob_get_clean();
	}
}
