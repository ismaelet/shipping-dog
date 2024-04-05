<?php

if (auth()->check()) redirect_old(sys_config('logged-redirect'));
