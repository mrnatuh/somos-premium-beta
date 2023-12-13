<?php

namespace App\Http\Controllers;

use App\Models\Preview;
use App\Models\User;
use App\Notifications\notificationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreviewForceController extends Controller
{
    public $logs;
    public $last_log;
    protected $user;

    /**
     * level = 1 => supervisão para coordenação
     * level = 2 => coordenação para diretoria
     * level = 3 => controlador, manager, admin
     */

    public function __construct(Preview $preview)
    {
        $this->middleware(function (Request $request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function publish(Preview $preview)
    {
        $logs = isset($preview->logs) ? unserialize($preview->logs) : [];
        $last_log = sizeof($logs) ? $logs[sizeof($logs) - 1] : null;

        $preview->status = 'em-analise';
        $preview->published_at = now();

        array_push($logs, [
            'status_from' => $last_log ? $last_log['status'] : '',
            'status' => $preview->status,
            'user_id' => $this->user['id'],
            'user_name' => $this->user['name'],
            'level' => 1,
            'timestamp' => now(),
        ]);

        $preview->logs = serialize($logs);

        $preview->update();

        // notificação
        $message = "Prévia {$preview->week_ref} enviada para aprovação por {$this->user['name']}.";

        $action = 'Ver prévia';
        $action_url = "/previa/{$preview->id}/redirect";

        // coordenadores e diretores
        $tmp_ids = DB::table('link_user')->where('parent_id', $this->user->id)->get();
        $ids = [];
        foreach ($tmp_ids as $tmp_user_id) {
            array_push($ids, $tmp_user_id->user_id);
        }

        $tmp_notify_users = User::whereIn('id', $ids)->get();

        foreach($tmp_notify_users as $notify_user) {
            $notify_user->notify(
                new notificationUser(
                    $notify_user,
                    $message,
                    $preview->id,
                    $this->user['id'],
                    $action,
                    $action_url,
                )
            );
        }
    }

    public function reprove(Preview $preview, Request $request)
    {
        $logs = isset($preview->logs) ? unserialize($preview->logs) : [];
        $last_log = sizeof($logs) ? $logs[sizeof($logs) - 1] : null;

        $inputs = $request->validate([
            'level' => 'required|string',
            'text' => 'required|string|min:10',
            'status' => 'required|string',
        ]);

        $preview->status = 'recusado';

        array_push($logs, [
            'status_from' => $last_log ? $last_log['status'] : '',
            'status' => $preview->status,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'level' => $inputs['level'],
            'text' => $inputs['text'],
            'timestamp' => now(),
        ]);

        $preview->logs = serialize($logs);

        $preview->update();

        $last_log_user = User::where('id', $last_log['user_id'])->first();

        if ($last_log_user) {
            $message = 'Prévia recusada: ' . $inputs['text'];
            $action = 'Ver prévia';
            $action_url = "/previa/{$preview->id}/redirect";

            $last_log_user->notify(new notificationUser(
                $last_log_user,
                $message,
                $preview->id,
                $this->user['id'],
                $action,
                $action_url,
            ));
        }
    }

    public function approve(Preview $preview, Request $request)
    {
        $logs = isset($preview->logs) ? unserialize($preview->logs) : [];
        $last_log = sizeof($logs) ? $logs[sizeof($logs) - 1] : null;

        $inputs = $request->validate([
            'level' => 'required|string',
            'status' => 'required|string',
        ]);

        $preview->status = 'validado';

        if ($inputs['level'] == '3') {
            $preview->approved_at = now();
        }

        array_push($logs, [
            'status_from' => $last_log ? $last_log['status'] : '',
            'status' => $preview->status,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'level' => $inputs['level'],
            'timestamp' => now(),
        ]);

        $preview->logs = serialize($logs);

        $preview->update();

        $last_log_user = User::where('id', $last_log['user_id'])->first();

        if ($last_log_user) {
            $message = 'Prévia aprovada.';
            $action = 'Ver prévia';
            $action_url = "/previa/{$preview->id}/redirect";

            $last_log_user->notify(new notificationUser(
                $last_log_user,
                $message,
                $preview->id,
                $this->user['id'],
                $action,
                $action_url,
            ));
        }
    }

    public function redirect(Preview $preview, Request $request)
    {
        if (isset($request->n)) {
            DB::table('notifications')->where('id', $request->n)->delete();
        }

        if (!$preview) {
            return to_route('home');
        }

        session()->put('preview', [
            'cc' => $preview->cc,
            'week_ref' => $preview->week_ref,
        ]);

        return to_route('category', [
            'filter' => 'faturamento'
        ]);
    }

    public function clear(Preview $preview)
    {
        $preview->status = 'em-analise';
        $preview->published_at = null;
        $preview->approved_at = null;
        $preview->logs = null;
        $preview->update();

        return null;
    }
}
