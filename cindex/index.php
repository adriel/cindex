<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Listing <?php echo $_SERVER["REQUEST_URI"]?></title>
	<link rel="stylesheet" href="/style/main.css" type="text/css" />
</head>
<body id="index" class="home">
<header>
	<h1>Directory listing</h1>
</header>
	<?php
	//
	// (c) 2011
	//
	ini_set('display_errors', 0);
	define(debugInfo, FALSE);

	$uri_arr = get_uri_scheme_name();

	// echod=Debug Info String
	function echod($echo_input)
	{
		if ("$echo_input") {
			if (debugInfo) {
				# Only echo if debugging is on
				echo "DEBUG: $echo_input<br />\n";
			}
		}
	}

$files = scandir(URL_FORMAT("full_uri_path"));

echo "<p class=\"location\"><span id=\"back-arrow\">&lsaquo;</span> <a href=\"".dirname(URL_FORMAT("just_uri", get_uri_scheme_name() ))."\">".urldecode(URL_FORMAT("full_url", get_uri_scheme_name()))."</a></p>\n";

// List of extensions to list
$extensions = array("");
$ext = array("");

// Loop through each filename of scandir
foreach ($files as $file_name) {
	// Construct a full path "/www/folder/file.php"
	$file_path_sys = URL_FORMAT("full_uri_path", get_uri_scheme_name())."$file_name";
	// Full site path "http://localhost:8888/folder/file.php"
	$full_url_start = URL_FORMAT("full_url", get_uri_scheme_name())."$file_name";
	// Full site path "/folder/file.php"
	$url_start = URL_FORMAT("just_uri", get_uri_scheme_name())."$file_name";

	// Is it a file? If so, get the extension using some function you created
	if(is_file("$file_path_sys") || is_dir("$file_path_sys")) {
		// Only get file extention if it's a file
		if (is_file("$file_path_sys") ) {
			$ext = get_file_ext("$file_name");
		}

		// Is the file extension not included in the array of forbidden extensions?
		// Since it is not included, execute code to list the file or whatever
		if (!in_array($ext,$extensions)) {

			if ("$file_name" != ".") {
				if ("$file_name" == "..") {
				}
				// Code for files not excluded
				else {
					// Check if it's a directoryu, if it is add the ending slash
					if (is_dir("$file_path_sys")) {
						$is_dir_prefix = "/";
					}
					else {
						$is_dir_prefix = "";
					}

					// Get file time different since creation.
					if (file_exists("$file_path_sys")) {
						$file_time = filemtime("$file_path_sys");
					    // echo "$filename was last modified: " . date ("F d Y H:i:s.", $fileTime);
						$time_diff_hours = timeDiffHours("$file_time",time());
						// echo "[DIFF: $TIME_DIFF_HOURS ]";

						// File size in MB
						$file_size = file_size(filesize("$file_path_sys"));
					}

					$url_format_size = "		<div class=\"filesizealt\"><div class=\"filesize\">$file_size</div></div>\n";
					$url_format = "		<pre class=\"filename\"><a href=\"".trim(URL_ENCODE("$url_start$is_dir_prefix"))."\">".urldecode("$full_url_start$is_dir_prefix")."</a></pre>\n";

					if (is_dir("$file_path_sys")) {
						$DIR_LIST .= "$url_format";
						$DIR_LIST_SIZE .= "$url_format_size";
					}
					elseif (is_file("$file_path_sys")) {
						// If time diff shortter then 12 hours then display file in NEW aria.
						if ("$time_diff_hours" < "12") {
							$FILE_LIST_NEW .= "$url_format";
							$FILE_LIST_NEW_SIZE .= "$url_format_size";
						}
						elseif (preg_match('/720p|1080p/',"$file_name") ) {
							$FILE_LIST_HD .= "$url_format";
							$FILE_LIST_HD_SIZE .= "$url_format_size";
						}
						else {
							$FILE_LIST .= "$url_format";
							$FILE_LIST_SIZE .= "$url_format_size";
						}
					}
					else {
						$OTHER_LIST .= "? - $url_format";
						$OTHER_LIST_SIZE .= "$url_format_size";
					}
				}
			}
		}
	}
}

	if (trim("$DIR_LIST")) {
		echo "<table>\n";
		echo "<caption class=\"title\">Directories</caption>\n";
		echo "<tbody>\n<tr>\n";
		echo "	<td class=\"tsize\">\n$DIR_LIST_SIZE\n	</td>\n";
		echo "	<td class=\"tfile\">\n$DIR_LIST\n	</td>\n";
		echo "</tr>\n</tbody>\n";
		echo "</table>\n";
	}

	if (trim("$FILE_LIST_NEW")) {
		echo "<table>\n";
		echo "<caption class=\"title\">Files NEW (~12h)</caption>\n";
		echo "<tr>\n";
		echo "	<td class=\"tsize\">\n$FILE_LIST_NEW_SIZE\n	</td>\n";
		echo "	<td class=\"tfile\">\n$FILE_LIST_NEW\n	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

	if (trim("$FILE_LIST_HD")) {
		echo "<table>\n";
		echo "<caption class=\"title\">Files 720p/1080p</caption>\n";
		echo "<tr>\n";
		echo "	<td class=\"tsize\">\n$FILE_LIST_HD_SIZE\n	</td>\n";
		echo "	<td class=\"tfile\">\n$FILE_LIST_HD\n	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

	if (trim("$FILE_LIST")) {
		echo "<table>\n";
		echo "<caption class=\"title\">Files</caption>\n";
		echo "<tr>\n";
		echo "	<td class=\"tsize\">\n$FILE_LIST_SIZE\n	</td>\n";
		echo "	<td class=\"tfile\">\n$FILE_LIST\n	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}

	if (trim("$OTHER_LIST")) {
		echo "<table>\n";
		echo "<caption class=\"title\">Other?</caption>\n";
		echo "<tr>\n";
		echo "	<td class=\"tsize\">\n$OTHER_LIST_SIZE\n	</td>\n";
		echo "	<td class=\"tfile\">\n$OTHER_LIST\n	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}



// Find and replace spaces with %20
function URL_ENCODE($url)
{
	$encoded_url = str_replace(" ", "%20", "$url");
	return "$encoded_url";
}


// Gets the time diff in hours from 2 unix strings
// arg 1 INT:
// arg 2 INT:
// timeDiffHours("$fileTime",time())
function timeDiffHours($firstTime,$lastTime)
{

	// Perform subtraction to get the difference (in seconds) between times
	$timeDiff=$lastTime-$firstTime;

	// Time diff in hours (rounds to the nearest hour)
	$timeDiffH = round($timeDiff/60/60,'0');
	// echo " -- TIMED: $timeDiffH -- <br />";
	return "$timeDiffH";
}
// Converts any input in Bytes to a human readable format.
// Thanks to http://snipplr.com/view/4633/convert-size-in-kb-mb-gb-/ for the "file_size" function.
function file_size($inputSizeBytes)
{
    $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		// I added number_format to make sure that the output will always have 2 decimal places (e.g. input: 0.2, output: 0.20)
    return $inputSizeBytes ? number_format(round($inputSizeBytes/pow(1024, ($i = floor(log($inputSizeBytes, 1024)))), 2), 2) . $filesizename[$i] : '0 Bytes';
}

	/**
	 * Gets the URI even if loading the file E.G. 10.10.10.2/folder/file.php
	 *
	 * @return 10.10.10.2'/this/part/file.php' || 10.10.10.2'/this/part/'
	 * @author netl
	 **/
	function REQUEST_URI()
	{
		$url = $_SERVER['REQUEST_URI'];

		// Check if the url is the index.php file, if so then take out file name
		if (preg_match("/\.php$/i", "$url")) {
			// Take out the filename and add a trailling slash
			$url_dirname = dirname($_SERVER['REQUEST_URI'])."/ ";
		} else {
			$url_dirname = $_SERVER['REQUEST_URI'];
		}

		echod("1: $url_dirname <br />\n");
		echod("2: $root_dir_URI <br />\n");
		echod("3: $root_dir <br />\n");

		return trim(urldecode("$url_dirname"));

	}


	/**
	 * Get URI scheme used (HTTP/HTTPS)
	 *
	 * @return https or http
	 * @author netl
	 **/
	function get_uri_scheme_name()
	{
		// Define verables
		$http_url_start = NULL;
		$url_port = NULL;

		// If the HTTPS _SERVER variable is set, then use it else detect via port
		if (isset($_SERVER['HTTPS'])) {

			// If https then it's https else http
			if ($_SERVER['HTTPS'] == 'on') {

				$http_url_start = 'https://';

			}
			else {

				$http_url_start = 'http://';

			}
		// If HTTPS _SERVER is not set then try SERVER_PORT
		}elseif (isset($_SERVER['SERVER_PORT'])) {

				if ($_SERVER['SERVER_PORT'] == 443) {

					$http_url_start = 'https://';

				}elseif ($_SERVER['SERVER_PORT'] == 80) {

					$http_url_start = 'http://';

				}else {

					$http_url_start = 'http://';
					$url_port = (int) $_SERVER['SERVER_PORT'];

				}
		// else fallback to standard http
		}else {

			$http_url_start = 'http://';

		}

		$url['scheme'] = $http_url_start;
		$url['port'] = $url_port;
		// return $url['port'] = isset($url_port) ? $url_port: NULL; // already defined as NULL if nothing is put in this variable
		return $url;
	}
	// print_r(get_uri_scheme_name()); // debugging

	/**
	 * Constructs the URL path
	 *
	 * @return Full URL E.G. https://google.com/files/link/
	 * @author netl
	 **/

	function URL_FORMAT($option, $uri_arr)
	{

			// 10.10.10.2 || localhost || google.com
			$server_name = $_SERVER["SERVER_NAME"];

			$request_uri = REQUEST_URI();

		// Debugging...
		echod("Start [server name: \"$_SERVER[SERVER_NAME]\" - server port: \"$_SERVER[SERVER_PORT]\" - server request uri: \"$_SERVER[REQUEST_URI]\" - server https?: \"$_SERVER[HTTPS]\"] End");

		switch ("$option") {
			// http://10.10.10.2:8888/inThisFolder/
			case 'full_url':
				return urldecode($uri_arr['scheme'].$server_name.$server_port.$request_uri);
				return 'bla';
				break;

			// http://10.10.10.2:8888/
			case 'just_url_start':
				return urldecode($uri_arr['scheme']."${server_name}${server_port}");
				break;

			// /inThisFolder/
			case 'just_uri':
				return urldecode("${request_uri}");
				break;

			// /www/inThisFolder/
			case 'full_uri_path':
				return urldecode($_SERVER["DOCUMENT_ROOT"]."${request_uri}");
				break;

			default:
				return 0;
				break;
		}
	}

	function get_file_ext($filename)
	{
		$path_info = pathinfo($filename);
		return $path_info['extension'];
	}
	?>
<div id="buffer"></div>
</body>
</html>
