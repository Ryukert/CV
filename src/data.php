<?php
declare(strict_types=1);

/**
 * Fuente única de verdad del CV.
 * Todo el sitio (HTML y API JSON, en ambos idiomas) se genera desde este archivo.
 * Para actualizar el CV en línea basta con editar aquí y hacer push.
 */

/** Fecha de la última actualización del contenido (sitemap, pie de página y /health). */
const CV_UPDATED = '2026-11-13';

const PROFILE = [
    'name'    => 'Marco Antonio Adame Rodríguez',
    'email'   => 'ragnarockgames86@gmail.com',
    'phone'   => '+52 747 334 2910',
    'github'  => 'https://github.com/Ryukert',
    'linkedin'=> 'https://www.linkedin.com/in/marco-antonio-adame-rodr%C3%ADguez-211615227/',
    'orcid'   => 'https://orcid.org/0009-0003-2361-4945',
];

const SHARED = [
    'publications' => [
        [
            'authors' => 'Adame Rodríguez, M. A., Alcaraz Vázquez, M., Martínez, E. T., García Guevara, D. S., Alonso Silverio, G. A.',
            'year'    => 2026,
            'title'   => ['es' => 'Sistema de medición de vibraciones ambientales en mesa vibradora biaxial',
                          'en' => 'Ambient Vibration Measurement System on a Biaxial Vibrating Table'],
            'venue'   => 'Revista Vínculos, 22(1)',
            'role'    => ['es' => 'Autor principal', 'en' => 'Lead author'],
            'url'     => 'https://revistas.udistrital.edu.co/index.php/vinculos/article/view/24610',
        ],
        [
            'authors' => 'Alcaraz Vázquez, M., Adame Rodríguez, M. A., García Guevara, D. S., Martínez, E. T., Alonso Silverio, G. A.',
            'year'    => 2025,
            'title'   => ['es' => 'Selección de atributos mediante feature importance para la clasificación de casos de dengue en México',
                          'en' => 'Feature-importance-based attribute selection for dengue case classification in Mexico'],
            'venue'   => 'Revista Vínculos, 22(2)',
            'role'    => ['es' => 'Coautor', 'en' => 'Co-author'],
            'url'     => 'https://revistas.udistrital.edu.co/index.php/vinculos/article/view/24567',
        ],
        [
            'authors' => 'García Guevara, D. S., Martínez, E. T., Alcaraz Vázquez, M., Adame Rodríguez, M. A., Alonso Silverio, G. A.',
            'year'    => 2025,
            'title'   => ['es' => 'Agitador de muestras biológicas con control de temperatura basado en tecnologías IoT',
                          'en' => 'IoT-based biological sample shaker with temperature control'],
            'venue'   => 'Revista Vínculos, 22(2)',
            'role'    => ['es' => 'Coautor', 'en' => 'Co-author'],
            'url'     => 'https://revistas.udistrital.edu.co/index.php/vinculos/article/view/24607',
        ],
    ],
    'projects' => [
        [
            'key'      => 'shm',
            'stack'    => 'Python · C++ · Raspberry Pi · PyQt5',
            'url'      => null,
            'featured' => true,
        ],
        [
            'key'   => 'ecosistema',
            'stack' => 'JavaScript · Leaflet · Supabase · PWA · GitHub Actions',
            'url'   => 'https://ecosistemariesgosismicotijuana.vercel.app',
        ],
        [
            'key'   => 'ivbc',
            'stack' => 'JavaScript · Preact · Supabase · Vercel Functions · GeoJSON',
            'url'   => 'https://iv-bc-sitio.vercel.app',
        ],
        [
            'key'   => 'game',
            'stack' => 'React 18 · Vite · Claude API · Vercel',
            'url'   => 'https://github.com/Ryukert/JUEGO-DE-GRENCITO-Y-SISMOTECNITO',
        ],
        [
            'key'   => 'trading',
            'stack' => 'Python · PySide6',
            'url'   => 'https://github.com/Ryukert/trading-',
        ],
        [
            'key'   => 'gps',
            'stack' => 'JavaScript · Vercel',
            'url'   => 'https://github.com/Ryukert/SHM_GPS_NETWORK_DASH_elite',
        ],
        [
            'key'   => 'attendance',
            'stack' => 'JavaScript · Supabase · Vercel',
            'url'   => 'https://github.com/Ryukert/SECUNDARIA-IMA',
        ],
        [
            'key'   => 'supabase',
            'stack' => 'JavaScript · Supabase · PostgreSQL',
            'url'   => 'https://github.com/Ryukert/MIIDT_SUPABASE_DASH',
        ],
        [
            'key'   => 'api',
            'stack' => 'Python · FastAPI',
            'url'   => 'https://github.com/Ryukert/APIAceleraciones',
        ],
        [
            'key'   => 'crm',
            'stack' => 'JavaScript · Vercel',
            'url'   => 'https://github.com/Ryukert/joyeria-crm',
        ],
        [
            'key'   => 'cv',
            'stack' => 'PHP · Vercel · JSON API · GitHub Actions',
            'url'   => 'https://github.com/Ryukert/CV',
        ],
    ],
    'metrics' => [
        ['value' => '200 Hz', 'key' => 'sampling'],
        ['value' => '4',      'key' => 'sensors'],
        ['value' => '5',      'key' => 'sites'],
        ['value' => '3',      'key' => 'papers'],
    ],
];

function cv_data(string $lang): array
{
    $es = [
        'lang'      => 'es',
        'locale'    => 'es_MX',
        'role'      => 'Ingeniero en Sistemas IoT y Embebidos',
        'subrole'   => 'Procesamiento Digital de Señales · Software Científico',
        'location'  => 'Chilpancingo, Guerrero, México (GMT-6)',
        'available' => 'Disponible para trabajo remoto con horario de EE. UU. y Europa',
        'meta_desc' => 'CV de Marco Antonio Adame Rodríguez, ingeniero en sistemas IoT y embebidos, procesamiento digital de señales y monitoreo de salud estructural.',
        'nav'       => ['about' => 'Perfil', 'exp' => 'Experiencia', 'projects' => 'Proyectos', 'skills' => 'Competencias', 'pubs' => 'Publicaciones', 'edu' => 'Formación', 'areas' => 'Áreas', 'contact' => 'Contacto'],
        'cta_pdf'   => 'Descargar CV en PDF',
        'cta_design'=> 'Versión con diseño',
        'switch'    => 'English',
        'switch_url'=> '/en',
        'summary'   => 'Construyo sistemas IoT de extremo a extremo: sensor MEMS, firmware embebido, adquisición en tiempo real, procesamiento de señales, backend en Linux, nube y dashboards. Diseñé desde cero un sistema de monitoreo de salud estructural de bajo costo y lo validé en campo contra instrumentación sísmica comercial (GEA y P-Alert); el trabajo derivó en tres artículos arbitrados y una ponencia internacional. También desarrollo aplicaciones web geoespaciales en producción (Supabase, Leaflet, funcionamiento sin internet) con pruebas automáticas y despliegue continuo. Ingeniero en Computación y egresado de la Maestría en Ingeniería para la Innovación y Desarrollo Tecnológico (UAGro), con promedio de 9.42 y titulación prevista para noviembre de 2026.',
        'metric_labels' => [
            'sampling' => 'Muestreo sincronizado',
            'sensors'  => 'Sensores MEMS integrados',
            'sites'    => 'Entornos de validación en campo',
            'papers'   => 'Artículos arbitrados',
        ],
        'sections'  => [
            'exp'      => 'Experiencia en investigación y desarrollo',
            'pubs'     => 'Publicaciones y ponencias',
            'projects' => 'Proyectos',
            'areas'    => 'Áreas de la computación cubiertas',
            'edu'      => 'Formación académica',
            'skills'   => 'Competencias técnicas',
            'other'    => 'Otra experiencia',
            'contact'  => 'Contacto',
        ],
        'experience' => [[
            'period' => 'Ago 2024 – Actualidad',
            'org'    => 'Universidad Autónoma de Guerrero (UAGro) · Maestría MIIDT',
            'title'  => 'Investigador y desarrollador principal — Sistema SHM-IoT',
            'intro'  => 'Diseño e implementación de extremo a extremo de una solución de bajo costo para monitoreo de salud estructural en obras civiles, validada en campo frente a los equipos comerciales GEA y P-Alert.',
            'groups' => [
                ['name' => 'Adquisición, firmware y hardware', 'items' => [
                    'Diseñé nodos de adquisición sobre Arduino Nano RP2040 Connect y Raspberry Pi 4, integrando cuatro sensores MEMS (MPU9250, MPU6050, LSM6DSOX y ADXL335) por I2C, SPI y UART.',
                    'Implementé adquisición sincronizada a 200 Hz sobre los dos núcleos del RP2040, con margen suficiente por Nyquist para el rango de frecuencias estructurales de interés.',
                    'Validé el sistema en cinco entornos —mesa vibradora biaxial, edificios universitarios, Jardín Botánico, COBACH y estructuras experimentales ligeras— comparando sus registros contra los equipos comerciales GEA y P-Alert.',
                ]],
                ['name' => 'Procesamiento de señales y software científico', 'items' => [
                    'Construí en Python (NumPy/SciPy) el pipeline de DSP para identificar la respuesta dinámica de las estructuras: RMS, FFT, densidad espectral de potencia (Welch), frecuencia dominante, coherencia, fase y correlación cruzada.',
                    'Desarrollé aplicaciones de escritorio en PyQt5 para adquisición, supervisión en vivo y análisis, con control de sesiones y recuperación automática ante fallos.',
                    'Construí herramientas para procesar archivos TXT, CSV, JSON y JSONL con grandes volúmenes de registros: sincronización temporal, remuestreo, detección de huecos, filtrado digital y recorte de series.',
                    'Automaticé la generación de informes técnicos y gráficas comparativas contra los equipos de referencia; estos resultados sustentan el artículo publicado como autor principal.',
                ]],
                ['name' => 'Backend, nube e infraestructura', 'items' => [
                    'Diseñé un receptor centralizado multi-sede sobre HTTP/HTTPS con buffer local, de modo que una caída de conectividad no implique pérdida de datos.',
                    'Desarrollé APIs REST en FastAPI para recepción, persistencia y consulta de telemetría de nodos ESP32.',
                    'Administré servidores Ubuntu en operación continua: servicios con systemd, contenedores Docker, acceso remoto SSH/VNC, respaldos y sincronización.',
                    'Publiqué dashboards web en Vercel para visualizar, filtrar y descargar datos de nodos propios y de sensores comerciales en un mismo lugar.',
                ]],
            ],
        ]],
        'talk' => [
            'label' => 'Ponencia',
            'text'  => '«Sistema de medición de vibraciones ambientales en mesa vibradora biaxial». 15.º Congreso Internacional de Computación (CICOM 2025), Tunja, Colombia, octubre 2025.',
        ],
        'project_text' => [
            'shm'      => ['SHM-IoT MIIDT — Monitoreo estructural de bajo costo', 'Arquitectura integral de adquisición, almacenamiento, análisis y visualización de aceleraciones en estructuras civiles, probada en campo contra sensores comerciales.'],
            'ecosistema' => ['Ecosistema de riesgo sísmico de Tijuana', 'Tablero web que une amenaza sísmica, suelo, estaciones, mapas de aceleración, inspección y difusión sobre una sola base de datos en Supabase. Calcula rutas a hospitales y servicios de emergencia por calles reales (OpenStreetMap, Dijkstra) en cinco ciudades de Baja California con el catálogo oficial CLUES, funciona sin internet como app instalable y se publica con versiones semánticas y pruebas automáticas en cada cambio.'],
            'ivbc'       => ['Infraestructura Verde · Baja California', 'Dashboard de 36 técnicas de infraestructura verde por municipio, con consulta por punto sobre capas GeoJSON y búsqueda de direcciones, panel de edición con inicio de sesión, reportes en PDF y CSV, modo sin conexión y contador de visitas en Supabase.'],
            'gps'      => ['Red de monitoreo SHM con georreferenciación', 'Dashboard web para visualizar ubicación y estado de los nodos distribuidos, con despliegue público continuo.'],
            'supabase' => ['Dashboard de sensores sobre Supabase', 'Aplicación web para envío, consulta y visualización de mediciones almacenadas en la nube.'],
            'api'      => ['APIs de telemetría para nodos ESP32', 'Servicios REST para recepción, persistencia y consulta de datos de aceleración, con graficación científica.'],
            'game'       => ['Escuadrón Tecnito y Greencito — juego educativo con IA', 'Juego por turnos para grupos de primaria y secundaria: dos mascotas conversan con Claude para enseñar protección civil ante sismos y cuidado del medio ambiente. La API key queda en una función serverless.'],
            'trading'    => ['Trading Fusion Pro 3.0', 'Herramienta educativa de escritorio para análisis técnico y fundamental, con backtesting con costos y validación walk-forward. Refactoricé un script de 4,363 líneas a una arquitectura modular.'],
            'attendance' => ['Lista de asistencia escolar', 'Aplicación para pasar lista y generar reportes de asistencia por grupo, con datos en Supabase y pruebas automatizadas de la lógica.'],
            'crm'        => ['CRM para joyería', 'Aplicación web de gestión de clientes para un negocio de joyería, desplegada en Vercel.'],
            'cv'       => ['Este CV en línea', 'Sitio bilingüe en PHP serverless sobre Vercel, generado desde una única fuente de datos, con endpoint JSON público, política de seguridad de contenido estricta y pruebas automáticas en GitHub Actions. Los mismos datos generan versiones del CV en PDF adaptadas a cada vacante.'],
        ],
        'areas' => [
            'toggle' => 'Ver las 17 áreas con sus 69 asignaturas',
            'intro'  => 'Las 69 asignaturas acreditadas entre licenciatura y maestría, ordenadas por área de la computación. La etiqueta «Aplicado» señala las áreas que además respaldo con trabajo real —proyectos en producción, publicaciones arbitradas o validación en campo—; «Formación» indica cobertura académica sin proyecto público asociado.',
            'legend' => ['applied' => 'Aplicado', 'coursework' => 'Formación'],
            'items'  => [
                ['name' => 'Programación, algoritmos y estructuras de datos', 'applied' => true,
                 'evidence' => 'Python y C/C++ a diario: firmware embebido, pipelines de procesamiento y herramientas de escritorio.',
                 'courses' => ['Fundamentos de Programación (9)', 'Programación Orientada a Objetos I (10)', 'Programación Orientada a Objetos II (8)', 'Programación Avanzada (8)', 'Estructura de Datos I (10)', 'Estructura de Datos II (9)', 'Lógica Informática (8)']],

                ['name' => 'Ingeniería y diseño de software', 'applied' => true,
                 'evidence' => 'Aplicaciones PyQt5 con control de sesiones, exportación y recuperación automática ante fallos.',
                 'courses' => ['Análisis y Diseño de Sistemas (10)', 'Ingeniería de Software (8)']],

                ['name' => 'Compiladores y teoría de lenguajes', 'applied' => false,
                 'evidence' => null,
                 'courses' => ['Compiladores (9)', 'Traductores e Intérpretes (8)']],

                ['name' => 'Sistemas operativos y administración de servidores', 'applied' => true,
                 'evidence' => 'Servidores Ubuntu en operación continua: systemd, SSH/VNC, respaldos y sincronización.',
                 'courses' => ['Sistemas Operativos I (10)', 'Sistemas Operativos II (8)', 'Arquitectura de Servidores (10)']],

                ['name' => 'Redes y comunicaciones', 'applied' => true,
                 'evidence' => 'Monitoreo multi-sede con nodos en redes distintas y receptor centralizado sobre HTTP/HTTPS.',
                 'courses' => ['Fundamentos de Enrutamiento (10)', 'Fundamentos de Redes (9)', 'Sistemas de Cableado Estructurado (9)', 'Fundamentos de Comunicaciones (7)']],

                ['name' => 'Seguridad, auditoría y cómputo forense', 'applied' => false,
                 'evidence' => 'Complementado con las certificaciones Cisco Cybersecurity Essentials y CCNAv7.',
                 'courses' => ['Auditoría de Recursos Informáticos (9)', 'Seguridad en Redes (8)', 'Cómputo Forense (8)']],

                ['name' => 'Bases de datos y persistencia', 'applied' => true,
                 'evidence' => 'PostgreSQL y Supabase como almacenamiento multi-origen de telemetría en la nube.',
                 'courses' => ['Base de Datos II (9)', 'Base de Datos I (7)']],

                ['name' => 'Arquitectura de computadoras y sistemas digitales', 'applied' => true,
                 'evidence' => 'Adquisición sincronizada sobre arquitectura dual-core en el RP2040.',
                 'courses' => ['Sistemas Digitales (10)', 'Organización de Computadoras (10)', 'Microprocesadores (8)']],

                ['name' => 'Sistemas embebidos, IoT y electrónica', 'applied' => true,
                 'evidence' => 'Núcleo de mi trabajo: nodos RP2040 y Raspberry Pi con sensores MEMS por I2C, SPI y UART.',
                 'courses' => ['Temas Selectos de Sistemas Embebidos (10)', 'Electricidad y Magnetismo (10)', 'Microcontroladores (9)', 'Diseño de Sistemas IoT (9)', 'Circuitos Eléctricos (9)', 'Principios Básicos de Sistemas Electrónicos (9)', 'Electrónica (7)']],

                ['name' => 'Inteligencia artificial y aprendizaje automático', 'applied' => true,
                 'evidence' => 'Coautor del artículo sobre selección de atributos mediante feature importance para clasificar casos de dengue.',
                 'courses' => ['Machine Learning (10)', 'Deep Learning (10)', 'Análisis de Datos y Big Data (10)', 'Fundamentos de Inteligencia Artificial (7)']],

                ['name' => 'Procesamiento de señales, visión e imágenes', 'applied' => true,
                 'evidence' => 'Pipeline propio de DSP: FFT, PSD por Welch, RMS, coherencia, fase y correlación cruzada (publicado y arbitrado).',
                 'courses' => ['Visión Artificial (10)', 'Procesamiento Digital de Imágenes (7)']],

                ['name' => 'Matemáticas, estadística y métodos numéricos', 'applied' => true,
                 'evidence' => 'Base del análisis espectral: criterio de Nyquist, resolución frecuencial y estimación estadística de espectros.',
                 'courses' => ['Cálculo Diferencial e Integral (8)', 'Probabilidad y Estadística (8)', 'Métodos Numéricos (8)', 'Geometría Analítica (8)', 'Química Básica (8)', 'Cálculo Vectorial (7)', 'Ecuaciones Diferenciales (7)', 'Álgebra (7)', 'Física General (7)']],

                ['name' => 'Investigación de operaciones y optimización', 'applied' => false,
                 'evidence' => null,
                 'courses' => ['Investigación de Operaciones (8)']],

                ['name' => 'Interfaces, visualización y desarrollo de aplicaciones', 'applied' => true,
                 'evidence' => 'Dashboards web desplegados en Vercel e interfaces de escritorio para supervisión en vivo.',
                 'courses' => ['Desarrollo de Aplicaciones Móviles (10)', 'Interacción Humano-Computadora (9)']],

                ['name' => 'Tecnologías de la información e innovación', 'applied' => true,
                 'evidence' => 'Alternativa de bajo costo frente a la instrumentación sísmica comercial, validada contra GEA y P-Alert.',
                 'courses' => ['Manejo de Tecnologías de la Información y Comunicación (10)', 'Tecnologías de Información y Comunicación (10)', 'Innovación y Desarrollo Tecnológico Sustentable (10)']],

                ['name' => 'Investigación y comunicación científica', 'applied' => true,
                 'evidence' => 'Tres artículos en Revista Vínculos y ponencia en CICOM 2025 (Tunja, Colombia).',
                 'courses' => ['Habilidades para la Comunicación de las Ideas (10)', 'Pensamiento Lógico, Heurístico y Creativo (10)', 'Seminario de Investigación I (9)', 'Trabajo de Grado III (9)', 'Seminario de Investigación II (8)', 'Trabajo de Grado I (8)', 'Trabajo de Grado II (8)', 'Proyecto de Grado (concluido)']],

                ['name' => 'Formación complementaria y vinculación', 'applied' => false,
                 'evidence' => '950 horas acreditadas entre servicio social y prácticas profesionales.',
                 'courses' => ['Taller de Emprendurismo (10)', 'Análisis del Mundo Contemporáneo (10)', 'Inglés I (7)', 'Inglés II (7)', 'Estancia Profesional (concluida)', 'Prácticas Profesionales (acreditada)', 'Servicio Social (acreditada)']],
            ],
        ],
        'education' => [
            ['period' => 'Ago 2024 – 2026', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'Maestría en Ingeniería para la Innovación y Desarrollo Tecnológico',
             'note'  => 'Opción terminal: Tecnologías de la Información y Comunicación (Plan 2023). Plan de estudios concluido; en proceso de titulación, prevista para noviembre de 2026. Tesis: «Desarrollo e implementación de un sistema de bajo costo y tecnologías IoT para el monitoreo de la salud estructural en obras civiles».',
             'stats' => ['Promedio general 9.42 / 10', 'Plan de estudios concluido', 'Sin materias reprobadas'],
             'coursework' => [
                 'label'  => 'Ver las 14 asignaturas cursadas y sus calificaciones',
                 'note'   => 'Calificación sobre 10. Estancia Profesional y Proyecto de Grado son asignaturas acreditables y no promedian.',
                 'groups' => [
                     ['name' => 'Inteligencia artificial y datos', 'items' => [
                         'Machine Learning (10)', 'Deep Learning (10)', 'Visión Artificial (10)', 'Análisis de Datos y Big Data (10)',
                     ]],
                     ['name' => 'Sistemas embebidos e IoT', 'items' => [
                         'Temas Selectos de Sistemas Embebidos (10)', 'Diseño de Sistemas IoT (9)', 'Principios Básicos de Sistemas Electrónicos (9)',
                     ]],
                     ['name' => 'Núcleo del programa', 'items' => [
                         'Tecnologías de Información y Comunicación (10)', 'Innovación y Desarrollo Tecnológico Sustentable (10)',
                         'Trabajo de Grado I (8)', 'Trabajo de Grado II (8)', 'Trabajo de Grado III (9)',
                         'Estancia Profesional (concluida)', 'Proyecto de Grado (concluido)',
                     ]],
                 ],
             ]],
            ['period' => '2018 – 2023', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'Ingeniero en Computación',
             'note'  => 'Unidad Académica de Ingeniería, Chilpancingo. Titulado en 2023. Cédula profesional federal núm. 13426767.',
             'stats' => ['Promedio general 8.56 / 10', '55 asignaturas acreditadas'],
             'coursework' => [
                 'label'  => 'Ver las 55 asignaturas cursadas y sus calificaciones',
                 'note'   => 'Calificación sobre 10. Escala de 0 a 10, mínima aprobatoria 6.',
                 'groups' => [
                     ['name' => 'Programación e ingeniería de software', 'items' => [
                         'Fundamentos de Programación (9)', 'Programación Orientada a Objetos I (10)', 'Programación Orientada a Objetos II (8)',
                         'Programación Avanzada (8)', 'Estructura de Datos I (10)', 'Estructura de Datos II (9)', 'Lógica Informática (8)',
                         'Análisis y Diseño de Sistemas (10)', 'Ingeniería de Software (8)', 'Traductores e Intérpretes (8)',
                         'Compiladores (9)', 'Desarrollo de Aplicaciones Móviles (10)', 'Interacción Humano-Computadora (9)',
                     ]],
                     ['name' => 'Hardware, electrónica y arquitectura', 'items' => [
                         'Sistemas Digitales (10)', 'Organización de Computadoras (10)', 'Arquitectura de Servidores (10)',
                         'Microcontroladores (9)', 'Microprocesadores (8)', 'Circuitos Eléctricos (9)',
                         'Electricidad y Magnetismo (10)', 'Electrónica (7)',
                     ]],
                     ['name' => 'Redes, sistemas operativos y seguridad', 'items' => [
                         'Fundamentos de Enrutamiento (10)', 'Sistemas Operativos I (10)', 'Sistemas Operativos II (8)',
                         'Fundamentos de Redes (9)', 'Sistemas de Cableado Estructurado (9)', 'Auditoría de Recursos Informáticos (9)',
                         'Seguridad en Redes (8)', 'Cómputo Forense (8)', 'Fundamentos de Comunicaciones (7)',
                     ]],
                     ['name' => 'Datos, inteligencia artificial y señales', 'items' => [
                         'Base de Datos II (9)', 'Base de Datos I (7)', 'Fundamentos de Inteligencia Artificial (7)',
                         'Procesamiento Digital de Imágenes (7)',
                     ]],
                     ['name' => 'Matemáticas y ciencias básicas', 'items' => [
                         'Cálculo Diferencial e Integral (8)', 'Probabilidad y Estadística (8)', 'Métodos Numéricos (8)',
                         'Geometría Analítica (8)', 'Investigación de Operaciones (8)', 'Química Básica (8)',
                         'Cálculo Vectorial (7)', 'Ecuaciones Diferenciales (7)', 'Álgebra (7)', 'Física General (7)',
                     ]],
                     ['name' => 'Investigación y formación institucional', 'items' => [
                         'Seminario de Investigación I (9)', 'Seminario de Investigación II (8)', 'Taller de Emprendurismo (10)',
                         'Manejo de Tecnologías de la Información y Comunicación (10)', 'Pensamiento Lógico, Heurístico y Creativo (10)',
                         'Habilidades para la Comunicación de las Ideas (10)', 'Análisis del Mundo Contemporáneo (10)',
                         'Inglés I (7)', 'Inglés II (7)',
                     ]],
                     ['name' => 'Integración y vinculación', 'items' => [
                         'Prácticas Profesionales (acreditada)', 'Servicio Social (acreditada)',
                     ]],
                 ],
             ]],
        ],
        'other' => [
            ['period' => 'Jun 2023 – Jun 2024', 'org' => 'Secretaría del Trabajo y Previsión Social', 'title' => 'Jóvenes Construyendo el Futuro — Departamento de publicaciones', 'note' => 'Capacitación laboral de 12 meses en producción e impresión digital.'],
            ['period' => 'Ago 2022 – Ene 2023', 'org' => '35ª Zona Militar (SEDENA)', 'title' => 'Prácticas profesionales — Área de Computación', 'note' => '450 horas acreditadas y liberadas. Soporte y mantenimiento de equipo de cómputo en entorno institucional.'],
            ['period' => 'Feb 2022 – Jul 2022', 'org' => 'Escuela Secundaria Ignacio Manuel Altamirano', 'title' => 'Servicio social — Área de cómputo', 'note' => '500 horas acreditadas y liberadas. Apoyo técnico y acompañamiento educativo en el sector comunitario.'],
        ],
        'skills' => [
            'Lenguajes y frameworks' => ['Python', 'C/C++ (embebidos)', 'JavaScript', 'Node.js', 'SQL', 'PHP', 'Bash', 'PyQt5 / PySide6', 'React', 'Preact'],
            'Web y mapas'        => ['Leaflet', 'GeoJSON', 'OpenStreetMap', 'PWA / Service Workers', 'Pruebas en navegador (Puppeteer)'],
            'Datos y señales'    => ['NumPy', 'Pandas', 'SciPy', 'Matplotlib', 'FFT', 'PSD/Welch', 'RMS', 'Filtros digitales', 'Series temporales'],
            'IA y aprendizaje automático' => ['Machine Learning', 'Deep Learning', 'Visión artificial', 'Big Data', 'Selección de atributos'],
            'IoT y hardware'     => ['Raspberry Pi', 'ESP32', 'Arduino Nano RP2040', 'Sensores MEMS', 'I2C', 'SPI', 'UART'],
            'Backend y nube'     => ['FastAPI', 'APIs REST', 'Supabase', 'PostgreSQL', 'Firebase', 'Vercel Functions'],
            'Sistemas y DevOps'  => ['Linux / Ubuntu Server', 'Docker', 'systemd', 'SSH', 'Git', 'GitHub Actions (CI)', 'Vercel'],
        ],
        'certs' => [
            'Data Science Essentials with Python — Cisco / UAGro (dic 2025)',
            'Cybersecurity Essentials — Cisco (mar 2022)',
            'NDG Linux Unhatched — Cisco (may 2022)',
            'CCNAv7: Introduction to Networks — Cisco (jun 2021)',
        ],
        'languages' => [
            'Español: lengua materna.',
            'Inglés: intermedio (B1), TOEFL ITP 470 puntos (2025). Lectura fluida de documentación técnica y literatura científica.',
        ],
        'contact_intro' => 'Abierto a oportunidades remotas en IoT, sistemas embebidos, procesamiento de señales, desarrollo backend y aplicaciones web.',
        'footer' => 'Sitio construido con PHP sobre Vercel. Código fuente disponible en GitHub.',
        'updated_label' => 'Última actualización',
        'updated' => 'noviembre de 2026',
        // Textos de las funciones con JavaScript (assets/cv.js)
        'ui' => [
            'buscar'        => 'Buscar en el CV',
            'buscar_ph'     => 'Python, Supabase, sensores, FFT…',
            'buscar_ayuda'  => 'También puedes hacer clic en una competencia para ver dónde la he usado.',
            'limpiar'       => 'Limpiar',
            'sin_resultados'=> 'Sin coincidencias para «%s».',
            'resultados'    => '«%s»: %d coincidencias',
            'secciones'     => ['exp' => 'experiencia', 'projects' => 'proyectos', 'skills' => 'competencias', 'pubs' => 'publicaciones', 'edu' => 'formación', 'areas' => 'áreas'],
            'filtrar'       => 'Ver dónde uso %s',
            'tema_oscuro'   => 'Cambiar a tema oscuro',
            'tema_claro'    => 'Cambiar a tema claro',
            'copiar'        => 'Copiar',
            'copiar_correo' => 'Copiar correo',
            'copiado'       => 'Correo copiado: %s',
            'compartir'     => 'Compartir',
            'enlace_copiado'=> 'Enlace copiado',
            'no_copiado'    => 'No se pudo copiar; selecciónalo a mano.',
        ],
    ];

    $en = [
        'lang'      => 'en',
        'locale'    => 'en_US',
        'role'      => 'IoT & Embedded Systems Engineer',
        'subrole'   => 'Digital Signal Processing · Scientific Software',
        'location'  => 'Mexico (GMT-6)',
        'available' => 'Available for remote work with US and European overlap',
        'meta_desc' => 'CV of Marco Antonio Adame Rodríguez, IoT and embedded systems engineer working on digital signal processing and structural health monitoring.',
        'nav'       => ['about' => 'Profile', 'exp' => 'Experience', 'projects' => 'Projects', 'skills' => 'Skills', 'pubs' => 'Publications', 'edu' => 'Education', 'areas' => 'Areas', 'contact' => 'Contact'],
        'cta_pdf'   => 'Download CV as PDF',
        'cta_design'=> 'Designed version',
        'switch'    => 'Español',
        'switch_url'=> '/',
        'summary'   => 'I build end-to-end IoT systems: MEMS sensor, embedded firmware, real-time acquisition, signal processing, Linux backend, cloud and dashboards. I designed a low-cost structural health monitoring system from scratch and validated it in the field against commercial seismic instrumentation (GEA and P-Alert); the work led to three peer-reviewed papers and an international conference talk. I also build production geospatial web applications (Supabase, Leaflet, offline-first) with automated tests and continuous deployment. Computer Engineer and MSc graduate in Engineering for Innovation and Technological Development (UAGro), 9.42/10 GPA, degree conferral expected November 2026.',
        'metric_labels' => [
            'sampling' => 'Synchronized sampling',
            'sensors'  => 'MEMS sensors integrated',
            'sites'    => 'Field validation environments',
            'papers'   => 'Peer-reviewed papers',
        ],
        'sections'  => [
            'exp'      => 'Research & development experience',
            'pubs'     => 'Publications & talks',
            'projects' => 'Projects',
            'areas'    => 'Computing areas covered',
            'edu'      => 'Education',
            'skills'   => 'Technical skills',
            'other'    => 'Additional experience',
            'contact'  => 'Contact',
        ],
        'experience' => [[
            'period' => 'Aug 2024 – Present',
            'org'    => 'Autonomous University of Guerrero (UAGro) · MIIDT graduate program',
            'title'  => 'Lead Researcher and Developer — SHM-IoT System',
            'intro'  => 'End-to-end design and implementation of a low-cost structural health monitoring solution for civil works, field-validated against commercial GEA and P-Alert equipment.',
            'groups' => [
                ['name' => 'Acquisition, firmware and hardware', 'items' => [
                    'Designed acquisition nodes on Arduino Nano RP2040 Connect and Raspberry Pi 4, integrating four MEMS sensors (MPU9250, MPU6050, LSM6DSOX and ADXL335) over I2C, SPI and UART.',
                    'Implemented synchronized 200 Hz acquisition across both RP2040 cores, with ample Nyquist margin for the structural frequency range of interest.',
                    'Validated the system in five environments — a biaxial shaking table, university buildings, a botanical garden, a high school campus and lightweight experimental structures — comparing its records against commercial GEA and P-Alert equipment.',
                ]],
                ['name' => 'Signal processing and scientific software', 'items' => [
                    'Built a Python (NumPy/SciPy) DSP pipeline to identify the dynamic response of structures: RMS, FFT, power spectral density (Welch), dominant frequency, coherence, phase analysis and cross-correlation.',
                    'Developed PyQt5 desktop applications for acquisition, live monitoring and analysis, with session control and automatic crash recovery.',
                    'Built tools to process large TXT, CSV, JSON and JSONL record sets: time synchronization, resampling, gap detection, digital filtering and series trimming.',
                    'Automated the generation of technical reports and comparative plots against reference equipment; these results underpin my lead-author paper.',
                ]],
                ['name' => 'Backend, cloud and infrastructure', 'items' => [
                    'Designed a centralized multi-site receiver over HTTP/HTTPS with local buffering, so connectivity outages do not cause data loss.',
                    'Developed REST APIs in FastAPI for ingesting, persisting and querying ESP32 node telemetry.',
                    'Administered continuously running Ubuntu servers: systemd services, Docker containers, SSH/VNC remote access, backups and synchronization.',
                    'Published web dashboards on Vercel to visualize, filter and download data from both custom nodes and commercial sensors in one place.',
                ]],
            ],
        ]],
        'talk' => [
            'label' => 'Conference talk',
            'text'  => '"Ambient Vibration Measurement System on a Biaxial Vibrating Table". 15th International Computing Conference (CICOM 2025), Tunja, Colombia, October 2025.',
        ],
        'project_text' => [
            'shm'      => ['SHM-IoT MIIDT — Low-cost structural monitoring', 'Full architecture for acquisition, storage, analysis and visualization of accelerations in civil structures, field-tested against commercial sensors.'],
            'ecosistema' => ['Tijuana seismic risk ecosystem', 'Web dashboard linking seismic hazard, soil, stations, acceleration maps, inspection and outreach on a single Supabase database. Computes routes to hospitals and emergency services over real streets (OpenStreetMap, Dijkstra) in five Baja California cities using the official CLUES health registry, works offline as an installable app, and ships with semantic versioning and automated tests on every change.'],
            'ivbc'       => ['Green Infrastructure · Baja California', 'Dashboard of 36 green-infrastructure techniques per municipality, with point queries over GeoJSON layers and address search, a login-protected editing panel, PDF and CSV reports, offline mode and a Supabase visit counter.'],
            'gps'      => ['Geo-referenced SHM monitoring network', 'Web dashboard showing location and status of distributed monitoring nodes, continuously deployed and public.'],
            'supabase' => ['Supabase sensor dashboard', 'Web application for submitting, querying and visualizing measurements stored in the cloud.'],
            'api'      => ['ESP32 telemetry APIs', 'REST services for receiving, persisting and querying acceleration data, plus scientific plotting tools.'],
            'game'       => ['Tecnito & Greencito Squad — AI educational game', 'Turn-based game for primary and secondary school classes: two mascots chat through Claude to teach earthquake preparedness and environmental care. The API key stays in a serverless function.'],
            'trading'    => ['Trading Fusion Pro 3.0', 'Educational desktop tool for technical and fundamental analysis, with cost-aware backtesting and walk-forward validation. Refactored a 4,363-line script into a modular architecture.'],
            'attendance' => ['School attendance tracker', 'App for taking roll and generating attendance reports per class, with data in Supabase and automated tests for the core logic.'],
            'crm'        => ['Jewelry store CRM', 'Customer management web app for a jewelry business, deployed on Vercel.'],
            'cv'       => ['This online CV', 'Bilingual serverless PHP site on Vercel, generated from a single data source, with a public JSON endpoint, a strict Content Security Policy and automated tests on GitHub Actions. The same data generates PDF versions of the CV tailored to each job posting.'],
        ],
        'areas' => [
            'toggle' => 'View all 17 areas and their 69 courses',
            'intro'  => 'All 69 courses passed across both degrees, organised by computing area. "Applied" marks the areas I also back with real work — systems in production, peer-reviewed publications or field validation; "Coursework" means academic coverage without an associated public project.',
            'legend' => ['applied' => 'Applied', 'coursework' => 'Coursework'],
            'items'  => [
                ['name' => 'Programming, algorithms and data structures', 'applied' => true,
                 'evidence' => 'Python and C/C++ daily: embedded firmware, processing pipelines and desktop tooling.',
                 'courses' => ['Programming Fundamentals (9)', 'Object-Oriented Programming I (10)', 'Object-Oriented Programming II (8)', 'Advanced Programming (8)', 'Data Structures I (10)', 'Data Structures II (9)', 'Computational Logic (8)']],

                ['name' => 'Software engineering and system design', 'applied' => true,
                 'evidence' => 'PyQt5 applications with session control, data export and automatic crash recovery.',
                 'courses' => ['Systems Analysis and Design (10)', 'Software Engineering (8)']],

                ['name' => 'Compilers and language theory', 'applied' => false,
                 'evidence' => null,
                 'courses' => ['Compilers (9)', 'Translators and Interpreters (8)']],

                ['name' => 'Operating systems and server administration', 'applied' => true,
                 'evidence' => 'Continuously running Ubuntu servers: systemd, SSH/VNC, backups and synchronization.',
                 'courses' => ['Operating Systems I (10)', 'Operating Systems II (8)', 'Server Architecture (10)']],

                ['name' => 'Networking and communications', 'applied' => true,
                 'evidence' => 'Multi-site monitoring with nodes on separate networks and a centralized HTTP/HTTPS receiver.',
                 'courses' => ['Routing Fundamentals (10)', 'Network Fundamentals (9)', 'Structured Cabling Systems (9)', 'Communications Fundamentals (7)']],

                ['name' => 'Security, auditing and computer forensics', 'applied' => false,
                 'evidence' => 'Complemented by the Cisco Cybersecurity Essentials and CCNAv7 certifications.',
                 'courses' => ['IT Resource Auditing (9)', 'Network Security (8)', 'Computer Forensics (8)']],

                ['name' => 'Databases and persistence', 'applied' => true,
                 'evidence' => 'PostgreSQL and Supabase as multi-origin cloud storage for telemetry.',
                 'courses' => ['Databases II (9)', 'Databases I (7)']],

                ['name' => 'Computer architecture and digital systems', 'applied' => true,
                 'evidence' => 'Synchronized acquisition on a dual-core RP2040 architecture.',
                 'courses' => ['Digital Systems (10)', 'Computer Organization (10)', 'Microprocessors (8)']],

                ['name' => 'Embedded systems, IoT and electronics', 'applied' => true,
                 'evidence' => 'The core of my work: RP2040 and Raspberry Pi nodes with MEMS sensors over I2C, SPI and UART.',
                 'courses' => ['Selected Topics in Embedded Systems (10)', 'Electricity and Magnetism (10)', 'Microcontrollers (9)', 'IoT Systems Design (9)', 'Electrical Circuits (9)', 'Fundamentals of Electronic Systems (9)', 'Electronics (7)']],

                ['name' => 'Artificial intelligence and machine learning', 'applied' => true,
                 'evidence' => 'Co-author of the paper on feature-importance-based attribute selection for dengue case classification.',
                 'courses' => ['Machine Learning (10)', 'Deep Learning (10)', 'Data Analysis and Big Data (10)', 'Artificial Intelligence Fundamentals (7)']],

                ['name' => 'Signal, vision and image processing', 'applied' => true,
                 'evidence' => 'Custom DSP pipeline: FFT, Welch PSD, RMS, coherence, phase and cross-correlation (published and peer-reviewed).',
                 'courses' => ['Computer Vision (10)', 'Digital Image Processing (7)']],

                ['name' => 'Mathematics, statistics and numerical methods', 'applied' => true,
                 'evidence' => 'The basis of the spectral analysis: Nyquist criterion, frequency resolution and statistical spectrum estimation.',
                 'courses' => ['Differential and Integral Calculus (8)', 'Probability and Statistics (8)', 'Numerical Methods (8)', 'Analytic Geometry (8)', 'Basic Chemistry (8)', 'Vector Calculus (7)', 'Differential Equations (7)', 'Algebra (7)', 'General Physics (7)']],

                ['name' => 'Operations research and optimization', 'applied' => false,
                 'evidence' => null,
                 'courses' => ['Operations Research (8)']],

                ['name' => 'Interfaces, visualization and application development', 'applied' => true,
                 'evidence' => 'Web dashboards deployed on Vercel and desktop interfaces for live monitoring.',
                 'courses' => ['Mobile Application Development (10)', 'Human-Computer Interaction (9)']],

                ['name' => 'Information technology and innovation', 'applied' => true,
                 'evidence' => 'A low-cost alternative to commercial seismic instrumentation, validated against GEA and P-Alert.',
                 'courses' => ['Managing Information and Communication Technologies (10)', 'Information and Communication Technologies (10)', 'Sustainable Innovation and Technological Development (10)']],

                ['name' => 'Research and scientific communication', 'applied' => true,
                 'evidence' => 'Three papers in Revista Vínculos and a talk at CICOM 2025 (Tunja, Colombia).',
                 'courses' => ['Communication Skills (10)', 'Logical, Heuristic and Creative Thinking (10)', 'Research Seminar I (9)', 'Graduate Research III (9)', 'Research Seminar II (8)', 'Graduate Research I (8)', 'Graduate Research II (8)', 'Graduation Project (completed)']],

                ['name' => 'General education and practicum', 'applied' => false,
                 'evidence' => '950 certified hours across social service and a professional internship.',
                 'courses' => ['Entrepreneurship Workshop (10)', 'Contemporary World Analysis (10)', 'English I (7)', 'English II (7)', 'Professional Residency (completed)', 'Professional Internship (completed)', 'Social Service (completed)']],
            ],
        ],
        'education' => [
            ['period' => 'Aug 2024 – 2026', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'MSc in Engineering for Innovation and Technological Development',
             'note'  => 'Terminal specialization: Information and Communication Technologies (2023 curriculum). All coursework completed; degree conferral in progress, expected November 2026. Thesis: "Development and implementation of a low-cost IoT system for structural health monitoring in civil works".',
             'stats' => ['GPA 9.42 / 10', 'All coursework completed', 'No failed courses'],
             'coursework' => [
                 'label'  => 'View all 14 graduate courses and grades',
                 'note'   => 'Grades on a 0–10 scale. The Professional Residency and Graduation Project are pass/fail and excluded from the GPA.',
                 'groups' => [
                     ['name' => 'Artificial intelligence and data', 'items' => [
                         'Machine Learning (10)', 'Deep Learning (10)', 'Computer Vision (10)', 'Data Analysis and Big Data (10)',
                     ]],
                     ['name' => 'Embedded systems and IoT', 'items' => [
                         'Selected Topics in Embedded Systems (10)', 'IoT Systems Design (9)', 'Fundamentals of Electronic Systems (9)',
                     ]],
                     ['name' => 'Programme core', 'items' => [
                         'Information and Communication Technologies (10)', 'Sustainable Innovation and Technological Development (10)',
                         'Graduate Research I (8)', 'Graduate Research II (8)', 'Graduate Research III (9)',
                         'Professional Residency (completed)', 'Graduation Project (completed)',
                     ]],
                 ],
             ]],
            ['period' => '2018 – 2023', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'BSc in Computer Engineering',
             'note'  => 'School of Engineering, Chilpancingo. Degree awarded 2023. Federal professional license no. 13426767.',
             'stats' => ['GPA 8.56 / 10', '55 courses passed'],
             'coursework' => [
                 'label'  => 'View all 55 undergraduate courses and grades',
                 'note'   => 'Grades on a 0–10 scale; 6 is the minimum passing grade.',
                 'groups' => [
                     ['name' => 'Programming and software engineering', 'items' => [
                         'Programming Fundamentals (9)', 'Object-Oriented Programming I (10)', 'Object-Oriented Programming II (8)',
                         'Advanced Programming (8)', 'Data Structures I (10)', 'Data Structures II (9)', 'Computational Logic (8)',
                         'Systems Analysis and Design (10)', 'Software Engineering (8)', 'Translators and Interpreters (8)',
                         'Compilers (9)', 'Mobile Application Development (10)', 'Human-Computer Interaction (9)',
                     ]],
                     ['name' => 'Hardware, electronics and architecture', 'items' => [
                         'Digital Systems (10)', 'Computer Organization (10)', 'Server Architecture (10)',
                         'Microcontrollers (9)', 'Microprocessors (8)', 'Electrical Circuits (9)',
                         'Electricity and Magnetism (10)', 'Electronics (7)',
                     ]],
                     ['name' => 'Networking, operating systems and security', 'items' => [
                         'Routing Fundamentals (10)', 'Operating Systems I (10)', 'Operating Systems II (8)',
                         'Network Fundamentals (9)', 'Structured Cabling Systems (9)', 'IT Resource Auditing (9)',
                         'Network Security (8)', 'Computer Forensics (8)', 'Communications Fundamentals (7)',
                     ]],
                     ['name' => 'Data, artificial intelligence and signals', 'items' => [
                         'Databases II (9)', 'Databases I (7)', 'Artificial Intelligence Fundamentals (7)',
                         'Digital Image Processing (7)',
                     ]],
                     ['name' => 'Mathematics and basic sciences', 'items' => [
                         'Differential and Integral Calculus (8)', 'Probability and Statistics (8)', 'Numerical Methods (8)',
                         'Analytic Geometry (8)', 'Operations Research (8)', 'Basic Chemistry (8)',
                         'Vector Calculus (7)', 'Differential Equations (7)', 'Algebra (7)', 'General Physics (7)',
                     ]],
                     ['name' => 'Research and general education', 'items' => [
                         'Research Seminar I (9)', 'Research Seminar II (8)', 'Entrepreneurship Workshop (10)',
                         'Managing Information and Communication Technologies (10)', 'Logical, Heuristic and Creative Thinking (10)',
                         'Communication Skills (10)', 'Contemporary World Analysis (10)',
                         'English I (7)', 'English II (7)',
                     ]],
                     ['name' => 'Practicum', 'items' => [
                         'Professional Internship (completed)', 'Social Service (completed)',
                     ]],
                 ],
             ]],
        ],
        'other' => [
            ['period' => 'Jun 2023 – Jun 2024', 'org' => 'Ministry of Labour (Mexico)', 'title' => 'Jóvenes Construyendo el Futuro — Publishing department', 'note' => '12-month vocational training programme in digital production and printing.'],
            ['period' => 'Aug 2022 – Jan 2023', 'org' => '35th Military Zone (Mexican Army)', 'title' => 'Professional internship — IT area', 'note' => '450 certified hours. Computer equipment support and maintenance in an institutional environment.'],
            ['period' => 'Feb 2022 – Jul 2022', 'org' => 'Ignacio Manuel Altamirano Secondary School', 'title' => 'Social service — Computing area', 'note' => '500 certified hours. Technical support and community-sector educational support.'],
        ],
        'skills' => [
            'Languages & frameworks' => ['Python', 'C/C++ (embedded)', 'JavaScript', 'Node.js', 'SQL', 'PHP', 'Bash', 'PyQt5 / PySide6', 'React', 'Preact'],
            'Web & mapping'     => ['Leaflet', 'GeoJSON', 'OpenStreetMap', 'PWA / Service Workers', 'Browser testing (Puppeteer)'],
            'Data & signals'    => ['NumPy', 'Pandas', 'SciPy', 'Matplotlib', 'FFT', 'PSD/Welch', 'RMS', 'Digital filters', 'Time series'],
            'AI & machine learning' => ['Machine Learning', 'Deep Learning', 'Computer Vision', 'Big Data', 'Feature selection'],
            'IoT & hardware'    => ['Raspberry Pi', 'ESP32', 'Arduino Nano RP2040', 'MEMS sensors', 'I2C', 'SPI', 'UART'],
            'Backend & cloud'   => ['FastAPI', 'REST APIs', 'Supabase', 'PostgreSQL', 'Firebase', 'Vercel Functions'],
            'Systems & DevOps'  => ['Linux / Ubuntu Server', 'Docker', 'systemd', 'SSH', 'Git', 'GitHub Actions (CI)', 'Vercel'],
        ],
        'certs' => [
            'Data Science Essentials with Python — Cisco / UAGro (Dec 2025)',
            'Cybersecurity Essentials — Cisco (Mar 2022)',
            'NDG Linux Unhatched — Cisco (May 2022)',
            'CCNAv7: Introduction to Networks — Cisco (Jun 2021)',
        ],
        'languages' => [
            'Spanish: native.',
            'English: intermediate (B1), TOEFL ITP 470 (2025). Fluent reading of technical documentation and scientific literature.',
        ],
        'contact_intro' => 'Open to remote opportunities in IoT, embedded systems, signal processing, backend development and web applications.',
        'footer' => 'Built with PHP on Vercel. Source code available on GitHub.',
        'updated_label' => 'Last updated',
        'updated' => 'November 2026',
        // Strings for the JavaScript features (assets/cv.js)
        'ui' => [
            'buscar'        => 'Search this CV',
            'buscar_ph'     => 'Python, Supabase, sensors, FFT…',
            'buscar_ayuda'  => 'You can also click a skill to see where I have used it.',
            'limpiar'       => 'Clear',
            'sin_resultados'=> 'No matches for "%s".',
            'resultados'    => '"%s": %d matches',
            'secciones'     => ['exp' => 'experience', 'projects' => 'projects', 'skills' => 'skills', 'pubs' => 'publications', 'edu' => 'education', 'areas' => 'areas'],
            'filtrar'       => 'See where I use %s',
            'tema_oscuro'   => 'Switch to dark theme',
            'tema_claro'    => 'Switch to light theme',
            'copiar'        => 'Copy',
            'copiar_correo' => 'Copy email',
            'copiado'       => 'Email copied: %s',
            'compartir'     => 'Share',
            'enlace_copiado'=> 'Link copied',
            'no_copiado'    => 'Could not copy; please select it manually.',
        ],
    ];

    $d = $lang === 'en' ? $en : $es;
    $d['profile'] = PROFILE;
    $d['publications'] = SHARED['publications'];
    $d['projects'] = SHARED['projects'];
    $d['metrics'] = SHARED['metrics'];
    $d['pdf'] = $lang === 'en'
        ? ['plain' => '/assets/cv-en.pdf', 'design' => '/assets/cv-en-design.pdf']
        : ['plain' => '/assets/cv-es.pdf', 'design' => '/assets/cv-es-diseno.pdf'];

    return $d;
}
