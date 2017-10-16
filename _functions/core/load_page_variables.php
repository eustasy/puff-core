<?php

function get_page_variable($Line) {
	$Line = explode('=', $Line, 2)[1];
	$Line = trim($Line);
	$Line = str_replace("\'", "'", $Line);
	$Line = trim($Line, '\'";');
	return $Line;
}

function load_page_variables($Sitewide, $File, $Date_Format) {

	$Page['Type']      = false;
	$Page['Title']     = false;
	$Page['Tagline']   = false;
	$Page['Author']    = false;
	$Page['Published'] = false;

	$URL = str_replace($Sitewide['Root'], '', $File);
	$URL = str_replace('index.php', '', $URL);
	if ( $Sitewide['Settings']['Strip PHP from URLs'] ) {
		require_once $Sitewide['Puff']['Functions'].'core/ends_with.php';
		if ( ends_with($URL, '.php') ) {
			$URL = substr($URL, 0, -4);
		}
	}
	$Page['Link'] = $Sitewide['Settings']['Site Root'].$URL;

	$Lines = file($File);
	foreach ($Lines as $Line) {
		if (strpos($Line, '$Page[\'Type\']') !== false) {
			$Page['Type'] = get_page_variable($Line);
		} else if (strpos($Line, '$Page[\'Title\']') !== false) {
			$Page['Title'] = get_page_variable($Line);
		} else if (strpos($Line, '$Page[\'Tagline\']') !== false) {
			$Page['Tagline'] = get_page_variable($Line);
		} else if (strpos($Line, '$Page[\'Description\']') !== false) {
			$Page['Description'] = get_page_variable($Line);
		} else if (strpos($Line, '$Page[\'Author\']') !== false) {
			$Page['Author'] = get_page_variable($Line);
		} else if (strpos($Line, '$Page[\'Published\']') !== false) {
			$Page['Published'] = get_page_variable($Line);
		}
	}

	if ( $Page['Published'] ) {
		$Page['Published'] = date($Date_Format, strtotime($Page['Published']));
	} else {
		$Page['Published'] = date($Date_Format, filemtime($File));
	}

	return $Page;

}
