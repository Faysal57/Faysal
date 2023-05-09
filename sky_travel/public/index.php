<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="../public/assets/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="../public/assets/css/sky_travel.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <script src="../public/assets/bootstrap/js/bootstrap.bundle.js"></script>
      <script src="https://kit.fontawesome.com/6d2ab15fef.js" crossorigin="anonymous"></script>
      <title>Accueil</title>
   </head>
      <body>
      <header>
          <?php
             include('../vue/header.php');
          ?>
      </header>
      <?php
    


    if (!isset($_GET ['page'])) {
        include ('../vue/accueil.php');      
    } else { 
        if ($_GET['page']==1) {
            include ('../vue/accueil.php');
        }
        
        
    }

   ?>

      
    
       <!-- <footer>
          <?php
             include('../vue/footer.php');
          ?>
       </footer> -->

       <script src="assets/js/script.js"></script>
      
   </body>
</html>