// Índice del sprite public/assets/iconos.svg. ARCHIVO GENERADO por herramientas/iconos.mjs:
// no editar a mano.
// Tecnología => [color de relleno, id del símbolo en el sprite]
export const TECH_ICONS: Record<string, [string, string]> = {
  'Python': ['#3776AB', 't-python'],
  'C/C++': ['#00599C', 't-cplusplus'],
  'C++': ['#00599C', 't-cplusplus'],
  'JavaScript': ['#F7DF1E', 't-javascript'],
  'TypeScript': ['#3178C6', 't-typescript'],
  'Node.js': ['#5FA04E', 't-nodedotjs'],
  'PHP': ['#777BB4', 't-php'],
  'Bash': ['#4EAA25', 't-gnubash'],
  'PyQt5': ['#41CD52', 't-qt'],
  'PySide6': ['#41CD52', 't-qt'],
  'React': ['#61DAFB', 't-react'],
  'Preact': ['#673AB8', 't-preact'],
  'Next.js': ['currentColor', 't-nextdotjs'],
  'Vite': ['#9135FF', 't-vite'],
  'Leaflet': ['#199900', 't-leaflet'],
  'OpenStreetMap': ['#7EBC6F', 't-openstreetmap'],
  'PWA': ['#5A0FC8', 't-pwa'],
  'Puppeteer': ['#40B5A4', 't-puppeteer'],
  'NumPy': ['currentColor', 't-numpy'],
  'Pandas': ['currentColor', 't-pandas'],
  'SciPy': ['#8CAAE6', 't-scipy'],
  'Raspberry Pi': ['#A22846', 't-raspberrypi'],
  'ESP32': ['#E7352C', 't-espressif'],
  'Arduino': ['#00878F', 't-arduino'],
  'FastAPI': ['#009688', 't-fastapi'],
  'Supabase': ['#3FCF8E', 't-supabase'],
  'PostgreSQL': ['#4169E1', 't-postgresql'],
  'Firebase': ['#DD2C00', 't-firebase'],
  'Vercel': ['currentColor', 't-vercel'],
  'Ubuntu': ['#E95420', 't-ubuntu'],
  'Linux': ['#FCC624', 't-linux'],
  'Docker': ['#2496ED', 't-docker'],
  'Git': ['#F03C2E', 't-git'],
  'GitHub Actions': ['#2088FF', 't-githubactions'],
  'Claude': ['#D97757', 't-claude'],
  'Tailwind CSS': ['#06B6D4', 't-tailwindcss'],
  'HTML': ['#E34F26', 't-html5'],
  'CSS': ['#663399', 't-css'],
  'JSON': ['currentColor', 't-json'],
};

/** Íconos de contacto con relleno: símbolo «c-<nombre>». */
export const ICONOS_RELLENO: readonly string[] = ["linkedin","github","orcid"];

/** Íconos de trazo (contacto, áreas de enfoque y genéricos): símbolo «g-<nombre>». */
export const ICONOS_TRAZO: readonly string[] = ["mail","phone","chip","onda","codigo","red","barras","espectro","curva","filtro","grafica","seleccion","aproximado","datos","sensor","bus","poligono","api","llave","engranaje","terminal","ventana","cubo"];

/** Tecnologías sin logotipo => su ícono de trazo propio. */
export const ICONOS_GENERICOS: Record<string, string> = {
  "FFT": "espectro",
  "PSD/Welch": "curva",
  "Filtros digitales": "filtro",
  "Digital filters": "filtro",
  "Matplotlib": "grafica",
  "Recharts": "grafica",
  "Machine Learning": "red",
  "Selección de atributos": "seleccion",
  "Feature selection": "seleccion",
  "Sensores MEMS": "sensor",
  "MEMS sensors": "sensor",
  "I2C": "bus",
  "SPI": "bus",
  "UART": "bus",
  "GeoJSON": "poligono",
  "APIs REST": "api",
  "REST APIs": "api",
  "SSH": "llave",
  "systemd": "engranaje",
  "CustomTkinter": "ventana",
  "RapidFuzz": "aproximado",
  "SQL": "datos"
};
