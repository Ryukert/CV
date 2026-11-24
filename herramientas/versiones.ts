// Calcula la huella (?v=<hash>) y el peso de cada archivo de public/assets y los guarda en
// lib/versiones.json. Corre solo antes de «npm run dev» y «npm run build».
//
// Así la página no lee archivos del disco al responder (en Vercel la función no los
// tiene) y un archivo que cambia cambia de URL: el navegador lo guarda un año sin riesgo.
import { createHash } from 'node:crypto';
import { readdirSync, readFileSync, statSync, writeFileSync } from 'node:fs';

const dir = new URL('../public/assets/', import.meta.url);
const salida: Record<string, { v: string; kb: number }> = {};
for (const nombre of readdirSync(dir).sort()) {
  const ruta = new URL(nombre, dir);
  if (!statSync(ruta).isFile()) continue;
  const datos = readFileSync(ruta);
  salida[nombre] = {
    v: createHash('md5').update(datos).digest('hex').slice(0, 8),
    kb: Math.round(datos.length / 1024),
  };
}
writeFileSync(new URL('../lib/versiones.json', import.meta.url), JSON.stringify(salida, null, 2) + '\n');
console.log(`lib/versiones.json: ${Object.keys(salida).length} archivos`);
