<?php

namespace App\Livewire\Preview;

use App\Helpers\UserRole;
use App\Models\Preview;
use Illuminate\Http\Request;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewDoneIndex extends Component
{
	public $previews = [];
	public $month_ref = '';

	#[On('month-scroll-updated')]
	public function monthUpdated(string $month_ref = '')
	{
		$this->month_ref = $month_ref;
	} 

	public function render(Request $request)
	{		
		if (!$this->month_ref) {
			$req_month_ref = $request->input('month_ref');

			if (!empty(trim($req_month_ref))) {
				$split = explode("_", $req_month_ref);
				if (isset($split[0]) && isset($split[1])) {
					$this->month_ref = "{$split[0]}_{$split[1]}";
				}
			} else {
				$this->month_ref = date('m') . '_' . substr(date('Y'), -2);
			}
		}

		$ccs = (new UserRole())->getCc();

		$this->previews = Preview::whereIn('cc', $ccs)
			->where('month_ref', '=', $this->month_ref)
			->where('status', 'validado')
			->get();

		$this->dispatch('render-preview-month');	

		return view('livewire.preview.index', [
			'previews' => $this->previews,
			'realizadas' => 1,
		]);
	}
}
