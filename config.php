<?php
// settings
$api['api_key'] = '6688d693255b545cddfcafa3fc00768a';
$api['api_secret'] = 'b4b2d76a407c66516c6892b57e715925';
$api['token'] = '044e4627a44ee66e220761ed87064588';
$api['api_sig'] = '73b3b7421859ba34f52773d2956c6a47';
$api['sk'] = '5e943628c89b072a23ba852d5f74fd5a';


// api links
$api['artist.link'] = 'http://www.last.fm/music/';
$api['artist.getinfo'] = 'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&api_key='.$api['api_key'].'&artist=';
$api['artist.search'] = 'http://ws.audioscrobbler.com/2.0/?method=artist.search&api_key='.$api['api_key'].'&artist=';
$api['artist.gettags'] = 'http://ws.audioscrobbler.com/2.0/?method=artist.gettags&api_key='.$api['api_key'].'&api_sig='.$api['api_sig'].'&sk='.$api['sk'].'&artist=';


// info template
$info_template = '$IMG_BB$'."\n"
                .'[size=5][url=$URL$]$NAME$[/url][/size]'."\n"
                .'($TAGS$)'."\n"
                .'[b]$PLAYS$[/b] plays ($LISTENERS$ listeners)'."\n"
                .'Similar artists: [i]$SIMILARS$[/i]'."\n";

$search = array('$NAME$',
                '$URL$',
                '$IMG$',
                '$IMG_BB$',
                '$IMG_HTML$',
                '$TAGS$',
                '$PLAYS$',
                '$LISTENERS$',
                '$SIMILARS$',
                '$SIMILARS_BB$',
                '$SIMILARS_HTML$');


// template tags
$_template = array();

$_template['head']['title']  = 'LinxFm - Last.FM Artist info from raw album titles.';
$_template['head']['meta']   = '';

$_template['form']['action'] = '';
$_template['form']['method'] = 'post';
$_template['form']['top']    = '';
$_template['form']['bottom'] = '';

$_template['top']['menu']  = '';

$_template['left']['title']  = '';
$_template['left']['col']    = '';

$_template['right']['title'] = '';
$_template['right']['col']   = '';


// htmlentities($str, ENT_QUOTES, 'UTF-8')


// functions
class XMLThing
{
    var $rawXML;
    var $valueArray = array();
    var $keyArray = array();
    var $parsed = array();
    var $index = 0;
    var $attribKey = 'attributes';
    var $valueKey = 'value';
    var $cdataKey = 'cdata';
    var $isError = false;
    var $error = '';

    function XMLThing($xml = NULL)
    {
        $this->rawXML = $xml;
    }

    function load($url = NULL)
    {
        if(is_null($url))
        {
            return false;
        }

        $this->rawXML = @file_get_contents($url);

        if(!$this->rawXML)
        {
            $this->isError = true;
            $this->error = 'error reading: '.$url;

            return false;
        }

        return $this->parse();
    }

    function parse($xml = NULL)
    {
        if(!is_null($xml))
        {
            $this->rawXML = $xml;
        }

        $this->isError = false;

        if(!$this->parse_init())
        {
            return false;
        }

        $this->index = 0;
        $this->parsed = $this->parse_recurse();
        $this->status = 'parsing complete';

        return $this->parsed;
    }

    function parse_recurse()
    {
        $found = array();
        $tagCount = array();

        while(isset($this->valueArray[$this->index]))
        {
            $tag = $this->valueArray[$this->index];
            $this->index++;

            if($tag['type'] == 'close')
            {
                return $found;
            }

            if($tag['type'] == 'cdata')
            {
                $tag['tag'] = $this->cdataKey;
                $tag['type'] = 'complete';
            }

            $tagName = $tag['tag'];

            if(isset($tagCount[$tagName]))
            {
                if($tagCount[$tagName] == 1)
                {
                    $found[$tagName] = array($found[$tagName]);
                }

                $tagRef =& $found[$tagName][$tagCount[$tagName]];
                $tagCount[$tagName]++;
            }
            else
            {
                $tagCount[$tagName] = 1;
                $tagRef =& $found[$tagName];
            }

            switch($tag['type'])
            {
                case 'open':
                    $tagRef = $this->parse_recurse();

                    if(isset($tag['attributes']))
                    {
                        $tagRef[$this->attribKey] = $tag['attributes'];
                    }

                    if(isset($tag['value']))
                    {
                        if(isset($tagRef[$this->cdataKey]))
                        {
                            $tagRef[$this->cdataKey] = (array)$tagRef[$this->cdataKey];
                            array_unshift($tagRef[$this->cdataKey], $tag['value']);
                        }
                        else
                        {
                            $tagRef[$this->cdataKey] = $tag['value'];
                        }
                    }
                break;

                case 'complete':
                    if(isset($tag['attributes']))
                    {
                        $tagRef[$this->attribKey] = $tag['attributes'];
                        $tagRef =& $tagRef[$this->valueKey];
                    }

                    if(isset($tag['value']))
                    {
                        $tagRef = $tag['value'];
                    }
                break;
            }
        }

        return $found;
    }

    function parse_init()
    {
        //$this->parser = xml_parser_create();
        $this->parser = xml_parser_create('UTF-8');

        $parser = $this->parser;
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

        if(!$res = (bool)xml_parse_into_struct($parser, $this->rawXML, $this->valueArray, $this->keyArray))
        {
            $this->isError = true;
            $this->error = 'error: '.xml_error_string(xml_get_error_code($parser)).' at line '.xml_get_current_line_number($parser);
        }
        xml_parser_free($parser);

        return $res;
    }
}
?>