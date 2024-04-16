<?php
session_start();

require_once('./auth/connect_db.php');

// ukoliko korisnik nije prijavljen, ne moze da vidi svoj nalog
if (!isset($_SESSION['userid'])) {
  header("Location: ./prijava.php");
  exit();
}

// userId uzimamo iz sesije
$userId = $_SESSION['userid'];
$trenutniDatum = date_format(date_create(), 'Y-m-d');

// datum zavrsetka clanarine (na pocetku je prazan, jer se kasnije tu ubacuje ako postoji)
$datumZavrsetka = '';

// asocijativni niz korisnickih informacija
$userInfo = array(
  'ime' => '',
  'broj_tel' => '',
  'email' => '',
  'stanje' => 0,
  'status' => false
);

// u $res stavljamo rezultat query-ja gde uzimamo sve informacije o korisniku
$res = $conn->query("SELECT * FROM korisnici WHERE id = '$userId';");

while ($row = $res->fetch_assoc()) {
  // ovde ubacujemo vrednosti u nas asocijativni niz
  $userInfo['ime'] = $row['ime'] . ' ' . $row['prezime'];
  $userInfo['broj_tel'] = $row['broj_tel'];
  $userInfo['stanje'] = $row['stanje'];
  $userInfo['email'] = $row['email'];
}

// ovde uzimamo clanarinu koja je trenutno aktivna i ako ona postoji
$res = $conn->query("SELECT * FROM clanarina WHERE korisnici_id = '$userId' AND '$trenutniDatum' <= datum_zavrsetka");

// ukoliko postoji jedan red $res query-ja to znaci da postoji i clanarina koja odgovara nasim parametrima 
// (da postoji korisnik ciji je ID u clanarina.korisnici_id i da je datum_zavrsetka clanarine u buducnosti)
if ($res->num_rows == 1) {
  $userInfo['status'] = true;

  while ($row = $res->fetch_assoc()) {
    $datumZavrsetka = $row['datum_zavrsetka'];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teretana | Moj Profil</title>
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>

  <!-- Navigacija -->
  <div class="container-fluid bg-dark px-0">
    <div class="row gx-0">
      <div class="col-lg-3 bg-dark d-none d-lg-block">
        <a href="index.html" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
          <h1 class="m-0 display-4 text-primary text-uppercase">Teretana</h1>
        </a>
      </div>
      <div class="col-lg-9">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0 px-lg-5 mt-1">
          <a href="#" class="navbar-brand d-block d-lg-none">
            <h1 class="m-0 display-4 text-primary text-uppercase">Teretana</h1>
          </a>
          <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
            <div class="navbar-nav mr-auto py-0">
              <a href="./index.php" class="nav-item nav-link ">Početna</a>
              <a href="./onama.php" class="nav-item nav-link">O Nama</a>
              <a href="./treneri.php" class="nav-item nav-link">Treneri</a>
              <a href="./kontakt.php" class="nav-item nav-link">Kontakt</a>
              <?php if (isset($_SESSION['userid'])) : ?>
                <a href="./nalog.php" class="nav-item nav-link active">Nalog</a>
                <a href="./clanarina.php" class="nav-item nav-link">Članarina</a>
              <?php endif; ?>
              <?php if (isset($_SESSION['admin'])) : ?>
                <a href="./admin/panel.php" class="nav-item nav-link admin_nav">Admin</a>
              <?php endif; ?>
            </div>
            <?php if (!isset($_SESSION['userid'])) : ?>
              <a href="./registracija.php" class="btn btn-primary py-md-3 px-md-5 d-none d-lg-block">Registruj se!</a>
            <?php endif;
            if (isset($_SESSION['userid'])) : ?>
              <a href="./logout.php" class="btn btn-primary py-md-3 px-md-5 d-none d-lg-block">Izloguj se...</a>
            <?php endif; ?>
          </div>
        </nav>
      </div>
    </div>
  </div>
  <div class="container-fluid p-5">
    <div class="row gx-5">
      <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
        <div class="position-relative h-100">
          <img class="position-absolute w-100 h-100 rounded" src="./images/trener2.jpg" style="object-fit: cover;">
        </div>
      </div>
      <div class="col-lg-7">
        <div class="mb-4">
          <h5 class="text-primary text-uppercase">Moj</h5>
          <h1 class="display-3 text-uppercase text-light mb-0">Profil</h1>
        </div>
        <h4 class="text-body mb-1 text-uppercase">Ime i Prezime</h4>
        <p class="mb-4 text-uppercase"><?php echo $userInfo['ime']; ?></p>
        <h4 class="text-body mb-1 text-uppercase">E-mail</h4>
        <p class="mb-4 text-uppercase"><?php echo $userInfo['email']; ?></p>
        <h4 class="text-body mb-1 text-uppercase">Broj Telefona</h4>
        <p class="mb-4 text-uppercase"><?php echo $userInfo['broj_tel']; ?></p>
        <h4 class="text-body mb-1 text-uppercase">Stanje na računu</h4>
        <p class="mb-4 text-uppercase">===<?php echo $userInfo['stanje']; ?>.00===</p>
        <h4 class="text-body mb-1 text-uppercase">Članarina</h4>
        <!-- Ukoliko clanarina nije uplacena, postavice se forma za uplatu -->
        <?php if ($userInfo['status']) : ?>
          <p class="mb-4 text-uppercase">Važi do: <?php echo date_format(date_create($datumZavrsetka), 'd. m. Y.'); ?></p>
        <?php else : ?>
          <p class="mb-4 text-uppercase">Nije uplaćena, <a href="./clanarina.php">uplati danas!</a></p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="container-fluid bg-dark text-secondary px-5 mt-5">
    <div class="row gx-5">
      <div class="col-lg-8 col-md-6">
        <div class="row gx-5">
          <div class="col-lg-4 col-md-12 pt-5 mb-5">
            <h4 class="text-uppercase text-light mb-4">Kontakt info</h4>
            <div class="d-flex mb-2">
              <p class="mb-0">Lole Ribara 123, Kosovska Mitrovica, SRB</p>
            </div>
            <div class="d-flex mb-2">
              <p class="mb-0">teretana_km@gmail.com</p>
            </div>
            <div class="d-flex mb-2">
              <p class="mb-0">+381 028 67890</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
            <h4 class="text-uppercase text-light mb-4">Linkovi</h4>
            <div class="d-flex flex-column justify-content-start">
              <a class="text-secondary mb-2" href="./index.php">Početna</a>
              <a class="text-secondary mb-2" href="./onama.php">O Nama</a>
              <a class="text-secondary mb-2" href="./treneri.php">Treneri</a>
              <a class="text-secondary" href="./kontakt.php">Kontakt</a>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
            <h4 class="text-uppercase text-light mb-4">Popularni sajtovi</h4>
            <div class="d-flex flex-column justify-content-start">
              <a class="text-secondary mb-2" href="https://darebee.com/">DAREBEE</a>
              <a class="text-secondary mb-2" href="https://www.fitnessblender.com/">Fitness Blender</a>
              <a class="text-secondary mb-2" href="https://www.muscleandstrength.com/workout-routines">Muscle and Strength</a>
              <a class="text-secondary mb-2" href="https://musclewiki.com/">MuscleWiki</a>
              <a class="text-secondary mb-2" href="https://workoutlabs.com/">Workout Labs</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-5">
          <h4 class="text-uppercase text-white mb-4">Filip Radivojević 125/2020</h4>
          <h6 class="text-uppercase text-white">Prirodno-matematički Fakultet</h6>
          <p class="text-light">Kosovska Mitrovica</p>
        </div>
      </div>
    </div>
  </div>
  <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>