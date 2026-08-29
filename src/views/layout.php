<?php /** @var array $d */ ?>
<!DOCTYPE html>
<html lang="<?= e($d['lang']) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($d['profile']['name']) ?> — <?= e($d['role']) ?></title>
<meta name="description" content="<?= e($d['meta_desc']) ?>">
<meta name="author" content="<?= e($d['profile']['name']) ?>">
<meta name="theme-color" content="#16233d">

<meta property="og:type" content="profile">
<meta property="og:title" content="<?= e($d['profile']['name']) ?> — <?= e($d['role']) ?>">
<meta property="og:description" content="<?= e($d['meta_desc']) ?>">
<meta property="og:image" content="/assets/og.jpg">
<meta property="og:locale" content="<?= e($d['locale']) ?>">
<meta name="twitter:card" content="summary_large_image">

<link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/apple-touch-icon.png">
<meta name="mobile-web-app-capable" content="yes">
<link rel="canonical" href="<?= $d['lang'] === 'en' ? '/en' : '/' ?>">
<link rel="alternate" hreflang="es" href="/">
<link rel="alternate" hreflang="en" href="/en">
<link rel="alternate" hreflang="x-default" href="/">
<link rel="stylesheet" href="/assets/style.css">

<script type="application/ld+json">
<?= json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'Person',
    'name'        => $d['profile']['name'],
    'jobTitle'    => $d['role'],
    'description' => $d['meta_desc'],
    'email'       => 'mailto:' . $d['profile']['email'],
    'image'       => $d['profile']['photo'],
    'sameAs'      => [$d['profile']['github'], $d['profile']['linkedin'], $d['profile']['orcid']],
    'alumniOf'    => ['@type' => 'CollegeOrUniversity', 'name' => 'Universidad Autónoma de Guerrero'],
    'knowsAbout'  => ['Internet of Things', 'Embedded Systems', 'Digital Signal Processing',
                      'Structural Health Monitoring', 'Python', 'Linux'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
</head>
<body>

<a class="skip" href="#main"><?= $d['lang'] === 'en' ? 'Skip to content' : 'Ir al contenido' ?></a>

<nav class="nav" aria-label="<?= $d['lang'] === 'en' ? 'Sections' : 'Secciones' ?>">
  <div class="wrap">
    <span class="mark">MA<span>·</span>AR</span>
    <ul>
      <li><a href="#about"><?= e($d['nav']['about']) ?></a></li>
      <li><a href="#exp"><?= e($d['nav']['exp']) ?></a></li>
      <li><a href="#pubs"><?= e($d['nav']['pubs']) ?></a></li>
      <li><a href="#projects"><?= e($d['nav']['projects']) ?></a></li>
      <li><a href="#edu"><?= e($d['nav']['edu']) ?></a></li>
      <li><a href="#contact"><?= e($d['nav']['contact']) ?></a></li>
    </ul>
    <a class="lang" href="<?= e($d['switch_url']) ?>"><?= e($d['switch']) ?></a>
  </div>
</nav>

<header class="hero">
  <div class="wrap hero-grid">
    <img class="avatar" src="<?= e($d['profile']['photo']) ?>" alt="<?= e($d['profile']['name']) ?>"
         width="132" height="132" loading="eager">
    <div>
      <h1><?= e($d['profile']['name']) ?></h1>
      <p class="role"><?= e($d['role']) ?></p>
      <p class="subrole"><?= e($d['subrole']) ?></p>
      <p class="meta"><?= e($d['location']) ?><span class="dot">·</span><?= e($d['available']) ?></p>
      <p class="links">
        <a href="mailto:<?= e($d['profile']['email']) ?>"><?= e($d['profile']['email']) ?></a>
        <a href="<?= e($d['profile']['github']) ?>" rel="noopener">GitHub</a>
        <a href="<?= e($d['profile']['linkedin']) ?>" rel="noopener">LinkedIn</a>
        <a href="<?= e($d['profile']['orcid']) ?>" rel="noopener">ORCID</a>
      </p>
      <p class="actions">
        <a class="btn" href="<?= e($d['pdf']['plain']) ?>" download><?= e($d['cta_pdf']) ?></a>
        <a class="btn ghost" href="<?= e($d['pdf']['design']) ?>" download><?= e($d['cta_design']) ?></a>
      </p>
    </div>
  </div>
</header>

<main class="wrap" id="main">

  <section class="summary" id="about">
    <p class="lede"><?= e($d['summary']) ?></p>
    <div class="metrics">
      <?php foreach ($d['metrics'] as $m): ?>
        <div class="metric">
          <strong><?= e($m['value']) ?></strong>
          <span><?= e($d['metric_labels'][$m['key']]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="exp">
    <h2><?= e($d['sections']['exp']) ?></h2>
    <div class="timeline">
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
    </div>
  </section>

  <section id="pubs">
    <h2><?= e($d['sections']['pubs']) ?></h2>
    <ol class="pubs">
      <?php foreach ($d['publications'] as $p): ?>
        <li>
          <a class="ptitle" href="<?= e($p['url']) ?>" rel="noopener"><?= e($p['title'][$d['lang']]) ?></a>
          <p class="cite"><?= e($p['authors']) ?> (<?= (int) $p['year'] ?>). <em><?= e($p['venue']) ?></em>.
            <span class="tag"><?= e($p['role'][$d['lang']]) ?></span>
          </p>
        </li>
      <?php endforeach; ?>
    </ol>
    <p class="talk"><strong><?= e($d['talk']['label']) ?>:</strong> <?= e($d['talk']['text']) ?></p>
  </section>

  <section id="projects">
    <h2><?= e($d['sections']['projects']) ?></h2>
    <div class="cards">
      <?php foreach ($d['projects'] as $pr): ?>
        <?php [$title, $desc] = $d['project_text'][$pr['key']]; ?>
        <article class="card">
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

  <section id="edu">
    <h2><?= e($d['sections']['edu']) ?></h2>
    <div class="timeline">
      <?php foreach ($d['education'] as $ed): ?>
        <article class="entry compact">
          <div class="entry-head">
            <h3><?= e($ed['title']) ?></h3>
            <span class="period"><?= e($ed['period']) ?></span>
          </div>
          <p class="org"><?= e($ed['org']) ?></p>
          <p><?= e($ed['note']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>

    <h2 class="sub"><?= e($d['sections']['other']) ?></h2>
    <div class="timeline">
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
    </div>

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

  <section id="contact" class="contact">
    <h2><?= e($d['sections']['contact']) ?></h2>
    <p><?= e($d['contact_intro']) ?></p>
    <p class="big"><a href="mailto:<?= e($d['profile']['email']) ?>"><?= e($d['profile']['email']) ?></a></p>
    <p class="meta"><?= e($d['profile']['phone']) ?><span class="dot"> · </span><?= e($d['location']) ?></p>
  </section>

</main>

<footer>
  <div class="wrap">
    <p><?= e($d['footer']) ?></p>
    <p><a href="/api/cv.json?lang=<?= e($d['lang']) ?>"><code>/api/cv.json</code></a></p>
  </div>
</footer>

</body>
</html>
