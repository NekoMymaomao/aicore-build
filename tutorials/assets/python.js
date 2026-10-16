/* ==========================================================================
   python.js — Pyodide 懒加载与运行封装
   仅在含「运行」按钮（.js-run）的 Python 课程页引入。首次点运行时才从
   CDN 拉取 Pyodide（约数 MB），随后复用同一实例。
   注：示例代码请勿使用 input()，浏览器环境无法读取标准输入。
   ========================================================================== */
(function () {
  "use strict";

  var PYODIDE_VERSION = "0.26.4";
  var BASE = "https://cdn.jsdelivr.net/pyodide/v" + PYODIDE_VERSION + "/full/";

  function esc(s) {
    return String(s).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
  }

  function loadScript(src) {
    return new Promise(function (resolve, reject) {
      var s = document.createElement("script");
      s.src = src;
      s.onload = resolve;
      s.onerror = function () { reject(new Error("无法加载 " + src)); };
      document.head.appendChild(s);
    });
  }

  function ensure() {
    if (window.__pyodide) return Promise.resolve(window.__pyodide);
    if (PyRun._pending) return PyRun._pending;
    PyRun._pending = loadScript(BASE + "pyodide.js").then(function () {
      return window.loadPyodide({ indexURL: BASE });
    }).then(function (py) {
      window.__pyodide = py;
      return py;
    });
    return PyRun._pending;
  }

  var PyRun = {
    _pending: null,
    run: function (code, outEl, btn) {
      var old = btn ? btn.textContent : "";
      if (btn) { btn.disabled = true; btn.textContent = "运行中…"; }
      outEl.innerHTML = '<span class="dim">正在加载 Python 运行时' +
        (window.__pyodide ? "" : "（首次约数 MB，请稍候）") + "…</span>";

      ensure().then(function (py) {
        var buf = "";
        py.setStdout({ batched: function (s) { buf += s + "\n"; } });
        py.setStderr({ batched: function (s) { buf += s + "\n"; } });
        var err = null;
        try {
          py.runPython(code, { globals: py.globals });
        } catch (e) {
          err = (e && (e.message || e.toString())) || "运行错误";
        }
        if (err) {
          outEl.innerHTML = esc(buf) + '<span class="err">' + esc(err) + "</span>";
        } else {
          outEl.textContent = buf.length ? buf.replace(/\n$/, "") : "(无输出)";
        }
      }).catch(function (e) {
        outEl.innerHTML = '<span class="err">' + esc(e && e.message ? e.message : e) + "</span>";
      }).then(function () {
        if (btn) { btn.disabled = false; btn.textContent = old; }
      });
    }
  };

  window.PyRun = PyRun;
})();
