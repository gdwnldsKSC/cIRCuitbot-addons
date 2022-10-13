<?php
function parseURLfromMessage($data){
    global $ircdata;
    global $config;

    if(stristr($data, "https://")) {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#i', $data, $urlmatch);
        foreach($urlmatch[0] as $url) {
            $urltitle = trim(getTitle($url));
            $urltitle = urldecode($urltitle);
            $urltitle = html_entity_decode($urltitle);
            if(strlen($urltitle)>5) {
                $message = "".$ircdata['usernickname'].": URL Title - ".$urltitle."";
                sendPRIVMSG($config['channel'], "".$message."");                        
            }
        }
    }
    if(stristr($data, "http://")) {
        preg_match_all('#\bhttp?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#i', $data, $urlmatch);
        foreach($urlmatch[0] as $url) {
            $urltitle = trim(getTitle($url));
            $urltitle = urldecode($urltitle);
            $urltitle = html_entity_decode($urltitle);
            if(strlen($urltitle)>5) {
                $message = "".$ircdata['usernickname'].": URL Title - ".$urltitle."";
                sendPRIVMSG($config['channel'], "".$message."");                        
            }
        }
    }
    return true;
}

function getTitle($url) {
    $badExtensions = array(
        '.exe',
        '.pdf',
        '.sh',
        '.py',
        '.pl',
        '.tcl',
        '.bat',
    );
    
    foreach($badExtensions as $filecheck) {
        if(stristr($url,$filecheck)) {
            $title = "Unable to parse URL that points to a ".$filecheck." file.";
            return $title;
        }
    }

    $page = file_get_contents(trim($url));
    $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
    return $title;
}