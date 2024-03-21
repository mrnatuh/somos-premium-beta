<?php

namespace App\Helpers;

use App\Models\LinkUser;
use App\Models\Preview;
use Illuminate\Support\Facades\Auth;

class UserRole {
    private $role = null;
    
    public function __construct()
    {
        $this->role = Auth::user()->access;
    }    
    
    public function getCc()
    {
        $ccs = [];

        $cc = Auth::user()->cc ? Auth::user()->cc : false;
        
		$user_links = LinkUser::all();

		if ($this->role === 'admin') {
			$previews = Preview::select("cc")->distinct()->get();

			foreach ($previews as $preview) {
				array_push($ccs, $preview->cc);
			}
        } else if ($this->role === 'director') {
			$supervisors = [];

			foreach ($user_links as $link) {
				if ($link->user_id == Auth::user()->id) {
					array_push($supervisors, $link->parent_id);
				}
			}

			foreach ($user_links as $link) {
				if (in_array($link->user_id, $supervisors)) {
					array_push($ccs, $link->parent_id);
				}
			}
			// regra para coordenadores
		} else if ($this->role === 'coordinator') {
			foreach ($user_links as $link) {
				if ($link->user_id == Auth::user()->id) {
					array_push($ccs, $link->parent_id);
				}
			}

			// regra para supervisores
		} else if ($cc) {
            array_push($ccs, $cc);
        }

        return $ccs;
    }
}