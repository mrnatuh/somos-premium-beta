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
		$realizadas = session('preview')['realizadas'] ?? 0;

		$preview = Preview::where([
			'cc' => session('preview')['cc'],
			'week_ref' => session('preview')['week_ref'],
		])->first();

		$logs = isset($preview->logs) ? unserialize($preview->logs) : [];
		$last_log = sizeof($logs) ? $logs[sizeof($logs) - 1] : [];

		$filename = "previews/{$preview->cc}_{$preview->week_ref}.json";
		$preview_is_exported = Storage::exists($filename);

		if ($preview_is_exported) {
			$data = json_decode(Storage::get($filename), true);
			$this->edit = false;
		} else {
			$this->edit = $realizadas ? false : true;
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
			'realizadas' => $realizadas,
		]);
	}
}
