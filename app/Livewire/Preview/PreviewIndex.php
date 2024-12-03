<?php

namespace App\Livewire\Preview;

use App\Helpers\UserRole;
use App\Models\Preview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PreviewIndex extends Component
{
  public $month_ref = '';
  public $cc = '';
  public $q = '';
  public $month = '';
  public $year = '';
  private $ccs;

  protected $paginationTheme = 'tailwind';

  public function render(Request $request)
  {
    $input = $request->validate([
      'q' => 'nullable|string|min:3|max:60',
      'm' => 'nullable|numeric|min:1|max:12',
      'y' => 'nullable|numeric|min:1900|max:2100',
    ]);

    $this->q = isset($input['q']) ? $input['q'] : '';
    $this->month = isset($input['m']) ? $input['m'] : (!isset($input['q']) ? date('m') : '');
    $this->year = isset($input['y']) ? $input['y'] : (!isset($input['q']) ? date('Y') : '');
    $this->month_ref = $this->month . '_' . substr($this->year, -2);

    $query = Preview::query();

    if (!Auth::user()->isAdmin() || !Auth::user()->isManager()) {
      $this->ccs = (new UserRole())->getCc();
      $query->whereIn('cc', $this->ccs);
    }

    if ($this->q) {
      $search = $this->q;
      $query->where(
        function ($q) use ($search) {
          $q->where('cc', 'like', '%' . $search . '%');
          $q->orWhere('week_ref', 'like', '%' . $search . '%');
        }
      );
    } else if (!empty($this->month) && !empty($this->year)) {
      $query->where('month_ref', '=', $this->month_ref);
    }

    $result = $query->paginate(6);
    $result->appends($request->query());

    return view('livewire.preview.index', [
      'q' => $this->q,
      'previews' => $result,
      'realizadas' => 0,
    ]);
  }
}
