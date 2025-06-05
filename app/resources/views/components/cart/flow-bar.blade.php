@php

	use App\Helpers\EcommerceHelper;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Route;

	$orderStep = cart()->step;
	$pageStep = EcommerceHelper::stepFromTag(explode('cart.', request()->route()->getName())[1]);
@endphp

@pushOnce('styles')
	<style>
		#flow-bar {
			--_size: min(2rem, 8vw);
			--_inactive-color: var(--color-neutral-300);
			display: grid;
			grid-template-columns: var(--_size) 1fr var(--_size) 1fr var(--_size) 1fr var(--_size);
			margin-inline: 7%;
			margin-block: 3rem 5rem;
			align-items: center;
		}

		.flow-step {
			position: relative;
			cursor: default;
		}

		.flow-circle {
			border: 1px solid var(--_inactive-color);
			border-radius: 50%;
			aspect-ratio: 1 / 1;
		}

		.flow-num {
			color: var(--_inactive-color);
			font-size: 0.9rem;
		}

		.flow-line {
			background-color: var(--_inactive-color);
			height: 1px;
		}

		.flow-txt {
			margin-top: 5px;
			color: var(--_inactive-color);
			position: absolute;
			top: 100%;
			left: 50%;
			transform: translateX(-50%);
		}

		.flow-icon {
			width: 45%;
			aspect-ratio: 1 / 1;
		}

		.flow-icon svg {
			fill: var(--color-primary-400);
		}

		.flow-line.active {
			background-color: var(--color-primary-400);
		}

		.flow-step.active .flow-circle {
			border-color: var(--color-primary-400);
		}

		.flow-step.active .flow-num {
			color: var(--color-primary-400);
		}

		.flow-step.active .flow-txt {
			color: var(--color-primary-400);
		}

		.flow-step.this .flow-circle {
			background-color: var(--color-primary-400);
		}

		.flow-step.this .flow-num {
			color: #fff;
		}

		.flow-step.this .flow-icon svg {
			fill: #fff;
		}
	</style>
@endpushOnce

<section id="flow-bar">

	@foreach (config('ecommerce.cart.steps') as $step => $stepData)
		@if ($step > 1)
			{{-- Line --}}
			<div class="flow-line {{ $step <= $orderStep ? 'active' : '' }}"></div>
		@endif

		{{-- Step --}}
		<div class="flow-step {{ $step <= $orderStep ? 'active' : '' }} {{ $step == $pageStep ? 'this' : '' }}">

			{{-- Number --}}
			<a
				class="flow-circle center-items"
				@if ($step <= $orderStep && $step != $pageStep && !($stepData['tag'] == 'access' && Auth::check())) href="{{ route('cart.' . $stepData['tag']) }}" @endif
			>

				@if ($step < $pageStep)
					<div class="flow-icon icon-box grid place-items-center">
						{!! embed_icon('correct') !!}
					</div>
				@else
					<p class="flow-num">{{ $step }}</p>
				@endif

			</a>

			{{-- Text --}}
			<p class="flow-txt">{{ $stepData['label'] }}</p>

		</div>
	@endforeach

</section>
