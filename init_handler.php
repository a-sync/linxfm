<?php
// template tags
$_template['form']['action'] = '?step=1';
$_template['form']['bottom'] = '<input type="submit" value="Clear titles!" />';

$_template['top']['menu']  = '<b><a href="index.php?step=0">Add titles</a></b> <span>&gt;&gt;</span> '
                            .'<a href="index.php?step=1">Clear titles</a> <span>&gt;&gt;</span> '
                            .'<a href="index.php?step=2">Get artist infos</a> <span>&gt;&gt;</span> '
                            .'<a href="index.php?step=3">Search artists</a>';

$_template['left']['title']  = 'Raw titles';
$_template['left']['col']    = '<textarea name="raw_titles"></textarea>';


?>