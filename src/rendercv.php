<?php
declare(strict_types=1);

/**
 * Convierte el CV de data.php al formato de RenderCV (https://rendercv.com).
 * Lo usan herramientas/rendercv.php (para generar los PDF) y tests/humo.php
 * (para comprobar que los PDF publicados corresponden a los datos actuales).
 *
 * $completo = true es el PDF sencillo que descarga el sitio: logros, los proyectos
 * marcados con 'pdf' => true y las asignaturas. false es la base para adaptar a una
 * vacante (todos los proyectos, sin asignaturas).
 */

/** Escapa los caracteres que RenderCV interpreta como Markdown en textos que no lo son. */
function rcv_md(string $s): string
{
    return str_replace(['*', '_', '[', ']'], ['\*', '\_', '\[', '\]'], $s);
}

function cv_rendercv(string $lang, bool $completo = true, string $sitio = ''): array
{
    $d = cv_data($lang);
    $en = $lang === 'en';
    $t = $en
        ? ['summary' => 'Profile', 'courses' => 'Courses', 'certs' => 'Certifications', 'langs' => 'Languages', 'language' => 'english']
        : ['summary' => 'Perfil profesional', 'courses' => 'Asignaturas', 'certs' => 'Certificaciones', 'langs' => 'Idiomas', 'language' => 'spanish'];
    $sec = $d['sections'];

    $logros = array_map(static fn (array $a): array => ['bullet' => '**' . rcv_md($a['title']) . '** ' . rcv_md($a['text'])], $d['achievements']);

    $experiencia = [];
    foreach ($d['experience'] as $job) {
        $puntos = [];
        foreach ($job['groups'] as $g) {
            foreach ($g['items'] as $it) {
                $puntos[] = rcv_md($it);
            }
        }
        $experiencia[] = ['company' => rcv_md($job['org']), 'position' => rcv_md($job['title']), 'date' => $job['period'],
                          'summary' => rcv_md($job['intro']), 'highlights' => $puntos];
    }

    $proyectos = [];
    foreach ($d['projects'] as $p) {
        if ($completo && empty($p['pdf'])) {
            continue;
        }
        [$titulo, $desc] = $d['project_text'][$p['key']];
        $proyectos[] = [
            'name'       => $p['url'] ? '[' . rcv_md($titulo) . '](' . $p['url'] . ')' : rcv_md($titulo),
            'summary'    => rcv_md($desc),
            'highlights' => [rcv_md($p['stack'])],
        ];
    }

    $publicaciones = [];
    foreach ($d['publications'] as $p) {
        $autores = array_map(
            static fn (string $a): string => str_starts_with($a, 'Adame Rodríguez') ? '**' . $a . '**' : $a,
            preg_split('/(?<=\.),\s+(?=[A-ZÁÉÍÓÚÑ])/u', $p['authors'])  // «Apellido, I. N., Apellido, I.» → uno por elemento
        );
        $publicaciones[] = ['title' => rcv_md($p['title'][$lang]) . ' (' . rcv_md($p['role'][$lang]) . ')', 'authors' => $autores,
                            // El año va con la revista: como fecha, RenderCV lo mostraría como «Ene 2026».
                            'journal' => $p['venue'] . ', ' . $p['year'], 'url' => $p['url']];
    }
    $publicaciones[] = '**' . rcv_md($d['talk']['label']) . ':** ' . rcv_md($d['talk']['text']);

    $formacion = [];
    foreach ($d['education'] as $ed) {
        $puntos = [rcv_md(implode(' · ', $ed['stats'] ?? [])), rcv_md($ed['note'])];
        if ($completo && !empty($ed['coursework'])) {
            foreach ($ed['coursework']['groups'] as $g) {
                $puntos[] = '**' . rcv_md($g['name']) . ':** ' . rcv_md(implode(', ', $g['items']));
            }
        }
        $formacion[] = ['institution' => $ed['org'], 'area' => rcv_md($ed['title']), 'date' => $ed['period'], 'highlights' => $puntos];
    }

    $otra = array_map(static fn (array $o): array => ['company' => rcv_md($o['org']), 'position' => rcv_md($o['title']),
                                                     'date' => $o['period'], 'summary' => rcv_md($o['note'])], $d['other']);

    $competencias = [];
    foreach ($d['skills'] as $grupo => $items) {
        $competencias[] = ['label' => rcv_md($grupo), 'details' => rcv_md(implode(', ', $items))];
    }

    // Una sección con un solo tipo de entrada: la ponencia (texto) no puede ir con las
    // publicaciones (PublicationEntry), así que va en su propia sección sin título extra.
    $pubs = array_values(array_filter($publicaciones, 'is_array'));
    $charla = array_values(array_filter($publicaciones, 'is_string'));

    $secciones = [$t['summary'] => [rcv_md($d['summary'])]];
    if ($completo) {
        $secciones[$sec['logros']] = $logros;
    }
    $secciones += [
        $sec['exp']      => $experiencia,
        $sec['skills']   => $competencias,
        $sec['projects'] => $proyectos,
        $sec['pubs']     => $pubs,
        $d['talk']['label'] => $charla,
        $sec['edu']      => $formacion,
        $sec['other']    => $otra,
        $t['certs']      => array_map(static fn ($c) => ['bullet' => rcv_md($c)], $d['certs']),
        $t['langs']      => array_map(static fn ($l) => ['bullet' => rcv_md($l)], $d['languages']),
    ];

    return [
        'cv' => array_filter([
            'name'     => $d['profile']['name'],
            'headline' => $d['role'] . ' · ' . $d['subrole'],
            'location' => $d['location'],
            'email'    => $d['profile']['email'],
            'phone'    => preg_replace('/\s+/', '', $d['profile']['phone']),
            'website'  => $sitio !== '' ? rtrim($sitio, '/') . ($en ? '/en' : '/') : null,
            'social_networks' => [
                ['network' => 'LinkedIn', 'username' => rawurldecode(basename(rtrim($d['profile']['linkedin'], '/')))],
                ['network' => 'GitHub', 'username' => basename($d['profile']['github'])],
                ['network' => 'ORCID', 'username' => basename($d['profile']['orcid'])],
            ],
            'sections' => $secciones,
        ], static fn ($v) => $v !== null),
        // Teléfono con +52: el CV se manda a vacantes remotas fuera de México.
        // Compacto para que quepa en pocas páginas; las entradas largas (la experiencia)
        // se pueden partir entre páginas para no dejar medias hojas en blanco.
        'design' => [
            'theme'      => 'classic',
            'page'       => ['size' => 'us-letter', 'top_margin' => '0.5in', 'bottom_margin' => '0.5in', 'left_margin' => '0.55in', 'right_margin' => '0.55in'],
            'typography' => ['font_size' => ['body' => '9.5pt', 'name' => '24pt'], 'line_spacing' => '0.5em'],
            'header'     => ['space_below_name' => '0.4cm', 'space_below_headline' => '0.4cm', 'space_below_connections' => '0.4cm',
                             'connections' => ['phone_number_format' => 'international']],
            'section_titles' => ['space_above' => '0.35cm', 'space_below' => '0.2cm'],
            'sections'   => ['space_between_regular_entries' => '0.7em'],
            'entries'    => ['allow_page_break' => true],
        ],
        'locale' => ['language' => $t['language']],
        // Sin «Última actualización» con la fecha del día: así el mismo data.php da el mismo PDF.
        'settings' => ['current_date' => CV_UPDATED],
    ];
}

/** Huella de lo que se mandó a RenderCV; si data.php cambia, cambia la huella. */
function cv_rendercv_huella(string $lang, string $sitio): string
{
    return hash('sha256', json_encode(cv_rendercv($lang, true, $sitio), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}
