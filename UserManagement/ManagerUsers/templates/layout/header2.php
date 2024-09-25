<?php

if(!defined('_LEGIT'))
{
    die('Access Denied');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo !empty($data['pageTitle'])?$data['pageTitle']:'Users Management'?> </title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE?>/css/style.css?ver=<?php echo rand()?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

    
</body>
</html>

  <header class="p-3 mb-3 border-bottom">
    <div class="container" >
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start" style="position: relative; left: 1050px; ">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
        </a>
        <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
          <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </form>

        <div class="dropdown text-end">
          <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small">
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="?module=auth&action=logout">Sign out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>