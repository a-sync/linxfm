<?php
// template tags
$_template['form']['action'] = '?step=3';
$_template['form']['top']    = 'Info template tags: $NAME$, $URL$, $IMG$, $IMG_BB$, $IMG_HTML$, $TAGS$, $PLAYS$, $LISTENERS$, $SIMILARS$, $SIMILARS_BB$, $SIMILARS_HTML$ <br/><textarea name="info_template">'.(($_POST['info_template']) ? htmlspecialchars($_POST['info_template']) : $info_template).'</textarea>';
$_template['form']['bottom'] = 'Copy your artist infos!';

$_template['top']['menu']  = '<a href="index.php?step=0">Add titles</a> <span>&gt;&gt;</span> '
                            .'<a href="index.php?step=1">Clear titles</a> <span>&gt;&gt;</span> '
                            .'<b><a href="index.php?step=2">Get artist infos</a></b> <span>&gt;&gt;</span> '
                            .'<a href="index.php?step=3">Search artists</a>';

$_template['left']['title']  = 'Info';
$_template['right']['title'] = 'Titles not found / Search';

$peti_sablon = '$IMG_HTML$<br/><font size="4"><a href="$URL$">$NAME$</a></font><br/>plays: $PLAYS$, listeners: $LISTENERS$<br/>similars: $SIMILARS$<br/><br/>';
$peti_output = '';


// handler
$info_output = '';
$failed_titles_output = '';

$cleared_titles = explode("\n", $_POST['cleared_titles']);//sorok szétbontása
$dupe = array();

$xml_parser = new XMLThing;


$nl = "";
foreach($cleared_titles as $key => $cleared_title)
{
  $title = trim($cleared_title);

  if($title != '')
  {
    if(!$dupe[$title])
    {
      $info_xml = $api['artist.getinfo'].urlencode($title);
      $artist_info = $xml_parser->load($info_xml);

      if($artist_info['lfm']['attributes']['status'] == 'ok')
      {
        $artist_info = $artist_info['lfm']['artist'];

        $info_template = ($_POST['info_template']) ? $_POST['info_template'] : $info_template;

        $similars = '';
        $sep = '';
        $artist_info_similars = ($artist_info['similar']['artist']) ? $artist_info['similar']['artist'] : array();
        foreach($artist_info_similars as $subkey => $similar)//helyette similar artist url
        {
          $similars .= $sep.$similar['name'];
          //$similars_bb .= $sep.'[url='.$api['artist.link'].urlencode($similar['name']).']'.$similar['name'].'[/url]';
          $similars_bb .= $sep.'[url='.$similar['url'].']'.$similar['name'].'[/url]';
          //$similars_html .= $sep.'<a href="'.$api['artist.link'].urlencode($similar['name']).'">'.$similar['name'].'</a>';
          $similars_html .= $sep.'<a href="'.$similar['url'].'">'.$similar['name'].'</a>';

          $sep = ', ';
        }

        $img = str_replace('/serve/34/', '/serve/252/', trim($artist_info['image'][0]['value']));
        $img_bb = ($img == '') ? '' : '[img]'.$img.'[/img]';
        $img_html = ($img == '') ? '' : '<img src="'.$img.'" alt=" " />';

        $replace = array($artist_info['name'],
                         $artist_info['url'],
                         $img,
                         $img_bb,
                         $img_html,
                         '',//tags
                         $artist_info['stats']['playcount'],
                         $artist_info['stats']['listeners'],
                         $similars,
                         $similars_bb,
                         $similars_html);

        //$info_output .= $nl.htmlspecialchars(str_replace($search, $replace, $info_template));
        $peti_output .= str_replace($search, $replace, $peti_sablon);
      }
      else
      {
        $failed_titles_output .= $nl.htmlspecialchars($title);
      }
    }
    $dupe[$title]++;
  }

  $nl = "\n";
}
$xml_parser = NULL;


//$_template['left']['col'] = '<textarea readonly="readonly">'.$info_output.'</textarea>';
$_template['left']['col'] = $peti_output;
$_template['right']['col'] = '<textarea name="cleared_titles">'.$failed_titles_output.'</textarea>';

if($failed_titles_output != '') { $_template['form']['bottom'] .= '<br/><input type="submit" value="Search the failed titles!" />'; }
?>