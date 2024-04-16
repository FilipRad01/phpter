<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teretana | Kontakt</title>
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
              <a href="./kontakt.php" class="nav-item nav-link active">Kontakt</a>
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

  <div class="container-fluid p-5">
    <div class="mb-5 text-center">
      <h5 class="text-primary text-uppercase">Kontaktirajte Nas</h5>
      <h1 class="display-3 text-light text-uppercase mb-0">Ostanimo u Kontaktu</h1>
    </div>
    <div class="row g-5 mb-5">
      <div class="col-lg-4">
        <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3">
          <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="fa fa-map-marker-alt fs-4 text-white"></i>
          </div>
          <h5 class="text-uppercase text-primary">Posetite nas</h5>
          <p class="text-secondary mb-0">Lole Ribara 123, Kosovska Mitrovica, SRB</p>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3">
          <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="fa fa-envelope fs-4 text-white"></i>
          </div>
          <h5 class="text-uppercase text-primary">Pišite nam</h5>
          <p class="text-secondary mb-0">teretana_km@gmail.com</p>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3">
          <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
            <i class="fa fa-phone fs-4 text-white"></i>
          </div>
          <h5 class="text-uppercase text-primary">Pozovite nas</h5>
          <p class="text-secondary mb-0">+381 028 67890</p>
        </div>
      </div>
    </div>
    <div class="row g-0">
      <div class="bg-dark p-5 d-flex justify-content-center">
          <div class="col-lg-6">
          <form>
            <div class="row g-3">
              <div class="col-6">
                <input type="text" class="form-control bg-light border-0 px-4" placeholder="Vaše ime" style="height: 55px;">
              </div>
              <div class="col-6">
                <input type="email" class="form-control bg-light border-0 px-4" placeholder="Vaša e-adresa" style="height: 55px;">
              </div>
              <div class="col-12">
                <input type="text" class="form-control bg-light border-0 px-4" placeholder="Naslov" style="height: 55px;">
              </div>
              <div class="col-12">
                <textarea class="form-control bg-light border-0 px-4 py-3" rows="4" placeholder="Message"></textarea>
              </div>
              <div class="col-12">
                <button class="btn btn-primary w-100 py-3" type="submit">Pošaljite poruku</button>
              </div>
            </div>
          </form>
        </div>
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