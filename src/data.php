<?php
declare(strict_types=1);

/**
 * Fuente única de verdad del CV.
 * Todo el sitio (HTML y API JSON, en ambos idiomas) se genera desde este archivo.
 * Para actualizar el CV en línea basta con editar aquí y hacer push.
 */

const PROFILE = [
    'name'    => 'Marco Antonio Adame Rodríguez',
    'email'   => 'ragnarockgames86@gmail.com',
    'phone'   => '+52 747 334 2910',
    'github'  => 'https://github.com/Ryukert',
    'linkedin'=> 'https://www.linkedin.com/in/marco-antonio-adame-rodr%C3%ADguez-211615227/',
    'orcid'   => 'https://orcid.org/0009-0003-2361-4945',
    'photo'   => '/assets/foto.jpg',
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
            'key'   => 'shm',
            'stack' => 'Python · C++ · Raspberry Pi · PyQt5',
            'url'   => null,
        ],
        [
            'key'   => 'gps',
            'stack' => 'JavaScript · Vercel',
            'url'   => 'https://github.com/Ryukert/SHM_GPS_NETWORK_DASH_elite',
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
    ],
    'metrics' => [
        ['value' => '200 Hz', 'key' => 'sampling'],
        ['value' => '2048',   'key' => 'fft'],
        ['value' => '0.98 Hz','key' => 'resolution'],
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
        'nav'       => ['about' => 'Perfil', 'exp' => 'Experiencia', 'pubs' => 'Publicaciones', 'projects' => 'Proyectos', 'edu' => 'Formación', 'contact' => 'Contacto'],
        'cta_pdf'   => 'Descargar CV en PDF',
        'cta_design'=> 'Versión con diseño',
        'switch'    => 'English',
        'switch_url'=> '/en',
        'summary'   => 'Ingeniero en Computación y candidato a Maestro en Ingeniería e Innovación y Desarrollo Tecnológico (UAGro, titulación prevista noviembre 2026). Diseño sistemas IoT de extremo a extremo: sensor MEMS, firmware embebido, adquisición en tiempo real, backend en Linux, almacenamiento en la nube y dashboard de visualización. Mi trabajo de posgrado es un sistema propio de monitoreo de salud estructural de bajo costo, validado en campo contra instrumentación sísmica comercial.',
        'metric_labels' => [
            'sampling'   => 'Muestreo sincronizado',
            'fft'        => 'Puntos por ventana FFT',
            'resolution' => 'Resolución frecuencial',
            'papers'     => 'Artículos publicados',
        ],
        'sections'  => [
            'exp'      => 'Experiencia en investigación y desarrollo',
            'pubs'     => 'Publicaciones y ponencias',
            'projects' => 'Proyectos',
            'edu'      => 'Formación académica',
            'skills'   => 'Competencias técnicas',
            'other'    => 'Otra experiencia',
            'contact'  => 'Contacto',
        ],
        'experience' => [[
            'period' => 'Ago 2024 – Actualidad',
            'org'    => 'UAGro · Posgrado MIIDT',
            'title'  => 'Investigador y desarrollador principal — Sistema SHM-IoT',
            'intro'  => 'Diseño e implementación de extremo a extremo de una solución de bajo costo para monitoreo de salud estructural en obras civiles, validada en campo frente a los equipos comerciales GEA y P-Alert.',
            'groups' => [
                ['name' => 'Adquisición, firmware y hardware', 'items' => [
                    'Integré nodos sobre Arduino Nano RP2040 Connect y Raspberry Pi 4 con sensores MEMS MPU9250/MPU6050, LSM6DSOX y ADXL335 por I2C, SPI y UART.',
                    'Implementé adquisición sincronizada en arquitectura dual-core a 200 Hz, cumpliendo el criterio de Nyquist para el rango estructural de interés.',
                    'Validé el sistema en mesa vibradora biaxial, edificios universitarios, Jardín Botánico, COBACH y estructuras experimentales ligeras.',
                ]],
                ['name' => 'Procesamiento de señales y software científico', 'items' => [
                    'Construí el pipeline de DSP: RMS, FFT, densidad espectral de potencia (Welch), frecuencia dominante, coherencia, fase y correlación cruzada.',
                    'Desarrollé aplicaciones en Python y PyQt5 para adquisición, supervisión en vivo y análisis, con control de sesiones y recuperación automática ante fallos.',
                    'Automaticé la generación de informes técnicos y gráficas comparativas contra equipos de referencia.',
                ]],
                ['name' => 'Backend, nube e infraestructura', 'items' => [
                    'Levanté un receptor centralizado multi-sede sobre HTTP/HTTPS con buffer local que evita la pérdida de datos ante caídas de conectividad.',
                    'Desarrollé APIs REST en FastAPI para recepción, persistencia y consulta de telemetría de nodos ESP32.',
                    'Administré servidores Ubuntu en operación continua: systemd, SSH/VNC, Docker, respaldo y sincronización.',
                    'Publiqué dashboards web para visualizar, filtrar y descargar datos de nodos propios y de sensores comerciales.',
                ]],
            ],
        ]],
        'talk' => [
            'label' => 'Ponencia',
            'text'  => '«Sistema de medición de vibraciones ambientales en mesa vibradora biaxial». 15.º Congreso Internacional de Computación (CICOM 2025), Tunja, Colombia, octubre 2025.',
        ],
        'project_text' => [
            'shm'      => ['SHM-IoT MIIDT — Monitoreo estructural de bajo costo', 'Arquitectura integral de adquisición, almacenamiento, análisis y visualización de aceleraciones en estructuras civiles, probada en campo contra sensores comerciales.'],
            'gps'      => ['Red de monitoreo SHM con georreferenciación', 'Dashboard web para visualizar ubicación y estado de los nodos distribuidos, con despliegue público continuo.'],
            'supabase' => ['Dashboard de sensores sobre Supabase', 'Aplicación web para envío, consulta y visualización de mediciones almacenadas en la nube.'],
            'api'      => ['APIs de telemetría para nodos ESP32', 'Servicios REST para recepción, persistencia y consulta de datos de aceleración, con graficación científica.'],
        ],
        'education' => [
            ['period' => 'Ago 2024 – Nov 2026', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'Maestría en Ingeniería e Innovación y Desarrollo Tecnológico',
             'note'  => 'Tesis: «Desarrollo e implementación de un sistema de bajo costo y tecnologías IoT para el monitoreo de la salud estructural en obras civiles». En etapa de culminación.'],
            ['period' => '2018 – 2022', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'Ingeniero en Computación',
             'note'  => 'Titulado en 2023. Cédula profesional federal núm. 13426767.'],
        ],
        'other' => [
            ['period' => 'Jun 2023 – Jun 2024', 'org' => 'Secretaría del Trabajo y Previsión Social', 'title' => 'Jóvenes Construyendo el Futuro — Departamento de publicaciones', 'note' => 'Capacitación laboral de 12 meses en producción e impresión digital.'],
            ['period' => 'Ago 2022 – Ene 2023', 'org' => '35ª Zona Militar (SEDENA)', 'title' => 'Prácticas profesionales — Área de Computación', 'note' => '450 horas acreditadas y liberadas. Soporte y mantenimiento de equipo de cómputo en entorno institucional.'],
            ['period' => 'Feb 2022 – Jul 2022', 'org' => 'Escuela Secundaria Ignacio Manuel Altamirano', 'title' => 'Servicio social — Área de cómputo', 'note' => '500 horas acreditadas y liberadas. Apoyo técnico y acompañamiento educativo en el sector comunitario.'],
        ],
        'skills' => [
            'Lenguajes'          => ['Python', 'C/C++ (embebidos)', 'JavaScript', 'SQL', 'PHP', 'Bash'],
            'Datos y señales'    => ['NumPy', 'Pandas', 'SciPy', 'Matplotlib', 'FFT', 'PSD/Welch', 'RMS', 'Filtros digitales', 'Series temporales'],
            'IoT y hardware'     => ['Raspberry Pi', 'ESP32', 'Arduino Nano RP2040', 'Sensores MEMS', 'I2C', 'SPI', 'UART'],
            'Backend y nube'     => ['FastAPI', 'APIs REST', 'Supabase', 'PostgreSQL', 'Firebase'],
            'Sistemas y DevOps'  => ['Linux / Ubuntu Server', 'Docker', 'systemd', 'SSH', 'Git', 'Vercel'],
        ],
        'certs' => [
            'Data Science Essentials with Python — Cisco / UAGro (dic 2025)',
            'Cybersecurity Essentials — Cisco (mar 2022)',
            'NDG Linux Unhatched — Cisco (may 2022)',
            'CCNAv7: Introduction to Networks — Cisco (jun 2021)',
        ],
        'languages' => [
            'Español: lengua materna.',
            'Inglés: intermedio (B1), TOEFL ITP 470 puntos (2025). Lectura fluida de documentación técnica.',
        ],
        'contact_intro' => 'Abierto a oportunidades remotas en IoT, sistemas embebidos, procesamiento de señales y desarrollo backend.',
        'footer' => 'Sitio construido con PHP sobre Vercel. Código fuente disponible en GitHub.',
    ];

    $en = [
        'lang'      => 'en',
        'locale'    => 'en_US',
        'role'      => 'IoT & Embedded Systems Engineer',
        'subrole'   => 'Digital Signal Processing · Scientific Software',
        'location'  => 'Mexico (GMT-6)',
        'available' => 'Available for remote work with US and European overlap',
        'meta_desc' => 'CV of Marco Antonio Adame Rodríguez, IoT and embedded systems engineer working on digital signal processing and structural health monitoring.',
        'nav'       => ['about' => 'Profile', 'exp' => 'Experience', 'pubs' => 'Publications', 'projects' => 'Projects', 'edu' => 'Education', 'contact' => 'Contact'],
        'cta_pdf'   => 'Download CV as PDF',
        'cta_design'=> 'Designed version',
        'switch'    => 'Español',
        'switch_url'=> '/',
        'summary'   => 'Computer Engineer and MSc candidate in Engineering, Innovation and Technological Development (UAGro, expected November 2026). I build end-to-end IoT systems: MEMS sensor, embedded firmware, real-time acquisition, Linux backend, cloud storage and visualization dashboard. My graduate work is a low-cost structural health monitoring system I designed from scratch and validated in the field against commercial seismic instrumentation.',
        'metric_labels' => [
            'sampling'   => 'Synchronized sampling',
            'fft'        => 'Points per FFT window',
            'resolution' => 'Frequency resolution',
            'papers'     => 'Published papers',
        ],
        'sections'  => [
            'exp'      => 'Research & development experience',
            'pubs'     => 'Publications & talks',
            'projects' => 'Projects',
            'edu'      => 'Education',
            'skills'   => 'Technical skills',
            'other'    => 'Additional experience',
            'contact'  => 'Contact',
        ],
        'experience' => [[
            'period' => 'Aug 2024 – Present',
            'org'    => 'UAGro · MIIDT graduate program',
            'title'  => 'Lead Researcher and Developer — SHM-IoT System',
            'intro'  => 'End-to-end design and implementation of a low-cost structural health monitoring solution for civil works, field-validated against commercial GEA and P-Alert equipment.',
            'groups' => [
                ['name' => 'Acquisition, firmware and hardware', 'items' => [
                    'Integrated acquisition nodes on Arduino Nano RP2040 Connect and Raspberry Pi 4 with MPU9250/MPU6050, LSM6DSOX and ADXL335 MEMS sensors over I2C, SPI and UART.',
                    'Implemented synchronized dual-core acquisition at 200 Hz, satisfying the Nyquist criterion for the structural frequency range of interest.',
                    'Validated the system on a biaxial shaking table, university buildings, a botanical garden, a high school campus and lightweight experimental structures.',
                ]],
                ['name' => 'Signal processing and scientific software', 'items' => [
                    'Built the DSP pipeline: RMS, FFT, power spectral density (Welch), dominant frequency, coherence, phase analysis and cross-correlation.',
                    'Developed Python and PyQt5 applications for acquisition, live monitoring and analysis, with session control and automatic crash recovery.',
                    'Automated the generation of technical reports and comparative plots against reference equipment.',
                ]],
                ['name' => 'Backend, cloud and infrastructure', 'items' => [
                    'Stood up a centralized multi-site receiver over HTTP/HTTPS with local buffering that prevents data loss during connectivity outages.',
                    'Developed REST APIs in FastAPI for ingesting, persisting and querying ESP32 node telemetry.',
                    'Administered continuously running Ubuntu servers: systemd, SSH/VNC, Docker, backup and synchronization.',
                    'Published web dashboards to visualize, filter and download data from both custom nodes and commercial sensors.',
                ]],
            ],
        ]],
        'talk' => [
            'label' => 'Conference talk',
            'text'  => '"Ambient Vibration Measurement System on a Biaxial Vibrating Table". 15th International Computing Conference (CICOM 2025), Tunja, Colombia, October 2025.',
        ],
        'project_text' => [
            'shm'      => ['SHM-IoT MIIDT — Low-cost structural monitoring', 'Full architecture for acquisition, storage, analysis and visualization of accelerations in civil structures, field-tested against commercial sensors.'],
            'gps'      => ['Geo-referenced SHM monitoring network', 'Web dashboard showing location and status of distributed monitoring nodes, continuously deployed and public.'],
            'supabase' => ['Supabase sensor dashboard', 'Web application for submitting, querying and visualizing measurements stored in the cloud.'],
            'api'      => ['ESP32 telemetry APIs', 'REST services for receiving, persisting and querying acceleration data, plus scientific plotting tools.'],
        ],
        'education' => [
            ['period' => 'Aug 2024 – Nov 2026', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'MSc in Engineering, Innovation and Technological Development',
             'note'  => 'Thesis: "Development and implementation of a low-cost IoT system for structural health monitoring in civil works". In final stage.'],
            ['period' => '2018 – 2022', 'org' => 'Universidad Autónoma de Guerrero',
             'title' => 'BSc in Computer Engineering',
             'note'  => 'Degree awarded 2023. Federal professional license no. 13426767.'],
        ],
        'other' => [
            ['period' => 'Jun 2023 – Jun 2024', 'org' => 'Ministry of Labour (Mexico)', 'title' => 'Jóvenes Construyendo el Futuro — Publishing department', 'note' => '12-month vocational training programme in digital production and printing.'],
            ['period' => 'Aug 2022 – Jan 2023', 'org' => '35th Military Zone (Mexican Army)', 'title' => 'Professional internship — IT area', 'note' => '450 certified hours. Computer equipment support and maintenance in an institutional environment.'],
            ['period' => 'Feb 2022 – Jul 2022', 'org' => 'Ignacio Manuel Altamirano Secondary School', 'title' => 'Social service — Computing area', 'note' => '500 certified hours. Technical support and community-sector educational support.'],
        ],
        'skills' => [
            'Languages'         => ['Python', 'C/C++ (embedded)', 'JavaScript', 'SQL', 'PHP', 'Bash'],
            'Data & signals'    => ['NumPy', 'Pandas', 'SciPy', 'Matplotlib', 'FFT', 'PSD/Welch', 'RMS', 'Digital filters', 'Time series'],
            'IoT & hardware'    => ['Raspberry Pi', 'ESP32', 'Arduino Nano RP2040', 'MEMS sensors', 'I2C', 'SPI', 'UART'],
            'Backend & cloud'   => ['FastAPI', 'REST APIs', 'Supabase', 'PostgreSQL', 'Firebase'],
            'Systems & DevOps'  => ['Linux / Ubuntu Server', 'Docker', 'systemd', 'SSH', 'Git', 'Vercel'],
        ],
        'certs' => [
            'Data Science Essentials with Python — Cisco / UAGro (Dec 2025)',
            'Cybersecurity Essentials — Cisco (Mar 2022)',
            'NDG Linux Unhatched — Cisco (May 2022)',
            'CCNAv7: Introduction to Networks — Cisco (Jun 2021)',
        ],
        'languages' => [
            'Spanish: native.',
            'English: intermediate (B1), TOEFL ITP 470 (2025). Fluent reading of technical documentation.',
        ],
        'contact_intro' => 'Open to remote opportunities in IoT, embedded systems, signal processing and backend development.',
        'footer' => 'Built with PHP on Vercel. Source code available on GitHub.',
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
