<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Aprog Console</title>
    <link rel="icon" href="{{ route('aprog.favicon', [], false) }}">
    <style>
        * { box-sizing:border-box }
        body { margin:0; background:#101623; color:#e5edf6; font:15px/1.5 system-ui,sans-serif; height:100vh; height:100dvh; display:flex; flex-direction:column; color-scheme:dark }
        header, footer { background:#182231; padding:14px clamp(12px,3vw,36px) }
        header, .actions, .command, form { display:flex; gap:10px; align-items:center; flex-wrap:wrap }
        header { border-bottom:1px solid #344358; justify-content:space-between }
        h1 { font-size:18px; margin:0 }
        small, .status { color:#b6c6d8 }
        button { font:inherit; color:inherit; background:#253449; border:1px solid #405068; border-radius:7px; padding:4px 10px; cursor:pointer }
        button:hover { background:#374861 }
        button:focus-visible, input:focus-visible { outline:2px solid #67deb4 }
        main { flex:1; min-height:0; overflow:auto; padding:16px clamp(12px,3vw,36px) }
        article { background:#151f2e; border:1px solid #304056; border-radius:10px; padding:12px 16px; margin-bottom:10px }
        code, pre, input, label { font:14px/1.55 ui-monospace,Menlo,monospace }
        code, pre, label { white-space:pre-wrap; overflow-wrap:anywhere }
        .status { margin-left:auto; font-size:13px }
        article[data-state="error"] .status { color:#ffaaa4 }
        article[data-state="done"] .status, label { color:#81e4b9 }
        pre { margin:10px 0 0 }
        .recall { display:block; margin-top:5px; max-width:100%; overflow-wrap:anywhere; text-align:left }
        footer { border-top:1px solid #344358 }
        input { flex:1; min-width:100px; background:transparent; color:inherit; border:0; padding:6px }
        label { max-width:100% }
    </style>
</head>
<body>
<header>
    <div><h1>Aprog Console</h1><small>{{ request()->getHttpHost() }} · {{ app()->environment() }}</small></div>
    <div class="actions"><button id="help">Довідка</button><button id="clear">Очистити</button></div>
</header>
<main id="output" aria-label="Результати команд"></main>
<footer><form id="form">
    <label for="command" id="prompt">{{ $cwd }} $</label>
    <input id="command" aria-label="Команда" autofocus autocomplete="off" spellcheck="false">
    <button>Запустити</button>
</form></footer>
<script>
    const byId = id => document.getElementById(id);
    const input = byId("command");
    const output = byId("output");
    const endpoint = new URL(@json(route('console.run', [], false)), location.origin);
    const commands = [];
    let position = 0;
    let draft = "";
    let queue = Promise.resolve();

    function element(tag, value, parent, className = "") {
        const node = document.createElement(tag);
        node.textContent = value;
        node.className = className;
        parent.append(node);
        return node;
    }

    function nearBottom() {
        return output.scrollHeight - output.clientHeight - output.scrollTop < 40;
    }

    function submit(command) {
        command = command.trim();
        if (!command) return;
        commands.push(command);
        position = commands.length;
        draft = "";
        if (command === "clear") {
            output.replaceChildren();
            return;
        }

        const follow = nearBottom();
        const card = element("article", "", output);
        const head = element("div", "", card, "command");
        element("code", command, head);
        const state = element("span", "У черзі", head, "status");
        state.setAttribute("role", "status");
        const copy = element("button", "Копіювати", head);
        const result = element("pre", "", card);
        copy.disabled = true;
        copy.onclick = async () => {
            try {
                await navigator.clipboard.writeText(result.innerText);
                copy.textContent = "Скопійовано";
            } catch {
                const range = document.createRange();
                range.selectNodeContents(result);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                copy.textContent = "Текст виділено";
            }
        };
        const snapshot = command === "history" ? commands.slice() : [];
        if (follow) output.scrollTop = output.scrollHeight;

        async function run() {
            const start = performance.now();
            state.textContent = "Виконується…";
            let status = "Готово";
            let failed = false;
            try {
                if (command === "history") {
                    snapshot.forEach((item, i) => {
                        const button = element("button", (i + 1) + "  " + item, result, "recall");
                        button.onclick = () => { input.value = item; input.focus(); };
                    });
                } else if (command === "help") {
                    result.textContent = [
                        "about           Застосунок, середовище та версії",
                        "routes console  Маршрути за фільтром (routes — усі)",
                        "php artisan     list, optimize:clear, route:list, migrate:status",
                        "composer        list, --version, validate, show [vendor/package]",
                        "php -v          Версія PHP CLI",
                        "pwd / ls        Поточний каталог / його вміст",
                        "cd каталог      Перехід; cd ~ повертає до кореня проєкту",
                        "history         Історія вкладки; ↑ і ↓ для навігації",
                        "clear           Очистити екран (Ctrl+L)",
                        "help            Показати довідку"
                    ].join("\n");
                } else {
                    const response = await fetch(endpoint, {
                        method: "POST",
                        headers: {"Content-Type":"application/json","Accept":"application/json","X-CSRF-TOKEN":@json(csrf_token())},
                        body: JSON.stringify({command})
                    });
                    const data = await response.json().catch(() => null);
                    if (!response.ok || typeof data?.exit_code !== "number") {
                        throw new Error("HTTP " + response.status + " · " + (data?.message || data?.output || "Неочікувана відповідь сервера"));
                    }
                    if (typeof data.cwd === "string") byId("prompt").textContent = data.cwd + " $";
                    result.textContent = data.output;
                    status = "exit " + data.exit_code;
                    failed = data.exit_code !== 0;
                }
            } catch (error) {
                result.textContent = error.message + "\n" + endpoint.href;
                status = "Помилка";
                failed = true;
            }
            card.dataset.state = failed ? "error" : "done";
            state.textContent = status + " · " + ((performance.now() - start) / 1000).toFixed(2) + " с";
            copy.disabled = !result.textContent;
            if (stick && card.isConnected) output.scrollTop = output.scrollHeight;
        }
        let stick;
        queue = queue.then(async () => {
            stick = nearBottom();
            const track = () => { stick = nearBottom(); };
            output.addEventListener("scroll", track);
            try { await run(); } finally { output.removeEventListener("scroll", track); }
        });
    }

    byId("form").onsubmit = event => {
        event.preventDefault();
        submit(input.value);
        input.value = "";
        input.focus();
    };
    byId("help").onclick = () => submit("help");
    byId("clear").onclick = () => { submit("clear"); input.focus(); };
    document.addEventListener("keydown", event => {
        if (event.ctrlKey && event.key.toLowerCase() === "l") {
            event.preventDefault();
            submit("clear");
            input.focus();
        }
    });
    input.onkeydown = event => {
        if (!["ArrowUp", "ArrowDown"].includes(event.key)) return;
        event.preventDefault();
        if (position === commands.length) draft = input.value;
        position = Math.max(0, Math.min(commands.length, position + (event.key === "ArrowUp" ? -1 : 1)));
        input.value = commands[position] ?? draft;
        input.setSelectionRange(input.value.length, input.value.length);
    };
</script>
</body>
</html>
