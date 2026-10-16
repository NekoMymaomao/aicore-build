/* ==========================================================================
   tutorial.js — 代码高亮 + 代码框交互 + 深浅色主题切换
   无第三方依赖：内置轻量正则/状态机高亮，支持 c / python / java / php。
   ========================================================================== */
(function () {
  "use strict";

  var KEYWORDS = {
    c: ("auto break case const continue default do else enum extern for goto if inline register restrict " +
        "return sizeof static struct switch typedef union volatile while _Bool _Complex").split(" "),
    python: ("and as assert async await break class continue def del elif else except finally for from global " +
             "if import in is lambda nonlocal not or pass raise return try while with yield match case True False None").split(" "),
    java: ("abstract assert break case catch class const continue default do else enum extends final finally for " +
           "goto if implements import instanceof interface native new package private protected public return static " +
           "strictfp super switch synchronized this throw throws transient try volatile while true false null var record sealed permits yield").split(" "),
    php: ("abstract and array as break callable case catch class clone const continue declare default die do echo else " +
          "elseif empty enddeclare endfor endforeach endif endswitch endwhile eval exit extends final finally fn for " +
          "foreach function global goto if implements include include_once instanceof insteadof interface isset list " +
          "match namespace new or print private protected public readonly require require_once return static switch throw " +
          "trait try unset use var while xor yield true false null").split(" ")
  };

  // 类型/内置标识（用于着色，不影响语法）
  var TYPES = {
    c: ("int char float double void long short unsigned signed size_t bool FILE NULL").split(" "),
    java: ("int char float double void long short byte boolean String Integer Double Boolean Character Object List Map Set " +
           "ArrayList HashMap HashSet System Math").split(" "),
    php: ("int float string bool array object mixed void callable iterable null true false").split(" ")
  };

  var CFG = {
    c:      { line: ["//"], block: true, pre: true,  triple: [] },
    java:   { line: ["//"], block: true, pre: false, triple: [] },
    php:    { line: ["//", "#"], block: true, pre: false, triple: [] },
    python: { line: ["#"],  block: false, pre: false, triple: ['"""', "'''"] },
    text:   { line: [],     block: false, pre: false, triple: [] }
  };

  function esc(s) {
    return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
  }
  function span(cls, s) {
    return '<span class="tok-' + cls + '">' + esc(s) + "</span>";
  }

  function highlight(code, lang) {
    var cfg = CFG[lang] || CFG.text;
    var keys = KEYWORDS[lang] || [];
    var types = TYPES[lang] || [];
    var out = "";
    var i = 0, n = code.length;

    function isIdStart(ch) { return /[A-Za-z_$]/.test(ch); }
    function isId(ch) { return /[A-Za-z0-9_$]/.test(ch); }

    while (i < n) {
      var ch = code[i];

      // 行注释
      var matchedLine = null;
      for (var l = 0; l < cfg.line.length; l++) {
        if (code.startsWith(cfg.line[l], i)) { matchedLine = cfg.line[l]; break; }
      }
      if (matchedLine) {
        var j = code.indexOf("\n", i);
        if (j < 0) j = n;
        out += span("com", code.slice(i, j));
        i = j;
        continue;
      }

      // 块注释
      if (cfg.block && code.startsWith("/*", i)) {
        var b = code.indexOf("*/", i + 2);
        b = b < 0 ? n : b + 2;
        out += span("com", code.slice(i, b));
        i = b;
        continue;
      }

      // 三引号字符串（python）
      var tri = null;
      for (var t = 0; t < cfg.triple.length; t++) {
        if (code.startsWith(cfg.triple[t], i)) { tri = cfg.triple[t]; break; }
      }
      if (tri) {
        var e = code.indexOf(tri, i + tri.length);
        e = e < 0 ? n : e + tri.length;
        out += span("str", code.slice(i, e));
        i = e;
        continue;
      }

      // 预处理器（C）
      if (cfg.pre && ch === "#" && (i === 0 || code[i - 1] === "\n")) {
        var pj = code.indexOf("\n", i);
        if (pj < 0) pj = n;
        out += span("pre", code.slice(i, pj));
        i = pj;
        continue;
      }

      // 普通字符串
      if (ch === '"' || ch === "'") {
        var q = ch, k = i + 1;
        while (k < n) {
          if (code[k] === "\\") { k += 2; continue; }
          if (code[k] === q) { k++; break; }
          if (code[k] === "\n") break;
          k++;
        }
        out += span("str", code.slice(i, k));
        i = k;
        continue;
      }

      // 数字
      if (/[0-9]/.test(ch)) {
        var m = i;
        while (m < n && /[0-9a-fA-FxXoObB._]/.test(code[m])) m++;
        out += span("num", code.slice(i, m));
        i = m;
        continue;
      }

      // 标识符
      if (isIdStart(ch)) {
        var w = i;
        while (w < n && isId(code[w])) w++;
        var word = code.slice(i, w);
        if (keys.indexOf(word) >= 0) out += span("key", word);
        else if (types.indexOf(word) >= 0) out += span("typ", word);
        else {
          // 函数调用：标识符后面是 (
          var after = code.slice(w).match(/^\s*\(/);
          out += after ? span("fn", word) : esc(word);
        }
        i = w;
        continue;
      }

      out += esc(ch);
      i++;
    }
    return out;
  }

  function applyHighlight(root) {
    (root || document).querySelectorAll("pre.code").forEach(function (pre) {
      if (pre.dataset.hl === "1") return;
      var box = pre.closest(".code-box");
      var lang = (box && box.dataset.lang) || "text";
      pre.innerHTML = highlight(pre.textContent, lang);
      pre.dataset.hl = "1";
    });
  }

  // ------------------------------ 主题切换 ------------------------------

  function initTheme() {
    var saved = null;
    try { saved = localStorage.getItem("tut-theme"); } catch (e) {}
    if (saved) document.documentElement.setAttribute("data-theme", saved);
    document.querySelectorAll(".js-theme-toggle").forEach(function (btn) {
      syncLabel(btn);
      btn.addEventListener("click", function () {
        var cur = document.documentElement.getAttribute("data-theme");
        if (!cur) {
          cur = matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }
        var next = cur === "dark" ? "light" : "dark";
        document.documentElement.setAttribute("data-theme", next);
        try { localStorage.setItem("tut-theme", next); } catch (e) {}
        syncLabel(btn);
      });
    });
  }
  function syncLabel(btn) {
    var cur = document.documentElement.getAttribute("data-theme");
    if (!cur) cur = matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    btn.textContent = cur === "dark" ? "☀ 浅色" : "☾ 深色";
  }

  // ------------------------------ 复制按钮 ------------------------------

  function initCopy() {
    document.querySelectorAll(".js-copy").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var box = btn.closest(".code-box");
        var pre = box.querySelector("pre.code");
        var text = pre.textContent;
        var done = function () {
          var old = btn.textContent;
          btn.textContent = "已复制";
          setTimeout(function () { btn.textContent = old; }, 1200);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).then(done, done);
        } else {
          var ta = document.createElement("textarea");
          ta.value = text; document.body.appendChild(ta); ta.select();
          try { document.execCommand("copy"); } catch (e) {}
          document.body.removeChild(ta); done();
        }
      });
    });
  }

  // --------------------------- Python 运行按钮 ---------------------------

  function initRun() {
    document.querySelectorAll(".js-run").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var box = btn.closest(".code-box");
        var pre = box.querySelector("pre.code");
        var out = box.parentElement.querySelector(".output pre");
        if (!out) return;
        if (window.PyRun) {
          window.PyRun.run(pre.textContent, out, btn);
        } else {
          out.textContent = "Python 运行时未加载。";
        }
      });
    });
  }

  function boot() {
    initTheme();
    applyHighlight(document);
    initCopy();
    initRun();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
