<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class UserDashboard extends Controller {

	/**
	 * Render the user dashboard
	 */
	public function index() {
		View::share('dashboardLocationTag', 'recap');

		return view('user-dashboard.recap')
			->with('locationTag', 'user-dashboard')

			->with('title', Auth::user()->name . ' - Profilo');
	}

	/**
	 * Render the courses page
	 */
	public function indexCourses() {
		View::share('dashboardLocationTag', 'courses');

		return view('user-dashboard.courses')
			->with('locationTag', 'user-dashboard')
			->with('title', 'Profilo - Videocorsi');
	}
}
