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
        $ids = [];
        $tmp_ids = DB::table('link_user')->where('parent_id', $this->user->id)->get();
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

        // notifications
        $log_users_ids = [];
        foreach ($logs as $log) {
            if (!isset($log_users_ids[$log['user_id']]) && $log['user_id'] != $this->user['id']) {
                array_push($log_users_ids, $log['user_id']);
            }
        }

        $log_users = User::whereIn('id', $log_users_ids)->get();

        if (sizeof($log_users)) {
            $message = "Prévia {$preview->week_ref} recusada por {$this->user['name']}, motivo: " . $inputs['text'];
            $action = 'Ver prévia';
            $action_url = "/previa/{$preview->id}/redirect";

            foreach ($log_users as $log_user) {
                $log_user->notify(new notificationUser(
                    $log_user,
                    $message,
                    $preview->id,
                    $this->user['id'],
                    $action,
                    $action_url,
                ));
            }
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

        // notifications
        $log_users_ids = [];
        foreach($logs as $log) {
            if (!isset($log_users_ids[$log['user_id']]) && $log['user_id'] != $this->user['id']) {
                array_push($log_users_ids, $log['user_id']);
            }
        }

        // notifica o diretor
        if ($inputs['level'] == '2') {
            $tmp_ids = DB::table('link_user')->where('parent_id', $this->user->id)->get();
            foreach ($tmp_ids as $tmp_user_id) {
                $user_id = $tmp_user_id->user_id;
                if (!isset($log_users_ids[$user_id])) {
                    array_push($log_users_ids, $user_id);
                }
            }

        }

        $log_users = User::whereIn('id', $log_users_ids)->get();

        if (sizeof($log_users)) {
            $message = "Prévia {$preview->week_ref} aprovada por {$this->user['name']}.";
            $action = 'Ver prévia';
            $action_url = "/previa/{$preview->id}/redirect";

            foreach($log_users as $log_user) {
                $log_user->notify(new notificationUser(
                    $log_user,
                    $message,
                    $preview->id,
                    $this->user['id'],
                    $action,
                    $action_url,
                ));
            }
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
