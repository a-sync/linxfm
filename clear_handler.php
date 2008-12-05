<?php
// template tags
$_template['form']['action'] = '?step=2';
$_template['form']['top']    = 'Info template tags: $NAME$, $URL$, $IMG$, $IMG_BB$, $IMG_HTML$, $TAGS$, $PLAYS$, $LISTENERS$, $SIMILARS$, $SIMILARS_BB$, $SIMILARS_HTML$ <br/><textarea name="info_template">'.$info_template.'</textarea>';
$_template['form']['bottom'] = 'Please check the cleared titles!<br/><input type="submit" value="Get artist infos!" />';

$_template['top']['menu']  = '<a href="index.php?step=0">Add titles</a> <span>&gt;&gt;</span> '
                            .'<b><a href="index.php?step=1">Clear titles</a></b> <span>&gt;&gt;</span> '
                            .'<a href="index.php?step=2">Get artist infos</a> <span>&gt;&gt;</span> '
                            .'<a href="index.php?step=3">Search artists</a>';

$_template['left']['title']  = 'Raw titles';
$_template['right']['title'] = 'Cleared titles';


// handler
$raw_titles_output = '';
$cleared_titles_output = '';

$raw_titles = explode("\n", $_POST['raw_titles']);//sorok szétbontása
$dupe = array();

$nl = "";
foreach($raw_titles as $key => $raw_title)
{
  $raw_title = trim($raw_title);
  $raw_titles_output .= $nl.htmlspecialchars($raw_title);

  $title = '';

  if($raw_title != '')
  {
    $title = explode('-', $raw_title);//[0] elvileg az előadó
    $title = trim(str_replace(array('_', '  '), ' ', $title[0]));

    if($title == '')
    {
      $title = trim(str_replace(array('_', '  '), ' ', $raw_title));
    }

    if($title != '')
    {
      $title = htmlspecialchars($title);

      if(!$dupe[$title])
      {
        $cleared_titles_output .= $nl.$title;
      }
      $dupe[$title]++;
    }
  }

  $nl = "\n";
}

$_template['left']['col'] = '<textarea readonly="readonly">'.$raw_titles_output.'</textarea>';
$_template['right']['col'] = '<textarea name="cleared_titles">'.$cleared_titles_output.'</textarea>';
?>