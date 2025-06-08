<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?=ucfirst(APP_NAME)?> | <?=ucfirst(URL(0))?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="<?=ROOT('assets/images/icon.ico')?>" rel="icon">
  <link href="<?=ROOT('assets/images/icon.ico')?>" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?=ROOT('assets/vendor/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
  <link href="<?=ROOT('assets/vendor/bootstrap-icons/bootstrap-icons.css')?>" rel="stylesheet">
  <link href="<?=ROOT('assets/vendor/aos/aos.css')?>" rel="stylesheet">
  <link href="<?=ROOT('assets/vendor/swiper/swiper-bundle.min.css')?>" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?=ROOT('assets/css/main.css')?>" rel="stylesheet">

  <!-- =======================================================
  * Template Name: ZenBlog
  * Template URL: https://bootstrapmade.com/zenblog-bootstrap-blog-template/
  * Updated: Aug 08 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="<?=ROOT('')?>" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="<?=ROOT('assets/images/h_icon.ico')?>" width="50" height="50" alt=""> 
        <h1 class="sitename"><?=APP_NAME?></h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?=ROOT('home')?>" class="<?=URL(0) =='home' ? 'active':''?>">Home</a></li>
          <li><a href="<?=ROOT('about')?>" class="<?=URL(0) =='about' ? 'active':''?>">About</a></li>
          <li><a href="<?=ROOT('contact')?>" class="<?=URL(0) =='contact' ? 'active':''?>">Contact</a></li>
          <li><a href="<?=ROOT('post/latest')?>" class="<?=URL(0) =='home' ? 'active':''?>">Latest</a></li>
          <li><a href="<?=ROOT('post/trending')?>" class="<?=URL(0) =='home' ? 'active':''?>">Trending</a></li>
          <li><a href="<?=ROOT('post/popular')?>" class="<?=URL(0) =='home' ? 'active':''?>">Popular</a></li>
          <li class="dropdown"><a href="#"><span>Categories</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <?php $category=get_all_categories_Limit(); ?>
              <?php if (count($category) > 0): ?>
                <?php foreach ($category as $cat): ?>
              <li><a href="<?=ROOT('category/'.$cat['slug'])?>"><?=$cat['category']?></a></li>
              <?php endforeach; ?>
          <?php endif; ?>
            
            </ul>
          </li>
          <?php if(!logged_in()): ?>
            <li><a class="dropdown-item" href="<?=ROOT('login')?>">login</a></li>
            <?php endif?>
          <form action="<?=ROOT('post/search')?>" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
          <div class="input-group ">
            <input value="<?=$_GET['find'] ?? ''?>" name="find" type="search" class="form-control" placeholder="Search..." aria-label="Search">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
          </div>
        </form>

        <?php if(logged_in()):?>
        <div class="dropdown text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?=get_image(user('image'))?>" alt="mdo" style="object-fit: cover;" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small">
            <li><a class="dropdown-item" href="#">Hi, <?=user('username')?></a></li>
            <?php if(is_admin_or_author()): ?>
            <li><a class="dropdown-item" href="<?=ROOT('admin/user_profile')?>">Profile</a></li>
            <li><a class="dropdown-item" href="<?=ROOT('admin/dashboard')?>">Admin</a></li>
            <!-- <li><a class="dropdown-item" href="#">Settings</a></li> -->
            <?php endif?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?=ROOT('logout')?>">Sign out</a></li>
          </ul>
        </div>
        <?php endif;?>
        </ul>
        
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <!-- <div class="header-social-links">
        <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
      </div> -->

    </div>
  </header>