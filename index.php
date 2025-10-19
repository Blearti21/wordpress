<?php
// Single-file PHP travel website (WordPress-like structure) — all-in-one
// Language: sq (Albanian labels), code and comments in English for clarity
// Run with any PHP server, e.g.: php -S localhost:8000

// ---- Bootstrap -----------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure correct charset
header('Content-Type: text/html; charset=UTF-8');

// ---- Utilities -----------------------------------------------------------
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function script_path(): string {
    return isset($_SERVER['PHP_SELF']) ? (string)$_SERVER['PHP_SELF'] : 'index.php';
}

function site_url(?string $page = null, array $params = []): string {
    $query = [];
    if ($page !== null) {
        $query['page'] = $page;
    }
    foreach ($params as $key => $value) {
        $query[$key] = $value;
    }
    $queryString = http_build_query($query);
    return script_path() . ($queryString !== '' ? ('?' . $queryString) : '');
}

function current_page(): string {
    $page = isset($_GET['page']) ? strtolower((string)$_GET['page']) : 'home';
    $page = preg_replace('/[^a-z0-9_-]/', '', $page);
    if ($page === '') {
        $page = 'home';
    }
    return $page;
}

function is_active_nav(string $page): bool {
    return current_page() === $page;
}

function nav_link(string $page, string $label): string {
    $href = escape(site_url($page));
    $activeClass = is_active_nav($page) ? ' class="active"' : '';
    return "<a href=\"{$href}\"{$activeClass}>" . escape($label) . "</a>";
}

function random_reference(string $prefix = 'TRIP'): string {
    $number = random_int(100000, 999999);
    return $prefix . '-' . $number;
}

function validate_date(string $value): bool {
    // Expect format YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return false;
    }
    [$y, $m, $d] = array_map('intval', explode('-', $value));
    return checkdate($m, $d, $y);
}

// ---- Handle POST (PRG: Post/Redirect/Get) -------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $formName = $_POST['form_name'] ?? '';

    if ($formName === 'contact') {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        $errors = [];
        if ($name === '') { $errors['name'] = 'Emri është i detyrueshëm.'; }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Email i pavlefshëm.'; }
        if (mb_strlen($message) < 5) { $errors['message'] = 'Mesazhi duhet të ketë të paktën 5 karaktere.'; }

        if (!empty($errors)) {
            $_SESSION['contact_flash'] = [
                'ok' => false,
                'errors' => $errors,
                'data' => [ 'name' => $name, 'email' => $email, 'message' => $message ],
            ];
        } else {
            $_SESSION['contact_flash'] = [
                'ok' => true,
                'summary' => 'Faleminderit, do t’ju kontaktojmë së shpejti!',
            ];
        }
        header('Location: ' . site_url('contact'));
        exit;
    }

    if ($formName === 'book') {
        $fullName = trim((string)($_POST['full_name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $destination = trim((string)($_POST['destination'] ?? ''));
        $startDate = trim((string)($_POST['start_date'] ?? ''));
        $endDate = trim((string)($_POST['end_date'] ?? ''));
        $numTravelers = (int)($_POST['travelers'] ?? 1);
        $notes = trim((string)($_POST['notes'] ?? ''));

        $errors = [];
        if ($fullName === '') { $errors['full_name'] = 'Emri i plotë është i detyrueshëm.'; }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Email i pavlefshëm.'; }
        if ($destination === '') { $errors['destination'] = 'Zgjidhni destinacionin.'; }
        if (!validate_date($startDate)) { $errors['start_date'] = 'Data e nisjes është e pavlefshme.'; }
        if (!validate_date($endDate)) { $errors['end_date'] = 'Data e kthimit është e pavlefshme.'; }
        if ($startDate !== '' && $endDate !== '' && validate_date($startDate) && validate_date($endDate)) {
            if (strtotime($endDate) < strtotime($startDate)) {
                $errors['end_date'] = 'Data e kthimit duhet të jetë pas nisjes.';
            }
        }
        if ($numTravelers < 1) { $errors['travelers'] = 'Numri i udhëtarëve duhet të jetë ≥ 1.'; }

        if (!empty($errors)) {
            $_SESSION['book_flash'] = [
                'ok' => false,
                'errors' => $errors,
                'data' => [
                    'full_name' => $fullName,
                    'email' => $email,
                    'phone' => $phone,
                    'destination' => $destination,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'travelers' => $numTravelers,
                    'notes' => $notes,
                ],
            ];
        } else {
            $reference = random_reference('TRIP');
            $_SESSION['book_flash'] = [
                'ok' => true,
                'summary' => 'Rezervimi u pranua. Referenca: ' . $reference,
            ];
        }
        header('Location: ' . site_url('book'));
        exit;
    }
}

// ---- Rendering -----------------------------------------------------------
function render_header(string $title): void {
    $homeUrl = escape(site_url('home'));
    $destUrl = escape(site_url('destinations'));
    $pkgUrl  = escape(site_url('packages'));
    $bookUrl = escape(site_url('book'));
    $aboutUrl = escape(site_url('about'));
    $contactUrl = escape(site_url('contact'));

    echo '<!DOCTYPE html>';
    echo '<html lang="sq">';
    echo '<head>';
    echo '<meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . escape($title) . '</title>';
    echo '<style>';
    // --- Inline CSS ---
    echo <<<CSS
    :root {
      --bg: #0f172a;          /* slate-900 */
      --panel: #111827;       /* gray-900 */
      --panel-2: #1f2937;     /* gray-800 */
      --text: #e5e7eb;        /* gray-200 */
      --muted: #9ca3af;       /* gray-400 */
      --primary: #06b6d4;     /* cyan-500 */
      --primary-2: #0891b2;   /* cyan-600 */
      --accent: #22c55e;      /* green-500 */
      --danger: #ef4444;      /* red-500 */
      --warning: #f59e0b;     /* amber-500 */
      --card: #0b1220;        /* deep slate */
      --radius: 12px;
      --shadow: 0 10px 30px rgba(0,0,0,.35);
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      background: linear-gradient(180deg, #0b1020 0%, #0f172a 60%);
      color: var(--text);
    }
    a { color: var(--primary); text-decoration: none; }
    a:hover { color: var(--primary-2); }

    header.site-header {
      position: sticky; top: 0; z-index: 50;
      backdrop-filter: blur(10px);
      background: rgba(15, 23, 42, 0.85);
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .nav {
      max-width: 1100px; margin: 0 auto; padding: 14px 20px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .brand { font-weight: 700; letter-spacing: 0.4px; }
    .brand a { color: #fff; }
    nav.menu { display: flex; gap: 14px; flex-wrap: wrap; }
    nav.menu a { color: var(--text); padding: 8px 12px; border-radius: 8px; }
    nav.menu a.active, nav.menu a:hover {
      background: linear-gradient(180deg, rgba(8,145,178,.15), rgba(8,145,178,.05));
      color: #e6fdff;
    }

    .container { max-width: 1100px; margin: 0 auto; padding: 28px 20px 60px; }

    .hero {
      margin-top: 18px;
      background: radial-gradient(1000px 400px at 10% -10%, rgba(34,197,94,.18), transparent),
                  radial-gradient(900px 400px at 90% -20%, rgba(6,182,212,.22), transparent),
                  linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02));
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 18px;
      padding: 36px 26px;
      box-shadow: var(--shadow);
    }
    .hero h1 { margin: 0 0 10px; font-size: 28px; }
    .hero p { margin: 0; color: var(--muted); }

    .grid { display: grid; gap: 18px; }
    @media (min-width: 640px) { .grid.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 900px) { .grid.cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

    .card {
      background: linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01));
      border: 1px solid rgba(255,255,255,.06);
      border-radius: var(--radius);
      padding: 16px;
      box-shadow: var(--shadow);
    }
    .card h3 { margin: 0 0 10px; }
    .card p { margin: 0; color: var(--muted); }

    .btn-row { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
    .btn {
      display: inline-block; padding: 10px 14px; border-radius: 10px; font-weight: 600; letter-spacing: .2px;
      color: #001219; background: linear-gradient(180deg, var(--primary), var(--primary-2));
    }
    .btn:hover { filter: brightness(1.04); }
    .btn.secondary { background: linear-gradient(180deg, #374151, #1f2937); color: var(--text); }

    form .row { display: grid; gap: 14px; }
    @media (min-width: 680px) { form .row.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }

    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group label { color: #cbd5e1; font-size: 14px; }
    .input, .select, .textarea {
      background: rgba(255,255,255,.03);
      border: 1px solid rgba(255,255,255,.08);
      color: var(--text);
      padding: 10px 12px;
      border-radius: 10px;
      outline: none;
    }
    .input:focus, .select:focus, .textarea:focus { border-color: rgba(6,182,212,.45); box-shadow: 0 0 0 3px rgba(6,182,212,.15); }
    .textarea { min-height: 110px; resize: vertical; }

    .alert { padding: 12px 14px; border-radius: 10px; }
    .alert.success { background: rgba(34,197,94,.14); border: 1px solid rgba(34,197,94,.35); }
    .alert.error { background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.35); }

    footer.site-footer { border-top: 1px solid rgba(255,255,255,.06); color: var(--muted); }
    footer .inner { max-width: 1100px; margin: 0 auto; padding: 26px 20px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
    CSS;
    echo '</style>';
    echo '</head>';

    echo '<body>';
    echo '<header class="site-header">';
    echo '<div class="nav">';
    echo '<div class="brand"><a href="' . $homeUrl . '">Udhtime</a></div>';
    echo '<nav class="menu">';
    echo nav_link('home', 'Kreu');
    echo nav_link('destinations', 'Destinacione');
    echo nav_link('packages', 'Paketat');
    echo nav_link('book', 'Rezervo');
    echo nav_link('about', 'Rreth Nesh');
    echo nav_link('contact', 'Kontakt');
    echo '</nav>';
    echo '</div>';
    echo '</header>';

    echo '<div class="container">';
}

function render_footer(): void {
    $year = date('Y');
    echo '</div>'; // .container
    echo '<footer class="site-footer">';
    echo '<div class="inner">';
    echo '<div>© ' . $year . ' Udhtime Travel</div>';
    echo '<div>Made me ❤️ dhe PHP</div>';
    echo '</div>';
    echo '</footer>';
    echo '</body></html>';
}

// ---- Pages ---------------------------------------------------------------
function render_home(): void {
    $bookUrl = escape(site_url('book'));
    echo '<section class="hero">';
    echo '<h1>Gjej udhëtimin tënd të ëndrrave</h1>';
    echo '<p>Zbuloni destinacione mahnitëse, pako fleksibile dhe çmime të arsyeshme.</p>';
    echo '<div class="btn-row"><a class="btn" href="' . $bookUrl . '">Rezervo tani</a></div>';
    echo '</section>';

    echo '<h2 style="margin-top:28px">Destinacionet më të kërkuara</h2>';
    echo '<div class="grid cols-3" style="margin-top:12px">';
    $spots = [
        ['title' => 'Santorini, Greqi', 'desc' => 'Perëndime dielli dhe fshatra të bardha.'],
        ['title' => 'Bali, Indonezi', 'desc' => 'Bregdet, tempull dhe natyrë tropikale.'],
        ['title' => 'Paris, Francë', 'desc' => 'Qyteti i dritave dhe romantikës.'],
        ['title' => 'Tokyo, Japoni', 'desc' => 'Traditë dhe teknologji moderne.'],
        ['title' => 'Tiranë, Shqipëri', 'desc' => 'Kulturë, kuzhinë dhe mikpritje.'],
        ['title' => 'New York, SHBA', 'desc' => 'Qyteti që nuk fle.'],
    ];
    foreach ($spots as $s) {
        echo '<div class="card">';
        echo '<h3>' . escape($s['title']) . '</h3>';
        echo '<p>' . escape($s['desc']) . '</p>';
        echo '</div>';
    }
    echo '</div>';
}

function render_destinations(): void {
    echo '<section class="hero">';
    echo '<h1>Destinacione</h1>';
    echo '<p>Zgjidh nga një përzgjedhje e gjerë destinacionesh në mbarë botën.</p>';
    echo '</section>';

    $groups = [
        'Europa' => ['Santorini', 'Paris', 'Barcelona', 'Pragë', 'Tiranë', 'Dubrovnik'],
        'Azi' => ['Bali', 'Tokyo', 'Bangkok', 'Seul', 'Kuala Lumpur'],
        'Amerika' => ['New York', 'Miami', 'Rio de Janeiro', 'Cusco'],
    ];

    foreach ($groups as $continent => $cities) {
        echo '<h2 style="margin-top:22px">' . escape($continent) . '</h2>';
        echo '<div class="grid cols-3" style="margin-top:12px">';
        foreach ($cities as $city) {
            echo '<div class="card">';
            echo '<h3>' . escape($city) . '</h3>';
            echo '<p>Eksploro ' . escape($city) . ' me oferta të personalizuara.</p>';
            echo '</div>';
        }
        echo '</div>';
    }
}

function render_packages(): void {
    echo '<section class="hero">';
    echo '<h1>Paketat</h1>';
    echo '<p>Pako fleksibile me fluturime, hotele dhe guida lokale.</p>';
    echo '</section>';

    $bookUrl = escape(site_url('book'));
    $packages = [
        ['name' => 'Romancë në Santorini', 'price' => '€699', 'nights' => 4],
        ['name' => 'Adrenalinë në Bali', 'price' => '€899', 'nights' => 6],
        ['name' => 'Weekend në Paris', 'price' => '€499', 'nights' => 3],
    ];

    echo '<div class="grid cols-3" style="margin-top:12px">';
    foreach ($packages as $p) {
        echo '<div class="card">';
        echo '<h3>' . escape($p['name']) . '</h3>';
        echo '<p>' . escape($p['nights']) . ' netë · ' . escape($p['price']) . '</p>';
        echo '<div class="btn-row"><a class="btn" href="' . $bookUrl . '">Rezervo</a></div>';
        echo '</div>';
    }
    echo '</div>';
}

function render_about(): void {
    echo '<section class="hero">';
    echo '<h1>Rreth Nesh</h1>';
    echo '<p>Agjenci udhëtimesh e krijuar për t’ju shërbyer me pasion dhe kujdes.</p>';
    echo '</section>';

    echo '<div class="card" style="margin-top:14px">';
    echo '<p>Ne besojmë se çdo udhëtim është një histori unike. Ekipi ynë ju ndihmon të planifikoni çdo detaj, nga fluturimet dhe akomodimi te aktivitetet autentike në destinacion.</p>';
    echo '</div>';
}

function render_contact(): void {
    $flash = $_SESSION['contact_flash'] ?? null;
    unset($_SESSION['contact_flash']);

    $errors = is_array($flash) && !($flash['ok'] ?? false) ? ($flash['errors'] ?? []) : [];
    $data = is_array($flash) && !($flash['ok'] ?? false) ? ($flash['data'] ?? []) : [];
    $success = is_array($flash) && ($flash['ok'] ?? false) ? ($flash['summary'] ?? null) : null;

    echo '<section class="hero">';
    echo '<h1>Kontakt</h1>';
    echo '<p>Na shkruani për çdo pyetje apo kërkesë.</p>';
    echo '</section>';

    if ($success) {
        echo '<div class="alert success" style="margin-top:14px">' . escape($success) . '</div>';
    }

    echo '<form method="post" action="' . escape(site_url('contact')) . '" style="margin-top:14px">';
    echo '<input type="hidden" name="form_name" value="contact">';

    echo '<div class="row cols-2">';
    echo '<div class="form-group">';
    echo '<label for="name">Emri</label>';
    echo '<input class="input" type="text" id="name" name="name" value="' . escape((string)($data['name'] ?? '')) . '" placeholder="Emri juaj">';
    if (isset($errors['name'])) { echo '<div class="alert error">' . escape($errors['name']) . '</div>'; }
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="email">Email</label>';
    echo '<input class="input" type="email" id="email" name="email" value="' . escape((string)($data['email'] ?? '')) . '" placeholder="ju@shembull.com">';
    if (isset($errors['email'])) { echo '<div class="alert error">' . escape($errors['email']) . '</div>'; }
    echo '</div>';
    echo '</div>'; // row

    echo '<div class="form-group" style="margin-top:14px">';
    echo '<label for="message">Mesazhi</label>';
    echo '<textarea class="textarea" id="message" name="message" placeholder="Si mund t’ju ndihmojmë?">' . escape((string)($data['message'] ?? '')) . '</textarea>';
    if (isset($errors['message'])) { echo '<div class="alert error">' . escape($errors['message']) . '</div>'; }
    echo '</div>';

    echo '<div class="btn-row" style="margin-top:14px">';
    echo '<button class="btn" type="submit">Dërgo</button>';
    echo '<a class="btn secondary" href="' . escape(site_url('home')) . '">Kthehu</a>';
    echo '</div>';
    echo '</form>';
}

function render_book(): void {
    $flash = $_SESSION['book_flash'] ?? null;
    unset($_SESSION['book_flash']);

    $errors = is_array($flash) && !($flash['ok'] ?? false) ? ($flash['errors'] ?? []) : [];
    $data = is_array($flash) && !($flash['ok'] ?? false) ? ($flash['data'] ?? []) : [];
    $success = is_array($flash) && ($flash['ok'] ?? false) ? ($flash['summary'] ?? null) : null;

    echo '<section class="hero">';
    echo '<h1>Rezervo Udhëtimin</h1>';
    echo '<p>Plotësoni detajet dhe ne do të kujdesemi për pjesën tjetër.</p>';
    echo '</section>';

    if ($success) {
        echo '<div class="alert success" style="margin-top:14px">' . escape($success) . '</div>';
    }

    $destinations = ['Santorini', 'Bali', 'Paris', 'Tokyo', 'Tiranë', 'New York'];

    echo '<form method="post" action="' . escape(site_url('book')) . '" style="margin-top:14px">';
    echo '<input type="hidden" name="form_name" value="book">';

    echo '<div class="row cols-2">';
    echo '<div class="form-group">';
    echo '<label for="full_name">Emri i plotë</label>';
    echo '<input class="input" type="text" id="full_name" name="full_name" value="' . escape((string)($data['full_name'] ?? '')) . '" placeholder="P.sh. Arben Hoxha">';
    if (isset($errors['full_name'])) { echo '<div class="alert error">' . escape($errors['full_name']) . '</div>'; }
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="email">Email</label>';
    echo '<input class="input" type="email" id="email" name="email" value="' . escape((string)($data['email'] ?? '')) . '" placeholder="ju@shembull.com">';
    if (isset($errors['email'])) { echo '<div class="alert error">' . escape($errors['email']) . '</div>'; }
    echo '</div>';
    echo '</div>'; // row

    echo '<div class="row cols-2" style="margin-top:14px">';
    echo '<div class="form-group">';
    echo '<label for="phone">Telefoni</label>';
    echo '<input class="input" type="tel" id="phone" name="phone" value="' . escape((string)($data['phone'] ?? '')) . '" placeholder="P.sh. +355 6x xxx xxxx">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="destination">Destinacioni</label>';
    echo '<select class="select" id="destination" name="destination">';
    echo '<option value="">Zgjidh destinacionin</option>';
    foreach ($destinations as $dest) {
        $selected = ((string)($data['destination'] ?? '') === $dest) ? ' selected' : '';
        echo '<option value="' . escape($dest) . '"' . $selected . '>' . escape($dest) . '</option>';
    }
    echo '</select>';
    if (isset($errors['destination'])) { echo '<div class="alert error">' . escape($errors['destination']) . '</div>'; }
    echo '</div>';
    echo '</div>'; // row

    echo '<div class="row cols-2" style="margin-top:14px">';
    echo '<div class="form-group">';
    echo '<label for="start_date">Data e nisjes</label>';
    echo '<input class="input" type="date" id="start_date" name="start_date" value="' . escape((string)($data['start_date'] ?? '')) . '">';
    if (isset($errors['start_date'])) { echo '<div class="alert error">' . escape($errors['start_date']) . '</div>'; }
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="end_date">Data e kthimit</label>';
    echo '<input class="input" type="date" id="end_date" name="end_date" value="' . escape((string)($data['end_date'] ?? '')) . '">';
    if (isset($errors['end_date'])) { echo '<div class="alert error">' . escape($errors['end_date']) . '</div>'; }
    echo '</div>';
    echo '</div>'; // row

    echo '<div class="row cols-2" style="margin-top:14px">';
    echo '<div class="form-group">';
    echo '<label for="travelers">Numri i udhëtarëve</label>';
    echo '<input class="input" type="number" min="1" id="travelers" name="travelers" value="' . escape((string)($data['travelers'] ?? '1')) . '">';
    if (isset($errors['travelers'])) { echo '<div class="alert error">' . escape($errors['travelers']) . '</div>'; }
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="notes">Shënime</label>';
    echo '<input class="input" type="text" id="notes" name="notes" value="' . escape((string)($data['notes'] ?? '')) . '" placeholder="Kërkesa të veçanta">';
    echo '</div>';
    echo '</div>'; // row

    echo '<div class="btn-row" style="margin-top:14px">';
    echo '<button class="btn" type="submit">Konfirmo Rezervimin</button>';
    echo '<a class="btn secondary" href="' . escape(site_url('packages')) . '">Shiko Paketa</a>';
    echo '</div>';

    echo '</form>';
}

function render_404(): void {
    echo '<section class="hero">';
    echo '<h1>404</h1>';
    echo '<p>Faqja nuk u gjet.</p>';
    echo '</section>';
}

// ---- Router --------------------------------------------------------------
$siteName = 'Udhtime Travel';
$page = current_page();

$titleMap = [
    'home' => $siteName . ' — Kreu',
    'destinations' => $siteName . ' — Destinacione',
    'packages' => $siteName . ' — Paketat',
    'book' => $siteName . ' — Rezervo',
    'about' => $siteName . ' — Rreth Nesh',
    'contact' => $siteName . ' — Kontakt',
];

render_header($titleMap[$page] ?? ($siteName . ' — ' . ucfirst($page)));

switch ($page) {
    case 'home':
        render_home();
        break;
    case 'destinations':
        render_destinations();
        break;
    case 'packages':
        render_packages();
        break;
    case 'book':
        render_book();
        break;
    case 'about':
        render_about();
        break;
    case 'contact':
        render_contact();
        break;
    default:
        render_404();
        break;
}

render_footer();
