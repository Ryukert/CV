// Pruebas en un navegador real (Chrome o Edge sin ventana) de las funciones con
// JavaScript: buscador y filtro por competencia, tema claro/oscuro, copiar correo,
// compartir, y que sin JavaScript la página quede completa. También revisa que la
// CSP no bloquee nada.
//
//   cd tests/navegador && npm ci && node navegador.mjs
//
// Levanta la versión compilada del sitio (antes: npm run build en la raíz).
import { spawn } from "node:child_process";
import { existsSync } from "node:fs";
import net from "node:net";
import path from "node:path";
import { fileURLToPath } from "node:url";
import puppeteer from "puppeteer-core";

const RAIZ = path.resolve(path.dirname(fileURLToPath(import.meta.url)), "..", "..");
const PUERTO = Number(process.env.PUERTO || 8097);
const BASE = `http://127.0.0.1:${PUERTO}`;

function buscarNavegador() {
  const c = [process.env.NAVEGADOR, "C:/Program Files/Google/Chrome/Application/chrome.exe", "C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe",
    "C:/Program Files/Microsoft/Edge/Application/msedge.exe", "/usr/bin/google-chrome", "/usr/bin/chromium", "/usr/bin/chromium-browser",
    "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"];
  return c.find((p) => p && existsSync(p));
}

const php = spawn(process.execPath, [path.join(RAIZ, "node_modules", "next", "dist", "bin", "next"), "start", "-p", String(PUERTO), "-H", "127.0.0.1"], { cwd: RAIZ, stdio: "ignore" });
const listo = async () => { for (let i = 0; i < 150; i++) { if (await new Promise((r) => { const s = net.connect(PUERTO, "127.0.0.1", () => { s.end(); r(true); }).on("error", () => r(false)); })) return; await new Promise((r) => setTimeout(r, 100)); } throw new Error("El servidor no arrancó"); };
await listo();

const ejecutable = buscarNavegador();
if (!ejecutable) { console.error("No encontré Chrome ni Edge (variable NAVEGADOR)"); php.kill(); process.exit(1); }
const nav = await puppeteer.launch({ executablePath: ejecutable, headless: true, args: process.env.CI ? ["--no-sandbox"] : [] });
const pausa = (ms) => new Promise((r) => setTimeout(r, ms));
let total = 0, fallas = 0;
const ok = (c, t) => { total++; if (!c) fallas++; console.log((c ? "  ok    " : "  FALLA ") + t); };

async function pagina({ ancho = 1280, alto = 900, movil = false, js = true, sinShare = true } = {}) {
  const p = await nav.newPage();
  p.errores = [];
  p.on("console", (m) => { if (m.type() === "error") p.errores.push(m.text()); });
  p.on("pageerror", (e) => p.errores.push(e.message));
  p.on("response", (r) => { if (r.status() >= 400) p.errores.push(r.status() + " " + r.url()); });
  await p.setJavaScriptEnabled(js);
  await p.setViewport({ width: ancho, height: alto, isMobile: movil, hasTouch: movil });
  if (sinShare) await p.evaluateOnNewDocument(() => { try { delete Navigator.prototype.share; } catch (e) {} });
  return p;
}

try {
  await nav.defaultBrowserContext().overridePermissions(BASE, ["clipboard-read", "clipboard-write", "clipboard-sanitized-write"]);

  // ── Buscador ──
  let p = await pagina();
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  ok(p.errores.length === 0, "carga sin errores ni bloqueos de la CSP" + (p.errores.length ? ": " + p.errores.join(" | ") : ""));
  ok(await p.$eval("#buscador", (e) => !e.hidden), "con JavaScript aparece el buscador");
  await p.type("#q", "supabase");
  await pausa(300);
  let s = await p.evaluate(() => ({
    estado: document.querySelector("#q-estado").textContent,
    coinciden: document.querySelectorAll(".coincide").length,
    proyectos: document.querySelectorAll("#projects .card.coincide").length,
    filtrando: document.body.classList.contains("filtrando"),
    url: location.search,
    atenuado: getComputedStyle(document.querySelector("#projects .card:not(.coincide)")).opacity,
  }));
  ok(s.filtrando && s.coinciden >= 4 && s.proyectos >= 3, `«supabase» marca ${s.coinciden} lugares (${s.proyectos} proyectos)`);
  ok(/«supabase»: \d+ coincidencias — .*proyectos \d+/.test(s.estado), "el estado dice cuántas y en qué secciones: " + s.estado);
  ok(Number(s.atenuado) < 0.5, "lo que no coincide se atenúa");
  ok(s.url === "?q=supabase", "la búsqueda queda en la dirección (?q=supabase) para compartirla");

  // Vaciar el campo antes de escribir (desde puppeteer 25 el triple clic ya no selecciona su texto).
  await p.$eval("#q", (e) => { e.value = ""; });
  await p.type("#q", "tecnologias");
  await pausa(300);
  ok(await p.evaluate(() => [...document.querySelectorAll(".coincide")].some((e) => /Tecnologías/.test(e.textContent))), "sin acentos: «tecnologias» encuentra «Tecnologías»");

  await p.keyboard.press("Escape");
  await pausa(100);
  s = await p.evaluate(() => ({ coinciden: document.querySelectorAll(".coincide").length, filtrando: document.body.classList.contains("filtrando"), url: location.search }));
  ok(s.coinciden === 0 && !s.filtrando && s.url === "", "Esc limpia la búsqueda y la dirección");

  await p.type("#q", "zzzxq");
  await pausa(300);
  ok(/Sin coincidencias/.test(await p.$eval("#q-estado", (e) => e.textContent)), "sin resultados lo dice");
  await p.click("#q-limpiar");
  await pausa(100);

  // ── Filtro por competencia ──
  const chip = await p.$$eval("#skills .filtro", (bs) => bs.map((b) => b.textContent));
  ok(chip.length >= 40, `las competencias son botones de filtro (${chip.length})`);
  await p.evaluate(() => [...document.querySelectorAll("#skills .filtro")].find((b) => b.textContent === "Leaflet").click());
  await pausa(300);
  s = await p.evaluate(() => ({
    pulsado: document.querySelector('#skills .filtro[aria-pressed="true"]')?.textContent,
    q: document.querySelector("#q").value,
    proyectos: [...document.querySelectorAll("#projects .card.coincide h3")].map((h) => h.textContent),
  }));
  ok(s.pulsado === "Leaflet" && s.q === "Leaflet", "clic en «Leaflet» la marca y la escribe en el buscador");
  ok(s.proyectos.some((t) => /Ecosistema/.test(t)), "«Leaflet» señala el proyecto Ecosistema: " + s.proyectos.join(", "));
  await p.evaluate(() => [...document.querySelectorAll("#skills .filtro")].find((b) => b.textContent.startsWith("Linux")).click());
  await pausa(300);
  ok(await p.evaluate(() => [...document.querySelectorAll("#exp li.coincide")].some((li) => /Ubuntu/.test(li.textContent))), "«Linux / Ubuntu Server» encuentra la experiencia con Ubuntu (variantes)");
  await p.evaluate(() => document.querySelector('#skills .filtro[aria-pressed="true"]').click());
  await pausa(100);
  ok(await p.evaluate(() => !document.querySelector('#skills .filtro[aria-pressed="true"]') && !document.body.classList.contains("filtrando")), "otro clic en la competencia quita el filtro");

  await p.type("#q", "deep learning");
  await pausa(300);
  await p.close();

  // ── Búsqueda que llega en la dirección ──
  p = await pagina();
  await p.goto(BASE + "/en?q=Python", { waitUntil: "networkidle0" });
  s = await p.evaluate(() => ({ q: document.querySelector("#q").value, n: document.querySelectorAll(".coincide").length, estado: document.querySelector("#q-estado").textContent, pulsado: document.querySelector('#skills .filtro[aria-pressed="true"]')?.textContent }));
  ok(s.q === "Python" && s.n > 3 && /"Python": \d+ matches/.test(s.estado) && s.pulsado === "Python", "un enlace con ?q=Python abre ya filtrado (en inglés): " + s.estado);
  await p.close();

  // ── Tema ──
  p = await pagina();
  await p.emulateMediaFeatures([{ name: "prefers-color-scheme", value: "light" }]);
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  const fondo = () => p.evaluate(() => getComputedStyle(document.body).backgroundColor);
  const claro = await fondo();
  ok(await p.$eval("#tema", (b) => !b.hidden && /oscuro/.test(b.getAttribute("aria-label"))), "botón de tema visible: «Cambiar a tema oscuro»");
  await p.click("#tema");
  await pausa(100);
  const oscuro = await fondo();
  ok(claro !== oscuro && (await p.evaluate(() => document.documentElement.dataset.theme)) === "dark", `cambia a oscuro (${claro} → ${oscuro})`);
  await p.reload({ waitUntil: "networkidle0" });
  ok((await fondo()) === oscuro && /claro/.test(await p.$eval("#tema", (b) => b.getAttribute("aria-label"))), "la elección se recuerda al recargar");
  await p.click("#tema");
  await pausa(100);
  ok((await fondo()) === claro, "y vuelve a claro");
  await p.evaluate(() => localStorage.removeItem("tema"));
  await p.close();

  p = await pagina();
  await p.emulateMediaFeatures([{ name: "prefers-color-scheme", value: "dark" }]);
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  ok((await p.$eval("#tema", (b) => b.dataset.actual)) === "dark", "sin elección guardada sigue el tema del sistema (oscuro)");
  await p.close();

  // ── Copiar correo y compartir ──
  p = await pagina();
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  const correo = await p.$eval(".hero [data-copiar]", (b) => b.dataset.copiar);
  await p.click(".hero [data-copiar]");
  await pausa(200);
  s = await p.evaluate(async () => ({ aviso: document.querySelector("#aviso").textContent, portapapeles: await navigator.clipboard.readText().catch(() => "") }));
  ok(s.portapapeles === correo && /Correo copiado/.test(s.aviso), "«Copiar» pone el correo en el portapapeles y avisa: " + s.aviso);
  await p.click("#compartir");
  await pausa(200);
  s = await p.evaluate(async () => ({ aviso: document.querySelector("#aviso").textContent, portapapeles: await navigator.clipboard.readText().catch(() => "") }));
  ok(/^https?:\/\/.+\/$/.test(s.portapapeles) && /Enlace copiado/.test(s.aviso), "sin menú de compartir del sistema, «Compartir» copia el enlace: " + s.portapapeles);
  await p.close();

  // ── Monitor SHM, secciones que aparecen y atajo de teclado ──
  p = await pagina();
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  await p.waitForSelector("#monitor[data-listo]", { timeout: 5000 }).catch(() => {});
  await pausa(800);
  s = await p.evaluate(() => ({
    oculto: document.getElementById("monitor").hidden,
    ancho: document.getElementById("monitor-onda").width,
    f0: parseFloat(document.getElementById("monitor-f0").textContent),
    rms: parseFloat(document.getElementById("monitor-rms").textContent),
  }));
  ok(!s.oculto && s.ancho > 0, "el monitor SHM aparece y dibuja en el canvas");
  ok(s.f0 > 1.5 && s.f0 < 15 && s.rms > 0, `el monitor calcula la frecuencia dominante con la FFT (f₀ = ${s.f0} Hz, RMS = ${s.rms})`);
  const cuadro = () => p.$eval("#monitor-onda", (c) => c.toDataURL());
  await p.click("#monitor-pausa");
  let a = await cuadro(); await pausa(400); let b = await cuadro();
  ok(a === b && (await p.$eval("#monitor-pausa", (e) => e.getAttribute("aria-pressed"))) === "true", "«Pausar» detiene el monitor");
  await p.click("#monitor-pausa");
  a = await cuadro(); await pausa(400); b = await cuadro();
  ok(a !== b, "«Reanudar» lo vuelve a mover");

  s = await p.evaluate(() => document.querySelectorAll("main section.revelar:not(.visible)").length);
  await p.evaluate(() => document.querySelector("#pubs").scrollIntoView());
  await pausa(900);
  ok(s > 0 && (await p.$eval("#pubs", (e) => getComputedStyle(e).opacity)) === "1", `las secciones aparecen al llegar a ellas (${s} esperaban)`);

  await p.evaluate(() => { window.scrollTo(0, 0); document.activeElement && document.activeElement.blur(); });
  await p.keyboard.press("/");
  ok((await p.evaluate(() => document.activeElement && document.activeElement.id)) === "q", "la tecla «/» lleva al buscador");
  ok(p.errores.length === 0, "monitor y efectos sin errores ni bloqueos de la CSP" + (p.errores.length ? ": " + p.errores.join(" | ") : ""));
  s = await p.evaluate(() => {
    const r = {};
    try { document.createElement("div").innerHTML = "<img src=x onerror=alert(1)>"; r.html = "permitido"; } catch (e) { r.html = e.name; }
    try { document.createElement("script").src = "https://evil.example/x.js"; r.src = "permitido"; } catch (e) { r.src = e.name; }
    return r;
  });
  ok(s.html === "TypeError" && s.src === "TypeError", `Trusted Types bloquea innerHTML y src de script sin política (innerHTML: ${s.html}, src: ${s.src})`);
  await p.close();

  p = await pagina();
  await p.emulateMediaFeatures([{ name: "prefers-reduced-motion", value: "reduce" }]);
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  await p.waitForSelector("#monitor[data-listo]", { timeout: 5000 }).catch(() => {});
  s = await p.evaluate(() => ({
    pausado: document.getElementById("monitor-pausa").getAttribute("aria-pressed"),
    revelar: document.querySelectorAll("main section.revelar").length,
  }));
  ok(s.pausado === "true" && s.revelar === 0, "con «reducir movimiento» el monitor arranca en pausa y nada se anima");
  await p.close();

  // ── Sin JavaScript ──
  p = await pagina({ js: false });
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  s = await p.evaluate(() => ({
    visibles: ["#buscador", "#tema", "#compartir", "[data-copiar]", "#monitor"].filter((sel) => { const e = document.querySelector(sel); return e && e.offsetParent !== null; }),
    proyectos: document.querySelectorAll("#projects .card").length,
    chips: document.querySelectorAll("#skills .chips li").length,
  }));
  ok(s.visibles.length === 0, "sin JavaScript no aparecen controles que no funcionarían" + (s.visibles.length ? ": " + s.visibles.join(", ") : ""));
  ok(s.proyectos >= 10 && s.chips >= 40, "sin JavaScript el CV está completo");
  await p.close();

  // ── Celular ──
  p = await pagina({ ancho: 390, alto: 844, movil: true });
  await p.goto(BASE + "/", { waitUntil: "networkidle0" });
  s = await p.evaluate(() => ({
    scrollX: document.documentElement.scrollWidth > innerWidth,
    tema: (({ right, width }) => ({ right, width }))(document.querySelector("#tema").getBoundingClientRect()),
    chicos: [...document.querySelectorAll("#tema, #compartir, [data-copiar], #q, #monitor-pausa")].filter((e) => e.offsetParent && e.getBoundingClientRect().height < 44).map((e) => e.id || e.className),
  }));
  ok(!s.scrollX, "en el celular no hay desplazamiento horizontal");
  ok(s.tema.right <= 390 && s.tema.width >= 44, "el botón de tema se ve completo en la barra del celular");
  ok(s.chicos.length === 0, "los controles nuevos miden al menos 44 px" + (s.chicos.length ? ": " + s.chicos.join(", ") : ""));
  ok(p.errores.length === 0, "celular sin errores");
  await p.close();
} finally {
  await nav.close();
  php.kill();
}

console.log(`\nNavegador: ${total - fallas} de ${total} pruebas bien`);
process.exit(fallas ? 1 : 0);
