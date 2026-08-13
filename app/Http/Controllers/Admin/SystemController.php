<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SystemController extends Controller
{
    public function settings(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->except('_token');
            foreach ($data as $key => $value) {
                \App\Models\Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
            \Illuminate\Support\Facades\Cache::forget('maintenance_mode_status');
            return redirect()->back()->with('success', 'Cập nhật cấu hình thành công!');
        }

        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.system.settings', compact('settings'));
    }

    public function backupDatabase()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        
        $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $fileName);

        // Ensure directory exists
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $connection = env('DB_CONNECTION');
        
        if ($connection === 'sqlite') {
            $sqlitePath = database_path('database.sqlite');
            if (file_exists($sqlitePath)) {
                copy($sqlitePath, $path);
            } else {
                return redirect()->back()->with('error', 'Không tìm thấy file database.sqlite.');
            }
        } else {
            // On Windows with XAMPP, mysqldump needs to be in PATH or specify full path
            $passwordPart = $password ? "-p{$password}" : "";
            $command = "mysqldump -u {$username} {$passwordPart} {$database} > \"{$path}\" 2>&1";
            shell_exec($command);
        }

        if (file_exists($path) && filesize($path) > 0) {
            return Response::download($path)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'Không thể tạo bản sao lưu. Vui lòng kiểm tra lại cấu hình mysqldump.');
        }
    }
}
