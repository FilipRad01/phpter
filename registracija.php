<?php 
  session_start();
  require_once('./auth/connect_db.php');
  require_once('./auth/register_functions.php');

  // ukoliko je korisnik ulogovan vratice se na pocetnu
  if (isset($_SESSION['userid'])) {
    header("Location: ./index.php");
    exit();
  }

  $success = false;
  $errorMessage = '';

  // kada se stisne dugme regBtn
  if(isset($_POST['regBtn'])) {
    // informacije iz <form>
    $email = $_POST['email'];
    $lozinka = $_POST['password'];
    $ime = $_POST['firstname'];
    $prezime = $_POST['lastname'];
    $telefon = $_POST['telephone'];

    // ovde se prikazuju errori ako postoje, npr ako je unet email koji vec postoji, 
    // ako je lozinka prekratka ili ako nista nije uneseno
    if(checkIfEmailExists($email, $conn)) {
      $errorMessage = 'E-mail već postoji!';
    } else if (strlen($telefon) <= 3 || strlen($telefon) >= 16){
      $errorMessage = 'Telefon nije validan!';
    } else if (strlen($lozinka) < 3) {
      $errorMessage = 'Lozinka mora sadržati bar 3 karaktera!';
    } else if (!strlen($email) || !strlen($ime) || !strlen($prezime)) {
      $errorMessage = 'Greška pri unosu!';
    }
    else { // ukoliko je sve kako treba lozinka se enkriptuje
      $lozinka = md5($lozinka);
      
      // ovo znaci "UBACI U korisnici (ime...) VREDNOSTI ($ime...)"
      $conn->query(
        "INSERT INTO korisnici
          (ime, prezime, broj_tel, email, lozinka) 
        VALUES 
          ('$ime', '$prezime', '$telefon', '$email', '$lozinka');"
      );

      // ako je sve proslo kako treba korisnik ce biti redirect-ovan na prijavu
      $success = true;
      header("Refresh: 2 ; URL =./prijava.php");
    }
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teretana | Registracija</title>
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

  <div class="container mt-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card registration_row">
          <div class="card-body">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4 text-primary">Registracija</p>
                <form method="post" class="mx-1 mx-md-4">
                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="formEmail">E-mail adresa</label>
                      <input type="email" id="formEmail" class="form-control" name="email" />
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="formPassword">Lozinka</label>
                      <input type="password" id="formPassword" class="form-control" name="password" required />
                      <small id="pwHelp" class="form-text text-secondary">Lozinka mora sadržati bar 3 karaktera.</small>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="formName">Ime</label>
                      <input type="text" id="formName" class="form-control" name="firstname" required/>
                    </div>
                  </div>
                  
                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="formLastName">Prezime</label>
                      <input type="text" id="formLastName" name="lastname" class="form-control" required />
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="formTel">Telefon</label>
                      <input type="text" id="formTel" class="form-control" name="telephone" required />
                    </div>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <small id="pwHelp" class="form-text text-muted">Imaš nalog? <a class="reg_link" href="./prijava.php">Prijavi se</a></small>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <input type="submit" class="btn btn-primary btn-lg reg_btn" value="Registruj se!" name="regBtn" />
                  </div>
                </form>
                <?php if ($errorMessage != '') : ?>
                  <div class="d-flex justify-content-center mx-4 mb-lg-4 alert alert-danger" role="alert">
                    <ul class="errors"><?php echo $errorMessage; ?></ul>
                  </div>
                <?php endif;
                if ($success) : ?>
                  <div class="d-flex justify-content-center mb-lg-4 alert alert-success" role="alert">
                    <p class="success"><?php echo 'Uspešna registracija, redirektujem na login...' ?></p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
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