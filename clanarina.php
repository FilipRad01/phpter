<?php
// clanarina.php funkcionise kao i treneri.php (vidi za objasnjenje)
session_start();

require_once('./auth/connect_db.php');

$trenutniDatum = date_format(date_create(), 'Y-m-d');
$datumZavrsetka = '';

$jedanMesec = date("d.m.Y.", strtotime("+1 month", strtotime(date("Y/m/d"))));
$triMeseca = date("d.m.Y.", strtotime("+3 month", strtotime(date("Y/m/d"))));
$sestMeseci = date("d.m.Y.", strtotime("+6 months", strtotime(date("Y/m/d"))));

$userId = $_SESSION['userid'];
$cena = 0;
$stanje = 0;

$errorMsg = '';
$success = false;

if (isset($_GET['ponuda'])) {

  if (!isset($_SESSION['userid'])) {
    header('Location: ./prijava.php');
    exit();
  }

  if ($_GET['ponuda'] == 1) {
    $cena = 100;
    $datumZavrsetka = $jedanMesec;
  } else if ($_GET['ponuda'] == 2) {
    $cena = 250;
    $datumZavrsetka = $triMeseca;
  } else if ($_GET['ponuda'] == 3) {
    $cena = 400;
    $datumZavrsetka = $sestMeseci;
  }

  $res = $conn->query("SELECT id, stanje FROM korisnici WHERE id = '$userId'");

  while ($row = $res->fetch_assoc()) {
    $stanje = $row['stanje'];
  }
}

if (isset($_POST['submit'])) {
  $datumZavrsetka = date_format(date_create($datumZavrsetka), 'Y-m-d');

  $res = $conn->query("SELECT * FROM clanarina WHERE korisnici_id = '$userId' AND datum_zavrsetka >= '$trenutniDatum';");
  $novoStanje = $stanje - $cena;

  if ($novoStanje < 0) {
    $errorMsg = "Nemate dovoljno sredstava na računu! Obratite se administratoru!";
  } else if ($res->num_rows >= 1) {
    $errorMsg = "Već imate uplaćenu članarinu!";
  } else {
    $conn->query(
      "INSERT INTO clanarina (korisnici_id, datum_pocetka, datum_zavrsetka)
      VALUES ('$userId', '$trenutniDatum', '$datumZavrsetka');"
    );

    $conn->query(
      "UPDATE korisnici
      SET stanje = '$novoStanje'
      WHERE korisnici.id = '$userId';"
    );

    $success = true;
    header("Refresh: 2 ; URL=./nalog.php");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teretana | Uplata Članarine</title>
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
                <a href="./nalog.php" class="nav-item nav-link">Nalog</a>
                <a href="./clanarina.php" class="nav-item nav-link active">Članarina</a>
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

  <?php if (!isset($_GET['ponuda'])) : ?>
    <div class="container-fluid p-5">
      <div class="mb-5 text-center">
        <h5 class="text-primary text-uppercase">Uplata Članarine</h5>
        <h1 class="display-3 text-light text-uppercase mb-0">Izaberite ponudu</h1>
      </div>
      <div class="row g-5 mb-5">
        <div class="col-lg-4">
          <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3 clanarina" onclick="window.location.href='./clanarina.php?ponuda=1'">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
              <i class="fa fa-map-marker-alt fs-4 text-white"></i>
            </div>
            <h5 class="text-uppercase text-primary">Cena: 100.00</h5>
            <p class="text-secondary mb-0">Mesec dana</p>
            <p class="text-secondary mb-0">(Do <?php echo $jedanMesec; ?>)</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3 clanarina" onclick="window.location.href='./clanarina.php?ponuda=2'">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
              <i class="fa fa-envelope fs-4 text-white"></i>
            </div>
            <h5 class="text-uppercase text-primary">Cena: 250.00</h5>
            <p class="text-secondary mb-0">Tri meseca</p>
            <p class="text-secondary mb-0">(Do <?php echo $triMeseca; ?>)</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3 clanarina" onclick="window.location.href='./clanarina.php?ponuda=3'">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
              <i class="fa fa-phone fs-4 text-white"></i>
            </div>
            <h5 class="text-uppercase text-primary">Cena: 400.00</h5>
            <p class="text-secondary mb-0">Šest meseci</p>
            <p class="text-secondary mb-0">(Do <?php echo $sestMeseci; ?>)</p>
          </div>
        </div>
      </div>
      <div class="mb-5 text-center">
        <h5 class="text-primary text-uppercase">Ukoliko Vam admin momentalno ne potvrdi možete doći uz broj telefona!</h5>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($cena != 0) : ?>
    <div class="container-fluid p-5">
      <div class="mb-5 text-center">
        <h5 class="text-primary text-uppercase">Izabrali ste ponudu #<?php echo $_GET['ponuda']; ?></h5>
        <h5 class="text-light text-uppercase mb-0">Da li ste sigurni?</h5>
      </div>
      <div class="container-fluid d-flex justify-content-center mb-5">
        <form method="post">
          <button name="submit" class="btn btn-primary py-md-3 px-md-5 me-3">DA</button>
        </form>
      </div>
      <div class="mb-5 text-center">
        <p class="text-light mb-0">(<?php echo $cena; ?>.00 će biti oduzeto sa Vašeg naloga)</p>
      </div>
      <?php if ($errorMsg != '') : ?>
        <div class="d-flex justify-content-center mx-4 mb-lg-4 alert alert-danger" role="alert">
          <ul class="errors"><?php echo $errorMsg; ?></ul>
        </div>
      <?php endif; ?>
      <?php if ($success) : ?>
        <div class="d-flex justify-content-center mb-lg-4 alert alert-success" role="alert">
          <p class="success"><?php echo 'Članarina je uplaćena, redirektujem na profil...' ?></p>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

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