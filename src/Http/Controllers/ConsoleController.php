<?php

namespace Aprog\Http\Controllers;

use App\Http\Controllers\Controller;
use Composer\InstalledVersions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/** Copyright (c) 2026 AlexProger. */
class ConsoleController extends Controller
{
    public function index(Request $request)
    {
        return view('aprog::console.index', ['cwd' => $this->cwd($request)]);
    }

    public function run(Request $request)
    {
        $request->validate(['command' => 'required|string|max:1024']);
        [$name, $arg] = array_pad(preg_split('/\s+/', trim($request->input('command')), 2), 2, '');
        $cwd = $this->cwd($request);
        $code = 0;

        switch ($name) {
            case 'about':
                $output = implode(PHP_EOL, [
                    'Застосунок   ' . config('app.name'),
                    'Середовище   ' . app()->environment(),
                    'Laravel      ' . app()->version(),
                    'PHP Web      ' . PHP_VERSION,
                    'PHP CLI      ' . $this->cliVersion(),
                    'APROG        ' . (InstalledVersions::getPrettyVersion('uaproger/aprog') ?? '—'),
                ]);
                break;
            case 'php':
                if ($arg === '-v') {
                    $output = $this->cliVersion();
                    break;
                }
                [$tool, $args] = array_pad(preg_split('/\s+/', $arg, 2), 2, '');
                if ($tool !== 'artisan') {
                    $output = 'Використовуй php -v або php artisan.';
                    $code = 1;
                    break;
                }
                [$code, $output] = $this->projectCommand('artisan', $args);
                break;
            case 'artisan':
            case 'composer':
                [$code, $output] = $this->projectCommand($name, $arg);
                break;
            case 'routes':
                $rows = [];
                foreach (Route::getRoutes() as $route) {
                    $columns = [implode('|', $route->methods()), $route->uri(), $route->getName() ?? '', implode(', ', $route->gatherMiddleware())];
                    $row = implode('  ', $columns);
                    if ($arg === '' || stripos($row, $arg) !== false) {
                        $rows[] = $row;
                    }
                }
                $output = $rows ? "Метод  URI  Назва  Middleware\n" . implode(PHP_EOL, $rows) : 'Маршрутів не знайдено.';
                break;
            case 'pwd':
                $output = $cwd;
                break;
            case 'ls':
                $entries = array_diff(scandir($cwd) ?: [], ['.', '..']);
                $output = implode(PHP_EOL, $entries);
                break;
            case 'cd':
                $root = realpath(base_path());
                $path = trim($arg, "\"'");
                $target = $path === '' || $path === '~' ? $root : realpath(str_starts_with($path, DIRECTORY_SEPARATOR) ? $path : $cwd . DIRECTORY_SEPARATOR . $path);
                if (!$this->inProject($target)) {
                    $output = 'Каталог відсутній або поза проєктом.';
                    $code = 1;
                    break;
                }
                $cwd = $target;
                $request->session()->put('console.cwd', $cwd);
                $output = $cwd;
                break;
            default:
                $output = 'Невідома команда. Введи help.';
                $code = 1;
        }

        return response()->json(['cwd' => $cwd, 'output' => $output, 'exit_code' => $code], 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    private function projectCommand(string $tool, string $args): array
    {
        $args = trim($args);
        if ($tool === 'artisan') {
            $commands = ['', 'list', 'optimize:clear', 'route:list', 'migrate', 'migrate:status'];
            [$name] = preg_split('/\s+/', $args, 2);
            if ($name !== '' && !in_array($args, $commands, true)) {
                $command = Artisan::all()[$name] ?? null;
                if ($command && str_starts_with(get_class($command), app()->getNamespace())) {
                    try {
                        $code = Artisan::call($args . ' --no-interaction --no-ansi');
                        return [$code, Artisan::output()];
                    } catch (\Exception $exception) {
                        report($exception);
                        return [1, $exception->getMessage()];
                    }
                }
                return [1, 'Доступні команди класів застосунку та list, optimize:clear, route:list, migrate, migrate:status.'];
            }
            $binary = (new PhpExecutableFinder())->find(false);
            if (!$binary) {
                return [1, 'Не знайдено PHP CLI.'];
            }
            $argv = [$binary, base_path('artisan'), $args ?: 'list', '--no-interaction', '--no-ansi'];
        } else {
            $args = preg_replace('/\s+/', ' ', $args);
            if (!preg_match('/^(?:|list|--version|validate|show(?: [a-z0-9_.-]+\/[a-z0-9_.-]+)?)$/D', $args)) {
                return [1, 'Доступні команди Composer: list, --version, validate, show [vendor/package].'];
            }
            $binary = (new ExecutableFinder())->find('composer');
            if (!$binary) {
                return [1, 'Composer відсутній у PATH вебпроцесу.'];
            }
            $argv = [$binary, '--no-plugins', '--no-scripts', '--no-interaction', '--no-ansi', ...explode(' ', $args ?: 'list')];
        }

        try {
            $process = new Process($argv, base_path());
            $code = $process->run();
            return [$code, trim($process->getOutput() . PHP_EOL . $process->getErrorOutput())];
        } catch (\Symfony\Component\Process\Exception\ExceptionInterface $exception) {
            report($exception);
            return [1, 'Помилка запуску: ' . $exception->getMessage()];
        }
    }

    private function cwd(Request $request): string
    {
        $cwd = realpath($request->session()->get('console.cwd', base_path()));
        return $this->inProject($cwd) ? $cwd : base_path();
    }

    private function inProject($path): bool
    {
        $root = realpath(base_path());
        return $path && is_dir($path) && ($path === $root || str_starts_with($path, $root . DIRECTORY_SEPARATOR));
    }

    private function cliVersion(): string
    {
        try {
            $binary = (new PhpExecutableFinder())->find(false);
            if ($binary) {
                $process = new Process([$binary, '-v']);
                $process->setTimeout(3);
                $process->mustRun();
                return strtok($process->getOutput(), "\r\n") ?: '—';
            }
        } catch (\Exception $exception) {
            report($exception);
        }
        return 'Недоступна';
    }
}
