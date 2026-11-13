<?php /** @var array $d */ ?>
<!DOCTYPE html>
<html lang="<?= e($d['lang']) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($d['profile']['name']) ?> — <?= e($d['role']) ?></title>
<meta name="description" content="<?= e($d['meta_desc']) ?>">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#1b2a4a" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#0f1521" media="(prefers-color-scheme: dark)">
<meta name="color-scheme" content="light dark">
<meta property="og:title" content="<?= e($d['profile']['name']) ?> — <?= e($d['role']) ?>">
<meta property="og:description" content="<?= e($d['meta_desc']) ?>">
<meta property="og:type" content="profile">
<meta property="og:locale" content="<?= e($d['locale']) ?>">
<meta property="og:locale:alternate" content="<?= $d['lang'] === 'en' ? 'es_MX' : 'en_US' ?>">
<meta property="og:url" content="<?= e(site_url($d['lang'] === 'en' ? '/en' : '/')) ?>">
<meta property="og:image" content="<?= e(site_url(asset('og.jpg'))) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= e($d['profile']['name']) ?> — <?= e($d['role']) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($d['profile']['name']) ?> — <?= e($d['role']) ?>">
<meta name="twitter:description" content="<?= e($d['meta_desc']) ?>">
<meta name="twitter:image" content="<?= e(site_url(asset('og.jpg'))) ?>">
<link rel="canonical" href="<?= e(site_url($d['lang'] === 'en' ? '/en' : '/')) ?>">
<link rel="alternate" hreflang="es" href="<?= e(site_url('/')) ?>">
<link rel="alternate" hreflang="en" href="<?= e(site_url('/en')) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e(site_url('/')) ?>">
<link rel="icon" href="<?= e(asset('favicon.svg')) ?>" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?= e(asset('apple-touch-icon.png')) ?>">
<link rel="preload" href="/assets/fonts/ibm-plex-sans-400.woff2" as="font" type="font/woff2" crossorigin>
<script src="<?= e(asset('tema.js')) ?>"></script>
<link rel="stylesheet" href="<?= e(asset('style.css')) ?>">
<script src="<?= e(asset('cv.js')) ?>" defer></script>
<script type="application/json" id="cv-textos"><?= json_encode($d['ui'] + ['url' => site_url($d['lang'] === 'en' ? '/en' : '/'), 'titulo' => $d['profile']['name'] . ' — ' . $d['role']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Person',
    'name'     => $d['profile']['name'],
    'jobTitle' => $d['role'],
    'description' => $d['meta_desc'],
    'email'    => 'mailto:' . $d['profile']['email'],
    'url'      => site_url($d['lang'] === 'en' ? '/en' : '/'),
    'sameAs'   => [$d['profile']['github'], $d['profile']['linkedin'], $d['profile']['orcid']],
    'affiliation' => ['@type' => 'CollegeOrUniversity', 'name' => 'Universidad Autónoma de Guerrero'],
    'alumniOf' => ['@type' => 'CollegeOrUniversity', 'name' => 'Universidad Autónoma de Guerrero'],
    'knowsAbout' => array_values(array_unique(array_merge(...array_values($d['skills'])))),
    'hasCredential' => array_map(static fn(array $ed): array => [
        '@type'                => 'EducationalOccupationalCredential',
        'name'                 => $ed['title'],
        'credentialCategory'   => 'degree',
        'recognizedBy'         => ['@type' => 'CollegeOrUniversity', 'name' => $ed['org']],
    ], $d['education']),
    'knowsLanguage' => ['es', 'en'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
</head>
<body>

<a class="skip" href="#main"><?= $d['lang'] === 'en' ? 'Skip to content' : 'Ir al contenido' ?></a>

<header class="hero">
  <div class="wrap hero-grid">
    <div>
      <h1><?= e($d['profile']['name']) ?></h1>
      <p class="role"><?= e($d['role']) ?></p>
      <p class="subrole"><?= e($d['subrole']) ?></p>
      <p class="meta"><?= e($d['location']) ?> · <?= e($d['available']) ?></p>
      <p class="links">
        <a href="mailto:<?= e($d['profile']['email']) ?>"><?= icon('mail') ?><?= e($d['profile']['email']) ?></a>
        <button type="button" class="copiar" data-copiar="<?= e($d['profile']['email']) ?>" aria-label="<?= e($d['ui']['copiar_correo']) ?>" hidden><?= e($d['ui']['copiar']) ?></button>
        <a href="<?= e(tel_href($d['profile']['phone'])) ?>"><?= icon('phone') ?><?= e($d['profile']['phone']) ?></a>
        <a href="<?= e($d['profile']['github']) ?>" rel="me noopener"><?= icon('github') ?>GitHub</a>
        <a href="<?= e($d['profile']['linkedin']) ?>" rel="me noopener"><?= icon('linkedin') ?>LinkedIn</a>
        <a href="<?= e($d['profile']['orcid']) ?>" rel="me noopener"><?= icon('orcid') ?>ORCID</a>
      </p>
      <p class="actions">
        <a class="btn" href="<?= e(asset(basename($d['pdf']['plain']))) ?>" download><?= e($d['cta_pdf']) ?> <span class="btn-meta"><?= e(asset_size($d['pdf']['plain'])) ?></span></a>
        <a class="btn ghost" href="<?= e(asset(basename($d['pdf']['design']))) ?>" download><?= e($d['cta_design']) ?> <span class="btn-meta">PDF · <?= e(asset_size($d['pdf']['design'])) ?></span></a>
        <a class="btn ghost" href="<?= e($d['switch_url']) ?>" lang="<?= $d['lang'] === 'en' ? 'es' : 'en' ?>" hreflang="<?= $d['lang'] === 'en' ? 'es' : 'en' ?>"><?= e($d['switch']) ?></a>
        <button type="button" class="btn ghost" id="compartir" hidden><?= e($d['ui']['compartir']) ?></button>
      </p>
    </div>
  </div>
</header>

<nav class="nav" aria-label="<?= $d['lang'] === 'en' ? 'Sections' : 'Secciones' ?>">
  <div class="wrap nav-bar">
    <div class="nav-inner">
      <?php foreach ($d['nav'] as $id => $label): ?>
        <a href="#<?= e($id) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>
    <button type="button" class="tema" id="tema" hidden aria-label="<?= e($d['ui']['tema_oscuro']) ?>" title="<?= e($d['ui']['tema_oscuro']) ?>">
      <svg class="sol" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
      <svg class="luna" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
    </button>
  </div>
</nav>

<main class="wrap" id="main">

  <section class="summary" id="about" aria-label="<?= e($d['nav']['about']) ?>">
    <p><?= e($d['summary']) ?></p>
    <div class="metrics">
      <?php foreach ($d['metrics'] as $m): ?>
        <div class="metric">
          <strong><?= e($m['value']) ?></strong>
          <span><?= e($d['metric_labels'][$m['key']]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="logros">
    <h2><?= e($d['sections']['logros']) ?></h2>
    <ul class="logros">
      <?php foreach ($d['achievements'] as $a): ?>
        <li><strong><?= e($a['title']) ?></strong> <?= e($a['text']) ?></li>
      <?php endforeach; ?>
    </ul>
  </section>

  <div class="buscador" id="buscador" role="search" hidden>
    <label for="q"><?= e($d['ui']['buscar']) ?></label>
    <div class="buscador-fila">
      <input type="search" id="q" placeholder="<?= e($d['ui']['buscar_ph']) ?>" autocomplete="off" spellcheck="false" aria-describedby="q-estado q-ayuda">
      <button type="button" class="limpiar" id="q-limpiar" hidden><?= e($d['ui']['limpiar']) ?></button>
    </div>
    <p class="buscador-estado" id="q-estado" role="status" aria-live="polite"></p>
    <p class="buscador-ayuda" id="q-ayuda"><?= e($d['ui']['buscar_ayuda']) ?></p>
  </div>

  <section id="exp">
    <h2><?= e($d['sections']['exp']) ?></h2>
    <?php foreach ($d['experience'] as $job): ?>
      <article class="entry">
        <div class="entry-head">
          <h3><?= e($job['title']) ?></h3>
          <span class="period"><?= e($job['period']) ?></span>
        </div>
        <p class="org"><?= e($job['org']) ?></p>
        <p class="intro"><?= e($job['intro']) ?></p>
        <?php foreach ($job['groups'] as $g): ?>
          <h4><?= e($g['name']) ?></h4>
          <ul>
            <?php foreach ($g['items'] as $it): ?>
              <li><?= e($it) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endforeach; ?>
      </article>
    <?php endforeach; ?>
  </section>

  <section id="projects">
    <h2><?= e($d['sections']['projects']) ?></h2>
    <div class="cards">
      <?php foreach ($d['projects'] as $pr): ?>
        <?php [$title, $desc] = $d['project_text'][$pr['key']]; ?>
        <article class="card<?= !empty($pr['featured']) ? ' featured' : '' ?><?= $pr['url'] ? ' has-link' : '' ?>">
          <h3><?= e($title) ?></h3>
          <p class="stack"><?= e($pr['stack']) ?></p>
          <p><?= e($desc) ?></p>
          <?php if ($pr['url']): ?>
            <a class="repo" href="<?= e($pr['url']) ?>" rel="noopener"><?= e(short_repo($pr['url'])) ?></a>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="skills">
    <h2><?= e($d['sections']['skills']) ?></h2>
    <?php foreach ($d['skills'] as $group => $items): ?>
      <div class="skillrow">
        <h4><?= e($group) ?></h4>
        <ul class="chips">
          <?php foreach ($items as $i): ?><li><?= e($i) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </section>

  <section id="pubs">
    <h2><?= e($d['sections']['pubs']) ?></h2>
    <ol class="pubs">
      <?php foreach ($d['publications'] as $p): ?>
        <li>
          <a href="<?= e($p['url']) ?>" rel="noopener"><?= e($p['title'][$d['lang']]) ?></a>
          <p class="cite"><?= e($p['authors']) ?> (<?= (int) $p['year'] ?>). <em><?= e($p['venue']) ?></em>.
            <span class="tag"><?= e($p['role'][$d['lang']]) ?></span>
          </p>
        </li>
      <?php endforeach; ?>
    </ol>
    <p class="talk"><strong><?= e($d['talk']['label']) ?>:</strong> <?= e($d['talk']['text']) ?></p>
  </section>

  <section id="edu">
    <h2><?= e($d['sections']['edu']) ?></h2>
    <?php foreach ($d['education'] as $ed): ?>
      <article class="entry compact">
        <div class="entry-head">
          <h3><?= e($ed['title']) ?></h3>
          <span class="period"><?= e($ed['period']) ?></span>
        </div>
        <p class="org"><?= e($ed['org']) ?></p>
        <?php if (!empty($ed['stats'])): ?>
          <ul class="chips stats">
            <?php foreach ($ed['stats'] as $s): ?><li><?= e($s) ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <p class="note"><?= e($ed['note']) ?></p>
        <?php if (!empty($ed['coursework'])): ?>
          <details class="coursework">
            <summary><?= e($ed['coursework']['label']) ?></summary>
            <?php if (!empty($ed['coursework']['note'])): ?>
              <p class="cw-note"><?= e($ed['coursework']['note']) ?></p>
            <?php endif; ?>
            <?php foreach ($ed['coursework']['groups'] as $g): ?>
              <h4><?= e($g['name']) ?></h4>
              <ul class="chips">
                <?php foreach ($g['items'] as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
              </ul>
            <?php endforeach; ?>
          </details>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>

    <h2 class="sub"><?= e($d['sections']['other']) ?></h2>
    <?php foreach ($d['other'] as $o): ?>
      <article class="entry compact">
        <div class="entry-head">
          <h3><?= e($o['title']) ?></h3>
          <span class="period"><?= e($o['period']) ?></span>
        </div>
        <p class="org"><?= e($o['org']) ?></p>
        <p><?= e($o['note']) ?></p>
      </article>
    <?php endforeach; ?>

    <div class="two-col">
      <div>
        <h4><?= $d['lang'] === 'en' ? 'Certifications' : 'Certificaciones' ?></h4>
        <ul><?php foreach ($d['certs'] as $c): ?><li><?= e($c) ?></li><?php endforeach; ?></ul>
      </div>
      <div>
        <h4><?= $d['lang'] === 'en' ? 'Languages' : 'Idiomas' ?></h4>
        <ul><?php foreach ($d['languages'] as $l): ?><li><?= e($l) ?></li><?php endforeach; ?></ul>
      </div>
    </div>
  </section>

  <section id="areas">
    <h2><?= e($d['sections']['areas']) ?></h2>
    <p class="areas-intro"><?= e($d['areas']['intro']) ?></p>
    <details class="coursework">
    <summary><?= e($d['areas']['toggle']) ?></summary>
    <div class="areas">
      <?php foreach ($d['areas']['items'] as $a): ?>
        <article class="area<?= $a['applied'] ? ' is-applied' : '' ?>">
          <h3><?= e($a['name']) ?></h3>
          <p class="badge"><?= e($a['applied'] ? $d['areas']['legend']['applied'] : $d['areas']['legend']['coursework']) ?></p>
          <?php if (!empty($a['evidence'])): ?>
            <p class="evidence"><?= e($a['evidence']) ?></p>
          <?php endif; ?>
          <p class="area-courses"><?= e(implode(' · ', $a['courses'])) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
    </details>
  </section>

  <section id="contact" class="contact">
    <h2><?= e($d['sections']['contact']) ?></h2>
    <p><?= e($d['contact_intro']) ?></p>
    <p class="big"><a href="mailto:<?= e($d['profile']['email']) ?>"><?= e($d['profile']['email']) ?></a>
      <button type="button" class="copiar" data-copiar="<?= e($d['profile']['email']) ?>" aria-label="<?= e($d['ui']['copiar_correo']) ?>" hidden><?= e($d['ui']['copiar_correo']) ?></button></p>
    <p class="meta"><?= e($d['profile']['phone']) ?> · <?= e($d['location']) ?></p>
  </section>

</main>

<footer>
  <div class="wrap">
    <p><?= e($d['footer']) ?></p>
    <p><?= e($d['updated_label']) ?>: <time datetime="<?= e(CV_UPDATED) ?>"><?= e($d['updated']) ?></time> · <a href="/api/cv.json?lang=<?= e($d['lang']) ?>">/api/cv.json</a></p>
  </div>
</footer>

<div class="aviso" id="aviso" role="status" aria-live="polite"></div>

</body>
</html>
