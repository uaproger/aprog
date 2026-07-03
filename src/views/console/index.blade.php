<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="UTF-8">
        <title>Aprog Web Console</title>
        <style>
            body {
                margin: 0;
                background: #111827;
                color: #d1d5db;
                font-family: monospace;
            }
            .terminal {
                padding: 20px;
                height: 100vh;
                box-sizing: border-box;
                overflow-y: auto;
            }
            .line {
                white-space: pre-wrap;
                margin-bottom: 4px;
            }
            .prompt {
                color: #22c55e;
            }
            .input-line {
                display: flex;
                gap: 8px;
            }
            input {
                flex: 1;
                background: transparent;
                color: #f9fafb;
                border: none;
                outline: none;
                font-family: monospace;
                font-size: 15px;
            }
            .output {
                color: #e5e7eb;
            }
            .error {
                color: #f87171;
            }
        </style>
    </head>
    <body>
        <div class="terminal" id="terminal">
            <div class="line">Aprog Web Console</div>
            <div class="line">@author: AlexProger</div>
            <div class="line">Type commands like: php artisan, ls, pwd, composer install</div>
            <div class="line">---------------------------------------------</div>

            <div id="output"></div>

            <div class="input-line">
                <span class="prompt" id="prompt">{{ $cwd }} $</span>
                <input id="command" autofocus autocomplete="off">
            </div>
        </div>

        <script>
            const input = document.getElementById('command');
            const output = document.getElementById('output');
            const prompt = document.getElementById('prompt');
            const terminal = document.getElementById('terminal');

            let history = [];
            let historyIndex = -1;

            input.addEventListener('keydown', async function (e) {
                if (e.key === 'Enter') {
                    const command = input.value.trim();
                    if (!command) return;

                    history.push(command);
                    historyIndex = history.length;

                    print(`<span class="prompt">${escapeHtml(prompt.textContent)}</span> ${escapeHtml(command)}`);

                    input.value = '';

                    await runCommand(command);
                }

                if (e.key === 'ArrowUp') {
                    if (historyIndex > 0) {
                        historyIndex--;
                        input.value = history[historyIndex];
                    }
                }

                if (e.key === 'ArrowDown') {
                    if (historyIndex < history.length - 1) {
                        historyIndex++;
                        input.value = history[historyIndex];
                    } else {
                        historyIndex = history.length;
                        input.value = '';
                    }
                }
            });

            async function runCommand(command) {
                try {
                    const response = await fetch('{{ route('console.run') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ command })
                    });

                    const data = await response.json();
                    if (data.output) print(`<span class="${data.exit_code === 0 ? 'output' : 'error'}">${escapeHtml(data.output)}</span>`);

                    prompt.textContent = `${data.cwd} $`;
                    scrollBottom();
                } catch (e) {
                    print(`<span class="error">${escapeHtml(e.message)}</span>`);
                }
            }

            function print(html) {
                const div = document.createElement('div');
                div.className = 'line';
                div.innerHTML = html;
                output.appendChild(div);
                scrollBottom();
            }

            function scrollBottom() {
                terminal.scrollTop = terminal.scrollHeight;
            }

            function escapeHtml(text) {
                return text
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;');
            }
        </script>
    </body>
</html>
