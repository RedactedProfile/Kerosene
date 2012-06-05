<?php
function redirect($path) {
	try {
		header("location: $path");
	} catch (Exception $e) {
		echo "<script>window.location='$path';</script>";
	}
}