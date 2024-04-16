<?php
// ovaj deo, kao i clanarina, funkcionise na principu GET requesta
// GET request uzima promenljive iz samog linka, npr. YT ima taj shit
// youtube.com/watch?v=123 -> ovde je v = 123 i moze se koristiti za uzimanje informacija o videu

// kada se klikne trener on vodi na ?trener=1 npr i tako se zna tacno koji trener je kliknut (vidi IF uslove dole u HTML)

session_start();
require_once('./auth/connect_db.php');

// asocijativni array sa informacijama o trenerima koje se uzimaju iz baze
$treneriInfo = array(
  'ime' => array(),
  'godine' => array(),
  'usluga' => array(),
  'slika' => array(),
  'broj_tel' => array(),
  'cena' => array()
);

$res = $conn->query("SELECT * FROM treneri;");

// ubacivanje svih informacija trenera u asocijativni niz
while ($row = $res->fetch_assoc()) {
  array_push($treneriInfo['ime'], $row['ime'] . ' ' . $row['prezime']);
  array_push($treneriInfo['godine'], $row['broj_god']);
  array_push($treneriInfo['broj_tel'], $row['broj_tel']);
  array_push($treneriInfo['slika'], $row['slika']);
  array_push($treneriInfo['usluga'], $row['usluga']);
  array_push($treneriInfo['cena'], $row['cena']);
}

// uzimanje korisnickog ID-a iz sesije
$userId = $_SESSION['userid'];

// na pocetku (ako nije kliknut nijedan trener) cena i stanje su nebitni
// zato su nula
$cena = 0;
$stanje = 0;

// datum zavrsetka potencijalnog aranzmana je mesec dana unapred
$datumZavrsetka = date("d.m.Y.", strtotime("+1 month", strtotime(date("Y/m/d"))));
$trenutniDatum = date_format(date_create(), 'Y-m-d');
$errorMsg = '';
$success = false;

// ako je kliknut neki od trenera
if (isset($_GET['trener'])) {
  // ukoliko tad korisnik nije ulogovan vratice ga na priajvu
  if (!isset($_SESSION['userid'])) {
    header('Location: ./prijava.php');
    exit();
  }
  // cena se uzima iz asocijativnog niza sa indeksom odgovarajuceg trenera
  $cena = $treneriInfo['cena'][$_GET['trener'] - 1];

  // ovde saljemo zahtev za uzimanje stanja korisnika
  $res = $conn->query("SELECT id, stanje FROM korisnici WHERE id = '$userId'");

  // definisemo stanje
  while ($row = $res->fetch_assoc()) {
    $stanje = $row['stanje'];
  }
}

// ukoliko se nakon toga klikne dugme submit
if (isset($_POST['submit'])) {
  // datum zavrsetka je sad u PHP citljivom datumu
  $datumZavrsetka = date_format(date_create($datumZavrsetka), 'Y-m-d');
  // uzimamo i trenerID iz linka
  $trenerId = $_GET['trener'] - 1;

  // ovde proveravamo da li je vec korisnik angazovao trenera sa id-em $trenerId i da li je proslo mesec dana ako jeste ranije
  $res = $conn->query("SELECT * FROM trenira WHERE korisnici_id = '$userId' AND treneri_id = '$trenerId' AND datum_zavrsetka >= '$trenutniDatum';");
  // racuna se $novoStanje  
  $novoStanje = $stanje - $cena;

  // ukoliko je ono manje od nule, znaci da nema dovoljno paricki na racuniki
  if ($novoStanje < 0) {
    $errorMsg = "Nemate dovoljno sredstava na računu! Obratite se administratoru!";
  } else if ($res->num_rows >= 1) { // ukoliko je $res query vratio bar jedan red onda je trener angazovan
    $errorMsg = "Već ste angažovali ovog trenera!";
  } else {
    // ukoliko prethodna dva nisu tacna ubacuju se informacije o treneru i korisniku u tabelu `trenira`
    $conn->query(
      "INSERT INTO trenira (korisnici_id, treneri_id, datum_pocetka, datum_zavrsetka)
      VALUES ('$userId', '$trenerId', '$trenutniDatum', '$datumZavrsetka');"
    );

    // azurira se stanje korisnika
    $conn->query(
      "UPDATE korisnici
      SET stanje = '$novoStanje'
      WHERE korisnici.id = '$userId';"
    );

    $success = true;
    // refresh-uje se stranica
    header("Refresh: 2 ; URL=./treneri.php");
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teretana | Treneri</title>
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
              <a href="./treneri.php" class="nav-item nav-link active">Treneri</a>
              <a href="./kontakt.php" class="nav-item nav-link">Kontakt</a>
              <?php if (isset($_SESSION['userid'])) : ?>
                <a href="./nalog.php" class="nav-item nav-link">Nalog</a>
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

  <?php if (!isset($_GET['trener'])) : ?>
    <div class="container-fluid p-5">
      <div class="mb-5 text-center">
        <h5 class="text-primary text-uppercase">Naš tim</h5>
        <h1 class="display-3 text-uppercase text-warning mb-0">Trenera</h1>
      </div>

      <div class="row g-5">
        <?php for ($i = 0; $i < sizeof($treneriInfo['ime']); $i++) : ?>
          <div class="col-lg-4 col-md-6">
            <div class="position-relative" onclick="window.location.href='./treneri.php?trener=<?php echo $i + 1 ?>'">
              <div class="position-relative overflow-hidden rounded">
                <img class="img-fluid w-100" src="./images/<?php echo $treneriInfo['slika'][$i]; ?>" alt="Trener 1">
              </div>
              <div class="treneri_info position-absolute start-0 bottom-0 w-100 rounded-bottom text-center p-4">
                <h5 class="text-uppercase text-light"><?php echo $treneriInfo['ime'][$i]; ?> (<?php echo $treneriInfo['godine'][$i] ?>)</h5>
                <p class="text-uppercase text-secondary m-0"><?php echo $treneriInfo['usluga'][$i]; ?></p>
                <p class="text-uppercase text-secondary m-0"><?php echo $treneriInfo['broj_tel'][$i]; ?></p>
                <p class="text-uppercase text-primary m-0">Cena: <?php echo $treneriInfo['cena'][$i]; ?>.00 </p>
              </div>
            </div>
          </div>
        <?php endfor; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['trener']) && $_GET['trener'] > 0 && $_GET['trener'] <= sizeof($treneriInfo['ime'])) : ?>
    <div class="container-fluid d-flex justify-content-center mt-5">
      <div class="col-lg-4 col-md-6">
        <div class="position-relative">
          <div class="position-relative overflow-hidden rounded">
            <img class="img-fluid w-100" src="./images/<?php echo $treneriInfo['slika'][$_GET['trener'] - 1]; ?>" alt="Trener 1">
          </div>
          <div class="treneri_info position-absolute start-0 bottom-0 w-100 rounded-bottom text-center p-4">
            <h5 class="text-uppercase text-light"><?php echo $treneriInfo['ime'][$_GET['trener'] - 1]; ?> (<?php echo $treneriInfo['godine'][$_GET['trener'] - 1] ?>)</h5>
            <p class="text-uppercase text-secondary m-0"><?php echo $treneriInfo['usluga'][$_GET['trener'] - 1]; ?></p>
            <p class="text-uppercase text-secondary m-0"><?php echo $treneriInfo['broj_tel'][$_GET['trener'] - 1]; ?></p>
            <p class="text-uppercase text-primary m-0">Cena: <?php echo $treneriInfo['cena'][$_GET['trener'] - 1]; ?>.00 </p>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-3 mb-3">
      <form method="post">
        <button name="submit" class="btn btn-primary py-md-3 px-md-5 me-3">Potvrdi!</button>
      </form>
    </div>
    <?php if ($errorMsg != '') : ?>
      <div class="d-flex justify-content-center mx-4 mb-lg-4 alert alert-danger" role="alert">
        <ul class="errors"><?php echo $errorMsg; ?></ul>
      </div>
    <?php endif; ?>
    <?php if ($success) : ?>
      <div class="d-flex justify-content-center mb-lg-4 alert alert-success" role="alert">
        <p class="success"><?php echo 'Trener je angažovan, vraćam nazad...' ?></p>
      </div>
    <?php endif; ?>
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