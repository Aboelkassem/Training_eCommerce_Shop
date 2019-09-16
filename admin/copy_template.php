<?php

	/*
	================================================
	== Template Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = '';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') { //* Start Manage Page ============================================================================================================================= 


		} elseif ($do == 'Add') { //* Start Add Page ============================================================================================================================= 


		} elseif ($do == 'Insert') { //* Start Insert Page ============================================================================================================================= 


		} elseif ($do == 'Edit') { 	//* Start Edit Page ============================================================================================================================= 


		} elseif ($do == 'Update') { //* Start Update Page ============================================================================================================================= 


		} elseif ($do == 'Delete') { //* Start Delete Page ============================================================================================================================= 


		} elseif ($do == 'Activate') { //* Start Activiate Page ============================================================================================================================= 


		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>