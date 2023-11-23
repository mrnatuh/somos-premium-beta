<?php

namespace App\Http\Controllers;

use App\Models\Preview;
use Illuminate\Http\Request;

class PreviewStatusController extends Controller
{
    public function __invoke(Request $request)
    {
        $input = $request->validate([
            'cc' => 'required',
            'weekref' => 'required',
            'status' => 'required',
        ]);

        $preview = Preview::where([
            'cc' => $input['cc'],
            'week_ref' => $input['weekref'],
        ])->first();

        if (!$preview) {
            return response()->json([], 404);
        }

        $logs = isset($preview->logs) ? unserialize($preview->logs) : [];
        $last_log = sizeof($logs) ? $logs[sizeof($logs) - 1] : null;

        if ($input['status'] == 'publish') {

            $preview->status = 'em-analise';
            $preview->published_at = now();

            array_push($logs, [
                'status_from' => $last_log ? $last_log['status'] : '',
                'status' => $preview->status,
                'user_id' => auth()->user()->id,
                'user_name' => auth()->user()->name,
                'timestamp' => now(),
            ]);

            $preview->logs = serialize($logs);

            $preview->update();

        } else if($input['status'] == 'reprove') {

            $preview->status = 'recusado';
            $preview->published_at = null;

            array_push($logs, [
                'status_from' => $last_log ? $last_log['status'] : '',
                'status' => $preview->status,
                'user_id' => auth()->user()->id,
                'user_name' => auth()->user()->name,
                'timestamp' => now(),
            ]);

            $preview->logs = serialize($logs);

            $preview->update();

        } else if($input['status'] == 'approve') {

            $preview->status = 'validado';
            $preview->approved_at = now();

            array_push($logs, [
                'status_from' => $last_log ? $last_log['status'] : '',
                'status' => $preview->status,
                'user_id' => auth()->user()->id,
                'user_name' => auth()->user()->name,
                'timestamp' => now(),
            ]);

            $preview->logs = serialize($logs);

            $preview->update();

        }

        return response()->json([]);
    }
}
