<?php

$meta = json_encode(array(
			'color' => array('red', 'yellow', 'blue'),
			'size' => array('big', 'middle', 'small'),
			)
		);

$sku = json_encode(array(
			"red\tbig" => 1,
			"red\tmiddle" => 3,
			"red\tsmall" => 0,
			"yellow\tbig" => 1,
			"yellow\tmiddle" => 0,
			"yellow\tsmall" => 1,
			"blue\tbig" => 0,
			"blue\tmiddle" => 5,
			"blue\tsmall" => 1,
			)
		);
echo $sku;
