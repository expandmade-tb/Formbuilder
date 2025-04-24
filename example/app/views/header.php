<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Formbuilder Demo" />
    <meta name="author" content="" />
    <title><?php echo $title ?></title>
    <?php foreach($css_files as $file): ?>
      <link href="<?php echo $file; ?>" rel="stylesheet" />
    <?php endforeach; ?>  
  </head>
  <body id="page">
  <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Formbuilder Demo</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/contact">Contact Form</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/mixed">Mixed Form</a>
        </li>
      </ul>
    </div>
  </div>
</nav>