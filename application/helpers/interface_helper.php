<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	// $data == array(type (a or span), class, id, href)
	// @TODO Document this because it is pretty funky

/**
 *
 * @param array $data        	
 * @return string boolean array
 *         required:
 *         "text" key for the button text
 *         optional:
 *         "item" is not used here but is used by the create_button_bar script.
 *         this should be improved in a later version so it just focuses on
 *         either the class or id
 *         "type" defaults to "a" but can be "div" "span" or other tag if the
 *         type=>"pass-through" then it just returns the "text" as-is without
 *         any further processing
 *         "href" defaults to "#" is only used if "type" is "a" (default)
 *         "class" defaults to "button" but can be replaced by any other classes
 *         as defined in the css or javascript
 *         "id" is completely optional
 *         "enclosure" is an option array with type class and id keys. This is
 *         used if the particular button needs an added container (for AJAX
 *         manipulation)
 *        
 *         EXAMPLES
 *         A button that provides a standard url (type and class are defaults
 *         "a" and "button");
 *         $data = array( "text" => "View Record", "href" =>
 *         "/index.php/record/view/2352");
 *         returns: <a href="/index.php/record/view/2352" class="button">View
 *         Record</a>
 *        
 *         A button that triggers a jquery script by class with an id that is
 *         parsed by the jQuery to parse for a relevant database table key:
 *         $data = array( "text" => "Edit Record", "type" => "span", "class" =>
 *         "button edit-record" "id" => "er_2532" );
 *         returns <span class="button edit-record" id="er_2532">Edit
 *         Record</span>
 *        
 *         A Button that needs a surrounding span for jQuery mainpulation:
 *         $data = array( "text" => "Edit Record", "type" => "span", "class" =>
 *         "button edit-record" "id" => "er_2532",
 *         "enclosure" => array("type" => "span", "id" => "edit-record-span" )
 *         );
 *         returns:<span id="edit-record-span"><span class="button edit-record"
 *         id="er_2532">Edit Record</span></span>
 *        
 */
function create_button($data)
{
	if (array_key_exists ( "text", $data )) {
		$type = "a";
		$href = "";
		$title = "";
		$target = "";
		$text = $data ["text"];
		if (array_key_exists ( "type", $data )) {
			if (isset ( $data ["type"] )) {
				$type = $data ["type"];
			}
		}
		if (array_key_exists ( "href", $data )) {
			$href = "href='" . $data ["href"] . "'";
		} else {
			$href = "href='#'";
		}
		
		if (array_key_exists ( "target", $data )) {
			$target = "target='" . $data ["target"] . "'";
		}
		
		if (array_key_exists ( "title", $data )) {
			$title = "title ='" . $data ["title"] . "'";
		}
		
		if ($type != "pass-through") {
			
			if (array_key_exists ( "class", $data )) {
				if (! is_array ( $data ["class"] )) {
					$data ["class"] = array (
							$data ["class"] 
					);
				}
			} else {
				$data ["class"] = array (
						"button" 
				);
			}
			if (array_key_exists ( "position", $data )) {
				$data ["class"] [] = $data ["position"];
			}
			if (array_key_exists ( "selection", $data ) && preg_match ( "/" . str_replace ( "/", "\/", $data ["selection"] ) . "/", $_SERVER ['REQUEST_URI'] )) {
				$data ["class"] [] = "active";
			}
			$class = sprintf ( "class='%s'", implode ( " ", $data ["class"] ) );
			
			$id = "";
			if (array_key_exists ( "id", $data )) {
				$id = "id='" . $data ["id"] . "'";
			}
			
			$button = "<$type $href $id $class $target $title>$text</$type>";
			
			if (array_key_exists ( "enclosure", $data )) {
				if (array_key_exists ( "type", $data ["enclosure"] )) {
					$enc_type = $data ["enclosure"] ["type"];
					$enc_class = "";
					$enc_id = "";
					if (array_key_exists ( "class", $data ["enclosure"] )) {
						$enc_class = "class='" . $data ["enclosure"] ["class"] . "'";
					}
					if (array_key_exists ( "id", $data ["enclosure"] )) {
						$enc_id = "id='" . $data ["enclosure"] ["id"] . "'";
					}
					$button = "<$enc_type $enc_class $enc_id>$button</$enc_type>";
				}
			}
		} else {
			return $data ["text"];
		}
		return $button;
	} else {
		return FALSE;
	}
}

/**
 *
 * @param
 *        	compound array $buttons
 * @param array $options        	
 * @return string
 */
function create_button_bar($buttons, $options = NULL)
{
	$id = "";
	$selection = "";
	$class = "mini";
	if ($options) {
		if (array_key_exists ( "id", $options )) {
			$id = sprintf ( "id='%s'", $options ["id"] );
		}
		
		if (array_key_exists ( "selection", $options )) {
			$selection = $options ["selection"];
		}
		
		if (array_key_exists ( "class", $options )) {
			$class = $options ["class"];
		}
	}
	$button_list = array ();
	
	// the "selection" option indicates the page in the interface. Currently as
	// indicated by the uri->segment(1)
	for($i = 0; $i < count ( $buttons ); $i ++) {
		$button = $buttons [$i];
		$display = TRUE;
		if (array_key_exists ( "userID", $button )) {
			if ($button ['userID'] == USER_ID) {
				$display = TRUE;
			} else {
				$display = FALSE;
			}
		}
		if (array_key_exists ( "dbRole", $button )) {
			if($button["dbRole"] == "not-superuser" && USER_ID != ROOT_USER){
				$display = TRUE;
			}elseif($button ['dbRole'] == DB_ROLE){
				$display = TRUE;
			}else {
				$display = FALSE;
			}
		}
		if ($display) {
			$position = "middle";
			if ($i == 0) {
				$position = "first";
			}
			if ($i == count ( $buttons ) - 1) {
				$position = "last";
			}
			$button_list [] = sprintf ( "<li class='%s'>%s</li>", $position, create_button ( $buttons [$i] ) );
		}
	}
	
	$contents = implode ( "\r", $button_list );
	$template = "<ul class='button-list'>$contents</ul>";
	$output = "<div class='button-box $class'  $id>$template</div>";
	return $output;
}

