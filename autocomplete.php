<?php
	require('includes/application_top.php');

	$return_arr = array();
	
	// Check if MySQLi is enabled
	if ( class_exists('mysqli') ) {
	$$link = 'db_link';

	if ( $stmt = $$link->prepare("SELECT pd.products_id, pd.products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd LEFT JOIN " . TABLE_PRODUCTS . " p ON pd.products_id = p.products_id WHERE p.products_status = '1' AND p.products_id = pd.products_id AND pd.language_id = ? AND pd.products_name LIKE ? LIMIT 0,10" ) ) {

		$tt1 = (int)$languages_id;
		$tt2 = '%' . tep_db_prepare_input($_REQUEST['term']) . '%';
		$stmt->bind_param("is", $tt1, $tt2);
		$stmt->execute(); 

		$stmt->bind_result($products_id, $products_name);

		while ($stmt->fetch()) {
			$row_array['id'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$products_id);
			$row_array['value'] = utf8_encode($products_name );

			array_push($return_arr, $row_array); 
		}
		$stmt->close(); 

	} else {
	// Uncomment to debug MySQLi prepared statement
		//echo "Prepare failed: (" . $$link->errno . ") " . $$link->error;
	}

	// Save session data and close connection
	session_write_close();
	$$link->close();
	
	} else {
		// Fallback to deprecated MySQL connection
		$keyword = tep_db_prepare_input($_REQUEST['term']);
		$fetch = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_PRODUCTS . " p on pd.products_id = p.products_id WHERE p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.products_name LIKE '%" . tep_db_input($keyword) . "%'  LIMIT 0,10");


		while ($row = tep_db_fetch_array($fetch)) { 
			$row_array['id'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . (int)$row['products_id']);
			$row_array['value'] = utf8_encode( $row['products_name'] );

			array_push($return_arr, $row_array); 
		}
	}
	
	print json_encode($return_arr);
	tep_exit();
?>