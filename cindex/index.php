<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Listing <?php $_SERVER["REQUEST_URI"]?></title>
	<link rel="stylesheet" href="style/main.css" type="text/css" />
</head>
<body id="index" class="home">
<header>
	<h1>Directory listing</h1>
</header>
	<?php
	ini_set('display_errors', 0);  
	// define("debugInfo", TRUE);
	define("debugInfo", FALSE);
	
	
	
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

// doc_h = header (php file info)
// doc_d = define globle constent
// doc_f = create documented function
// doc_s = create documented function (without brakets)
// <<< = used to insert HTML code into a variable (good for testing)
// if? = short hand if
// ctr+shift+commnad+W = include selected into try catch

// echod("Hello");

// phpinfo();


// echo "URL: ".URL_FORMAT("full_url");
// $root_url = URL_FORMAT("full_url");
// echo "<br />\n";

// echo "URI: ".REQUEST_URI();
// $search_root = $_SERVER["DOCUMENT_ROOT"].REQUEST_URI();
// echod("root_uri: $root_uri");
// echo "<br />\n";
// echo "<br />\n";


// echo URL_FORMAT("full_uri_path");
$files = scandir(URL_FORMAT("full_uri_path"));
// $files = scandir("/www/aindex/");
// print_r($files);
// echo "<br />\n";

// echo "<p class=\"location\">Location: <a href=\"".dirname(URL_FORMAT("just_uri"))."\">".urldecode(URL_FORMAT("full_url"))."</a></p>\n";
echo "<pre class=\"location\">Location: <a href=\"".dirname(URL_FORMAT("just_uri"))."\">".URL_FORMAT("full_url")."</a></pre>\n";
// echo "<br />\r\n";

// List of extensions to list
// $extensions = array("txt","php","htm");
$extensions = array("");
$ext = array("");

// Loop through each filename of scandir
foreach ($files as $file_name) {	
	// Construct a full path "/www/folder/file.php"
	$file_path_sys = URL_FORMAT("full_uri_path")."$file_name";
	// Full site path "http://localhost:8888/folder/file.php"
	$full_url_start = URL_FORMAT("full_url")."$file_name";
	// Full site path "/folder/file.php"
	$url_start = URL_FORMAT("just_uri")."$file_name";

	// Is it a file? If so, get the extension using some function you created
	if(is_file("$file_path_sys") OR is_dir("$file_path_sys")) {
		// Only get file extention if it's a file
		if (is_file("$file_path_sys") ) {
			$ext = get_file_ext("$file_name");
		}
		
		// Is the file extension not included in the array of forbidden extensions?
		// Since it is not included, execute code to list the file or whatever
		if (!in_array($ext,$extensions)) {

			// $linkURL = htmlentities("$URLstart$filename");
			// echo "1111: $linkURL";
			if ("$file_name" != ".") {
				if ("$file_name" == "..") {
					// if ("$root_dir_URI" != "/") {
					// 	# Don't display the back link if it's the root dir
					// 	// echo "<a href=\"$linkURL\">Parent Directory/</a></pre><br \><br \>\n\n";
					// 	// Parent Directory
					// }
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
					$url_format = "		<pre class=\"filename\"><a href=\"".trim(URL_ENCODE("$url_start$is_dir_prefix"))."\">".rawurldecode("$full_url_start$is_dir_prefix")."</a></pre>\n";
					// $url_format = "		<pre class=\"filename\"><a href=\"".trim(rawurlencode("test  %20"))."\">".rawurldecode("$full_url_start$is_dir_prefix")."</a></pre>\n";
	
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

// <table>
// 	<caption>cap</caption>
// 	<thead><tr><th>header</th></tr></thead>
// 	<tbody>
// 		<tr>
// 			<td>data1</td>
// 			<td>data2</td>
// 		</tr>
// 	</tbody>
// </table>
// echo "<table border=\"0\" cellspacing=\"0\">";
	if (trim($DIR_LIST)) {
		echo '<table>';
		echo '<caption class="title">Directories:</caption>';
		echo '<tbody><tr>';
		echo '	<td class="tsize">' . $DIR_LIST_SIZE . '	</td>';
		echo '	<td class="tfile">' . $DIR_LIST . '	</td>';
		echo '</tr></tbody>';
		echo '</table>';
	}

	if (trim($FILE_LIST_NEW)) {
		echo '<table>';
		echo '<caption class="title">Files NEW (~12h):</caption>';
		echo '<tr>';
		echo '	<td class="tsize">' . $FILE_LIST_NEW_SIZE. '	</td>';
		echo '	<td class="tfile">' . $FILE_LIST_NEW . '	</td>';
		echo '</tr>';
		echo '</table>';	
	}

	if (trim($FILE_LIST_HD)) {
		echo '<table>';
		echo '<caption class="title">Files 720p/1080p:</caption>';
		echo '<tr>';
		echo '	<td class="tsize">' . $FILE_LIST_HD_SIZE. '	</td>';
		echo '	<td class="tfile">' . $FILE_LIST_HD. '	</td>';
		echo '</tr>';
		echo '</table>';
	}

	if (trim($FILE_LIST)) {
		echo '<table>';
		echo '<caption class="title">Files:</caption>';
		echo '<tr>';
		echo '	<td class="tsize">' . $FILE_LIST_SIZE. '	</td>';
		echo '	<td class="tfile">' . $FILE_LIST. '	</td>';
		echo '</tr>';
		echo '</table>';	
	}

	if (trim("$OTHER_LIST")) {
		echo '<table>';
		echo '<caption class="title">Other?</caption>';
		echo '<tr>';
		echo '	<td class="tsize">' . $OTHER_LIST_SIZE. '	</td>';
		echo '	<td class="tfile">' . $OTHER_LIST. '	</td>';
		echo '</tr>';
		echo '</table>';
	}



// Find and replace spaces with %20
function URL_ENCODE($url)
{
	// $encoded_url = str_replace(" ", "%20", "$url");
	// return "$encoded_url";
	return myUrlEncode("$url");
	// return rawurlencode($url);
}

function myUrlEncode($string) {
    // $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    // $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    // $replacements = array('%23', '%25', '%5E', '%20', '%7C', '%7B', '%7D', '%22', '%27', '%3C', '%3E' );
    // $entities = 	array("#",   "%",   "^",   " ",   "|",   "{",   "}",   '"',   "'",   "<",   ">" );
    $replacements = array('%25', '%23',  '%20', '%22', '%27', '%5C', '%3F');
    $entities = 	array('%',   '#',    ' ',   '"',   "'",   "\\",  '?');

    // return str_replace($entities, $replacements, urlencode($string));
    return str_replace($entities, $replacements, $string);
}

// Gets the time diff in hours from 2 unix strings
// arg 1 INT: 
// arg 2 INT: 
// timeDiffHours("$fileTime",time())
function timeDiffHours($firstTime,$lastTime)
{
	// orginal time strings
	// $firstTime="$fileTime";
	// The current time (unix timestamp)
	// $lastTime = time();
	// $lastTime = "$lastTime";

	// perform subtraction to get the difference (in seconds) between times
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








	// DOMAIN_NAME("root-domain");
	// DOMAIN_NAME("full-domain");
	// 
	// /**
	//  * undocumented function
	//  *
	//  * @return void
	//  * @author A
	//  **/
	// function DOMAIN_NAME($option)
	// {
	// 	
	// 	
	// 	// if ($option == "root-domain") {
	// 	// 	return "$url_domain_name";
	// 	// }
	// 	// elseif ($option == "full-domain") {
	// 	// 	return "$URLstart";
	// 	// }
	// 	// else {
	// 	// 	return "fail<br />\n";
	// 	// }
	// }
	
	
	/**
	 * Gets the URI even if loading the file E.G. 10.10.10.2/folder/file.php
	 *
	 * @return 10.10.10.2'/this/part/file.php' || 10.10.10.2'/this/part/'
	 * @author A
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
		// $root_dir_URI = dirname($_SERVER["REQUEST_URI"]);
		// $root_dir = trim(urldecode("$root_dir_URI"));
		
		echod("1: $url_dirname <br />\n");
		echod("2: $root_dir_URI <br />\n");
		echod("3: $root_dir <br />\n");
		
		// return trim(urldecode("$url_dirname"));
		// return trim("$url_dirname");
		return "$url_dirname";
		
	}
	
	/**
	 * Constructs the URL path
	 *
	 * @return Full URL E.G. https://google.com/files/link/
	 * @author A
	 **/
	function URL_FORMAT($option)
	{
		// $_SERVER["HTTPS"] = null;
		// // If not https then it's http
		// if ($_SERVER["HTTPS"] != "on") {
		// 	$http_url_start = "http://";
		// }
		// else {
		// 	$http_url_start = "https://";
		// }
			// To prevent it showing localhost when a local address is used but it's not local... so use the IP address
			if ($_SERVER["SERVER_NAME"] == "localhost") {
				// 10.10.1.15
				$server_name = $_SERVER["SERVER_ADDR"];
			} else {
				// 10.10.10.2 || localhost || google.com
				$server_name = $_SERVER["SERVER_NAME"];
			}

			// 10.10.10.2'/this/part/' (regardless of the way you execute the script, E.G. "10.10.10.2/this/part/" or "10.10.10.2/this/part/index.php")
			$request_uri = REQUEST_URI();
			// 10.10.10.2'/this/part/file.php' || 10.10.10.2'/this/part/'
			// $request_uri = $_SERVER["REQUEST_URI"];

		// // Server port number (nothing if standart "80")
		// if ($_SERVER["SERVER_PORT"] == "80") {
		// 	$server_port = null;
		// }
		// elseif ($_SERVER["SERVER_PORT"] == "443") {
		// 	$server_port = null;
		// }
		// else {
		// 	$server_port = ":".$_SERVER["SERVER_PORT"];
		// }


		// http://10.10.1.15/'%20co%20py~!@/#$%^&*()_+=- []-|}{":;'.,<>
		// 
		// http://10.10.1.15/%27%20co%20%20py~!@%23$%25%5E&*()_+=-%20[]-%7C%7D%7B%22:;%27.,%3C%3E/
		// http://10.10.1.15/' co py~!@#$%^&*()_+=- []-|}{":;'.,<>/
		// 
		// http://10.10.1.15/'%20co%20%20py~!@/#$%^&*()_+=-%20[]-|}{


// http://10.10.1.15/testing%20&%20more%20more%20%3E%20less%20%22%20en%20%27/

// http://10.10.1.15/test  %20
// http://10.10.1.15/test%20%20%2520/

		// Get URL scheme and port used (if port needed)
		$uri_arr = get_uri_scheme_name();
		// $uri_arr['scheme']
		// $uri_arr['port']

		// Debugging...
		echod("Start [server name: \"$_SERVER[SERVER_NAME]\" - server port: \"$_SERVER[SERVER_PORT]\" - server request uri: \"$_SERVER[REQUEST_URI]\" - server https?: \"$_SERVER[HTTPS]\"] End");		
				
		switch ("$option") {
			// http://10.10.10.2:8888/inThisFolder/
			case 'full_url':
				// return htmlspecialchars_decode(rawurldecode("${http_url_start}${server_name}${server_port}${request_uri}"));
				return urldecode($uri_arr['scheme'].$server_name.$uri_arr['port'].$request_uri);
				// return urldecode("${http_url_start}${server_name}${server_port}${request_uri}");
				break;
			
				// http://10.10.10.2:8888/
				case 'just_url_start':
					return urldecode($uri_arr['scheme'].$server_name.$uri_arr['port']);
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
	
	/**
	 * Get URI scheme used (HTTP/HTTPS) 
	 *
	 * @return https or http
	 * @author A
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
	
	
	// echo REQUEST_URI();
	// echo $_SERVER["REQUEST_URI"];
	function get_file_ext($filename)
	{
		$path_info = pathinfo($filename);
		return $path_info['extension'];
	}
	?>
<div id="buffer"></div>
</body>
</html>