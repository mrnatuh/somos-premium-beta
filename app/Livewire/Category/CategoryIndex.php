<?php

namespace App\Livewire\Category;

use App\Models\Option;
use App\Models\Preview;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class CategoryIndex extends Component
{
	public $edit = true;

	public function render()
	{
		$sess = session('preview');

		$is_page_realizadas = isset($sess['realizadas']) && $sess['realizadas'];

		$preview = Preview::where([
			'cc' => $sess['cc'],
			'week_ref' => $sess['week_ref'],
		])->first();

		$logs = $preview->logs ? unserialize($preview->logs) : [];
		$last_log = sizeof($logs) ? $logs[sizeof($logs) - 1] : [];

		$filename = "previews/{$preview->cc}_{$preview->week_ref}.json";
		$preview_is_exported = Storage::exists($filename);

		if ($preview_is_exported) {
			$data = json_decode(Storage::get($filename), true);
			$this->edit = false;
		} else {
			$this->edit = $is_page_realizadas ? false : true;
			$data = $preview->toArray();
		}

		return view('livewire.category.category', [
			'id' => $data['id'],
			'cc' => session('preview')['cc'],
			'weekref' => session('preview')['week_ref'],
			'published_at' => $data['published_at'],
			'approved_at' => $data['approved_at'],
			'logs' => $logs,
			'last_log' => $last_log,
			'edit' => $this->edit,
			'realizadas' => $is_page_realizadas,
		]);
	}
}
