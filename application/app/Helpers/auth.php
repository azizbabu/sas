<?php

function isAdmin()
{
	if(!Auth::check()){
		return false;
	}

	return Auth::user()->role == 'admin' ? true : false;
}

