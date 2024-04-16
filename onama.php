<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teretana | O Nama</title>
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
              <a href="./onama.php" class="nav-item nav-link active">O Nama</a>
              <a href="./treneri.php" class="nav-item nav-link">Treneri</a>
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

  <div class="container-fluid p-5">
    <div class="row gx-5">
      <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
        <div class="position-relative h-100">
          <img class="position-absolute w-100 h-100 rounded" src="./images/trener2.jpg" style="object-fit: cover;">
        </div>
      </div>
      <div class="col-lg-7">
        <div class="mb-4">
          <h5 class="text-primary text-uppercase">O Nama</h5>
          <h1 class="display-3 text-uppercase text-light mb-0">Dobrodošli</h1>
        </div>
        <h4 class="text-body mb-4">Diam dolor diam ipsum tempor sit. Clita erat ipsum et lorem stet no labore lorem sit clita duo justo magna dolore</h4>
        <p class="mb-4">Nonumy erat diam duo labore clita. Sit magna ipsum dolor sed ea duo at ut. Tempor sit lorem sit magna ipsum duo. Sit eos dolor ut sea rebum, diam sea rebum lorem kasd ut ipsum dolor est ipsum. Et stet amet justo amet clita erat, ipsum sed at ipsum eirmod labore lorem.</p>
        <div class="rounded bg-dark p-5">
          <ul class="nav nav-pills justify-content-between mb-3">
            <li class="nav-item w-50">
              <a class="nav-link text-uppercase text-center w-100" data-bs-toggle="pill" href="#pills-1">O Nama</a>
            </li>
            <li class="nav-item w-50">
              <a class="nav-link text-uppercase text-center w-100 active" data-bs-toggle="pill" href="#pills-2">Zašto Baš Mi?</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade" id="pills-1">
              <p class="text-secondary mb-0">Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et, tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus clita duo justo et tempor consetetur takimata eirmod, dolores takimata consetetur invidunt magna dolores aliquyam dolores dolore. Amet erat amet et magna</p>
            </div>
            <div class="tab-pane fade active show" id="pills-2">
              <p class="text-secondary mb-0">Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et, tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus clita duo justo et tempor consetetur takimata eirmod, dolores takimata consetetur invidunt magna dolores aliquyam dolores dolore. Amet erat amet et magna</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid  position-relative px-5 mt-5" style="margin-bottom: 180px;">
    <div class="row g-5 gb-5">
      <div class="col-lg-4 col-md-6">
        <div class="bg-light rounded text-center p-5">
          <i class="flaticon-six-pack display-1 text-primary"></i>
          <h3 class="text-uppercase my-4">Bodibilding</h3>
          <p>Sed amet tempor amet sit kasd sea lorem dolor ipsum elitr dolor amet kasd elitr duo vero amet amet stet</p>
          <a class="text-uppercase" href="">Saznaj Više <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="bg-light rounded text-center p-5">
          <i class="flaticon-barbell display-1 text-primary"></i>
          <h3 class="text-uppercase my-4">Dizanje Tegova</h3>
          <p>Sed amet tempor amet sit kasd sea lorem dolor ipsum elitr dolor amet kasd elitr duo vero amet amet stet</p>
          <a class="text-uppercase" href="">Saznaj Više <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="bg-light rounded text-center p-5">
          <i class="flaticon-bodybuilding display-1 text-primary"></i>
          <h3 class="text-uppercase my-4">Bildovanje Mišića</h3>
          <p>Sed amet tempor amet sit kasd sea lorem dolor ipsum elitr dolor amet kasd elitr duo vero amet amet stet</p>
          <a class="text-uppercase" href="">Saznaj Više <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-12 col-md-6 text-center">
        <h1 class="text-uppercase text-light mb-4">Najbolja teretana u regionu</h1>
        <?php if (isset($_SESSION['userid'])) : ?>
          <a href="./clanarina.php" class="btn btn-primary py-3 px-5">Učlani se!</a>
        <?php else : ?>
          <a href="./registracija.php" class="btn btn-primary py-3 px-5">Registruj se!</a>
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