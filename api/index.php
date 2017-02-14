<?php 

// Sample Plugin & Theme API by Kaspars Dambis (kaspars@konstruktors.com)

$packages['ypwp-shipping-master'] = array(
	'versions' => array(
		'2' => array(
			'version' => '2',
			'date' => '2017-02-14',
			'package' => 'https://github.com/sergiocutone/ypwp-shipping/archive/master.zip'
		)
	),
	'info' => array(
		'url' => 'https://sergiocutone.github.io/ypwp-shipping/'
	)	
);



// Process API requests

$action = $_POST['action'];
$args = unserialize(stripslashes($_POST['request']));

if (is_array($args))
	$args = array_to_object($args);

$latest_package = array_shift($packages[$args->slug]['versions']);



// basic_check

if ($action == 'basic_check') {	
	$update_info = array_to_object($latest_package);
	$update_info->slug = $args->slug;
	
	if (version_compare($args->version, $latest_package['version'], '<'))
		$update_info->new_version = $update_info->version;
	
	print serialize($update_info);
}


// plugin_information

if ($action == 'plugin_information') {	
	$data = new stdClass;
	
	$data->slug = $args->slug;
	$data->version = $latest_package['version'];
	$data->last_updated = $latest_package['date'];
	$data->download_link = $latest_package['package'];
	
	$data->sections = array('description' => '<h2>$_POST</h2><small><pre>'.var_export($_POST, true).'</pre></small>'
		. '<h2>Response</h2><small><pre>'.var_export($data, true).'</pre></small>'
		. '<h2>Packages</h2><small><pre>'.var_export($packages, true).'</pre></small>');

	print serialize($data);
}

function array_to_object($array = array()) {
    if (empty($array) || !is_array($array))
		return false;
		
	$data = new stdClass;
    foreach ($array as $akey => $aval)
            $data->{$akey} = $aval;
	return $data;
}

?>
