<?php
include('init.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="plain.css" type="text/css" rel="stylesheet" />

  <title><?php echo $_template['head']['title']; ?></title>
  <?php echo $_template['head']['meta']; ?>
</head>

<body>
  <div id="container">
    <div id="menu"><?php echo $_template['top']['menu']; ?></div>
    <form action="<?php echo $_template['form']['action']; ?>" method="<?php echo $_template['form']['method']; ?>">

      <div id="top">
        <?php echo $_template['form']['top']; ?>
      </div>

      <div id="left_col">
        <h5><?php echo $_template['left']['title']; ?></h5>
        <?php echo $_template['left']['col']; ?>
      </div>

      <div id="right_col">
        <h5><?php echo $_template['right']['title']; ?></h5>
        <?php echo $_template['right']['col']; ?>
      </div>

      <div id="bottom">
        <?php echo $_template['form']['bottom']; ?>
      </div>

    </form>

  </div>
</body>

</html>