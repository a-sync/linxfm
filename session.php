<?php
$fail = '';

$api_key = (!preg_match('/^[A-Za-z0-9]{32,32}$/', trim($_POST['api_key']))) ? false : trim($_POST['api_key']);
$api_secret = (!preg_match('/^[A-Za-z0-9]{32,32}$/', trim($_POST['api_secret']))) ? false : trim($_POST['api_secret']);

$token = ($_POST['token']) ? trim($_POST['token']) : trim($_GET['token']);
$token = (!preg_match('/^[A-Za-z0-9]{32,32}$/', $token)) ? false : $token;

$api_sig = false;
$getsession = false;

$sk = (!preg_match('/^[A-Za-z0-9]{32,32}$/', trim($_POST['sk']))) ? false : trim($_POST['sk']);

if($_GET['step'] == 1 || $sk) {
  if($api_key && $token && $api_secret) {
    $api_sig = md5('api_key'.$api_key.'methodauth.getsessiontoken'.$token.$api_secret);
    $getsession = '&token='.$token.'&api_key='.$api_key.'&api_sig='.$api_sig;
  }
  elseif($api_key && $token && !$api_secret) {
    $fail = 'I need an <i>api secret</i>. Get it from <a href="http://www.last.fm/api/account" target="_blank">here</a>.';
  }
  elseif($_POST['auth'] && $api_key) {
    header("Location: http://www.last.fm/api/auth/?api_key=".$_POST['api_key']);
    exit;
  }
  else { $fail = 'You need an <i>api key</i> first. Get it from <a href="http://www.last.fm/api/account" target="_blank">here</a>.'; }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="plain.css" type="text/css" rel="stylesheet" />

  <title>Get a freakin' last.fm session key!</title>
</head>

<body>
  <div id="container">
    <div id="menu">Get a freakin' last.fm session key!</div>
    <?php if($sk) { ?>
      <div>
        api_key: <?php echo htmlspecialchars($api_key); ?><br/>
        api_secret: <?php echo htmlspecialchars($api_secret); ?><br/>
        token: <?php echo htmlspecialchars($token); ?><br/>
        api_sig: <?php echo htmlspecialchars($api_sig); ?><br/>
        sk: <?php echo htmlspecialchars($sk); ?><br/>
      </div>
    <?php } elseif($api_sig) { ?>
      <form action="?step=2" method="post">
        Api key: <input type="text" name="api_key" value="<?php echo $api_key; ?>" readonly/>
        <br/>
        Api secret: <input type="text" name="api_secret" value="<?php echo $api_secret; ?>" readonly/>
        <br/>
        Token: <input type="text" name="token" value="<?php echo $token; ?>" readonly/>
        <br/>
        Api signature: <input type="text" name="api_sig" value="<?php echo $api_sig; ?>" readonly/>
        <br/>
        Session key: <input type="text" name="sk" value=""/>
        <br/><br/>
        <iframe width="800" height="400" src="http://ws.audioscrobbler.com/2.0/?method=auth.getsession<?php echo $getsession; ?>" frameborder="0"></iframe>
        <br/><br/>
        <input type="submit" value="Go man go!" />
      </form>
    <?php } else { ?>
      <form action="?step=1" method="post">
        Api key: <input type="text" name="api_key" value="<?php echo $api_key; ?>" />
        <br/>
        Api secret: <input type="text" name="api_secret" value="<?php echo $api_secret; ?>" />
        <br/>
        Token: <input type="text" name="token" value="<?php echo $token; ?>" />
        <br/><br/>
        <input name="auth" type="submit" value="Go man go!" />
      </form>
    <?php echo $fail; } ?>
  </div>
</body>

</html>