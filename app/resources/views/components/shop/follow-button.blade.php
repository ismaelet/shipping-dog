@props(['seller'])

<?php

use App\Models\Ecommerce\Seller;
use Illuminate\Support\Facades\Auth;

?>

@pushOnce('styles')
	<style>
		.follow-button {
			display: inline-block;
			background-color: #739fb1;
			border: none;
			border-radius: 10px;
			padding-block: 0.5em;
			width: 6rem;
			color: #fff;
			font-size: var(--font-size-300);
			font-family: Arial;
			letter-spacing: 0.03rem;
			text-align: center;
			cursor: pointer;
			transition: background-color 0.2s ease-in-out;

			& span {
				display: none;
			}

			&[data-followed="false"] {
				& span:nth-child(1) {
					display: block;
				}
			}

			&[data-followed="true"]:not(:hover) {
				& span:nth-child(2) {
					display: block;
				}
			}

			&[data-followed="true"]:hover {
				& span:nth-child(3) {
					display: block;
				}
			}

			&:hover {
				background-color: #5c7a8c;
			}
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		function storeFollower(target) {
			const button = target.closest('[data-seller-id]');
			const sellerId = button.dataset.sellerId;

			axios.post('/api/followers', {
					user_id: sellerId,
					follower_id: {{ Auth::id() }}
				})
				.then(response => {
					const followId = response.data.follower.id;
					button.dataset.followId = followId;

				})
				.catch(error => {});
		}

		function destroyFollow(target) {
			const button = target.closest('[data-follow-id]');
			const followId = button.dataset.followId;

			axios.delete(`/api/followers/${followId}`)
				.then(response => {})
				.catch(error => {});
		}
	</script>
@endpushOnce

<template>

	@if ($seller)
		@if (Auth::check())
			@if ($seller->id != Auth::id())
				@php
					$followId = $seller->auth_follower?->id;
				@endphp

				<div
					class="follow-button"
					x-data="{ followed: {{ $followId ? 'true' : 'false' }} }"
					:data-followed="followed ? 'true' : 'false'"
					@click="followed = !followed; followed ? storeFollower($event.target) : destroyFollow($event.target);"
					data-seller-id="{{ $seller->id }}"
					data-follow-id="{{ $followId }}"
				>
					<span>segui</span>
					<span>seguendo</span>
					<span>non seguire</span>
				</div>
			@endif
		@else
			<a
				href="{{ client_url('/login?url=' . $_SERVER['REQUEST_URI']) }}"
				class="follow-button"
			>segui</a>
		@endif
	@endif

</template>
