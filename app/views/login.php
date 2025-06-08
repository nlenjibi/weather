<?php 

  if(!empty($_POST))
  {
    //validate
    $errors = [];

    $query = "select * from users where email = :email limit 1";
    $row = query($query, ['email'=>$_POST['email']]);

    if($row)
    {
      $data = [];
      if(password_verify($_POST['password'], $row[0]['password']))
      {
        //grant access
        authenticate($row[0]);
        session('success', 'Welcome back, '.$row[0]['username']);
        if($row[0]['role'] == 'admin' || $row[0]['role'] == 'author')
          redirect('admin');
      
        redirect('home');

      }else{
        $errors['email'] = "wrong email or password";
      }

    }else{
      $errors['email'] = "wrong email or password";
    }
  }

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Sleek Dashboard - Free Bootstrap 4 Admin Dashboard Template and UI Kit. It is very powerful bootstrap admin dashboard, which allows you to build products like admin panels, content management systems and CRMs etc.">

    <title><?=APP_NAME?> | Login</title>

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="<?=ROOT?>/assets/css/sleek.css" />

    <!-- FAVICON -->
    <link href="<?=ROOT('assets/images/icon.ico')?>" rel="icon">

    <!--
      HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?=ROOT?>/assets/plugins/nprogress/nprogress.js"></script>
  </head>

  <body class="" id="body">
    <div class="container d-flex align-items-center justify-content-center vh-100">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-10">
          <div class="card">
            <div class="card-header bg-primary">
              <div class="app-brand">
                <a href="#">
                  <svg class="brand-icon" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" width="30" height="33"
                    viewBox="0 0 30 33">
                    <g fill="none" fill-rule="evenodd">
                      <path class="logo-fill-blue" fill="#7DBCFF" d="M0 4v25l8 4V0zM22 4v25l8 4V0z" />
                      <path class="logo-fill-white" fill="#FFF" d="M11 4v25l8 4V0z" />
                    </g>
                  </svg>

                  <span class="brand-name">MyBlog</span>
                </a>
              </div>
            </div>

            <div class="card-body p-5">
              <h4 class="text-dark mb-5">Sign In</h4>
              <?php if (!empty($errors['email'])):?>
                <div class="alert alert-danger"><?=$errors['email']?></div>
              <?php endif;?>
              <form action="" method="post">
                <div class="row">
                  <div class="form-group col-md-12 mb-4">
                    <input value="<?=old_value('email')?>" name="email" type="email" class="form-control input-lg" id="email" aria-describedby="emailHelp" placeholder="Username">
                  </div>

                  <div class="form-group col-md-12 ">
                    <input value="<?=old_value('password')?>" type="password" name="password" class="form-control input-lg" id="password" placeholder="Password">
                  </div>

                  <div class="col-md-12">
                    <div class="d-flex my-2 justify-content-between">
                      <div class="d-inline-block mr-3">
                        <label class="control control-checkbox">Remember me
                          <input  name="remember" type="checkbox" value="1" <?=old_checked('remember')?>/>
                          <div class="control-indicator"></div>
                        </label>
                      </div>

                      <!-- <p><a class="text-blue" href="#">Forgot Your Password?</a></p> -->
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary btn-block mb-4">Sign In</button>

                    <p>Don't have an account yet ?
                      <a class="text-blue" href="<?=ROOT('signup')?>">Sign Up</a>
                    </p>
                    
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- <script type="module">
      import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

      const el = document.createElement('pwa-update');
      document.body.appendChild(el);
    </script> -->

    <!-- Javascript -->
    <script src="<?=ROOT?>/assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?=ROOT?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?=ROOT?>/assets/js/sleek.js"></script>
  <link href="<?=ROOT?>/assets/options/optionswitch.css" rel="stylesheet">
<script src="<?=ROOT?>/assets/options/optionswitcher.js"></script>
</body>
</html>