<?php

/**
 * Check if the user is an admin
 */
function admin(): bool {
	return auth()->user() && auth()->user()->isAdmin();
}

/**
 * Check if the user is a supervisor
 */
function supervisor(): bool {
	return auth()->user() && auth()->user()->isSupervisor();
}
