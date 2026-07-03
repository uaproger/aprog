<?php

namespace Aprog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Throwable;

/**
 * class ConsoleController
 * @package Aprog\Controllers
 * @author Copyright (c) 2026 AlexProger.
 */
class ConsoleController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->put('console.cwd', base_path());

        return view('aprog::console.index', ['cwd' => base_path()]);
    }

    public function run(Request $request)
    {
        $command = trim((string) $request->input('command'));
        if ($command === '') return response()->json([
            'cwd' => $this->cwd($request),
            'output' => '',
        ]);

        $cwd = $this->cwd($request);
        if (str_starts_with($command, 'cd')) return $this->changeDirectory($request, $command, $cwd);

        $process = $this->makeProcess($command, $cwd);
        $process->setTimeout(300);
        $output = '';

        try {
            $process->run(function ($type, $buffer) use (&$output) {
                $output .= $buffer;
            });

            return response()->json([
                'cwd' => $cwd,
                'output' => $output ?: '[OK]',
                'exit_code' => $process->getExitCode(),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'cwd' => $cwd,
                'output' => $e->getMessage(),
                'exit_code' => 1,
            ], 500);
        }
    }

    private function cwd(Request $request): string
    {
        $cwd = $request->session()->get('console.cwd', base_path());
        if (!File::isDirectory($cwd)) return base_path();

        return $cwd;
    }

    private function changeDirectory(Request $request, string $command, string $cwd)
    {
        $path = trim(substr($command, 2));
        if ($path === '' || $path === '~') $newPath = base_path();
        elseif (str_starts_with($path, '/')) $newPath = $path;
        else $newPath = realpath($cwd . DIRECTORY_SEPARATOR . $path);

        if (!$newPath || !File::isDirectory($newPath)) return response()->json([
            'cwd' => $cwd,
            'output' => "Directory not found: $path",
            'exit_code' => 1,
        ]);

        $request->session()->put('console.cwd', $newPath);

        return response()->json([
            'cwd' => $newPath,
            'output' => '',
            'exit_code' => 0,
        ]);
    }

    private function makeProcess(string $command, string $cwd): Process
    {
        if (PHP_OS_FAMILY === 'Windows') return new Process(['cmd', '/C', $command], $cwd);

        return new Process(['/bin/bash', '-lc', $command], $cwd);
    }
}
