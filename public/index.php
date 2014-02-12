<html>
<header>
  <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
  <title>Validation's file sample</title>
</header>
  <body>
    <?php
      $path = realpath('../');

      define('ROOT', $path);

      include ROOT . '/Bootstrap.php';

      use MyValidations\Sample;

      $sample = new Sample();

      $sample->start();
    ?>
  </body>
</html>
