<?php

# configuration file CAPTCHAR

$config_captcha = array(

	/**
	* The desired width of the CAPTCHA image.
	*
	* @var int
	*/
	'image_width' => 123,
	/**
	* The desired width of the CAPTCHA image.
	*
	* @var int
	*/
	'image_height' => 40,
	
	/**
	* The image format for output.<br />
	* Valid options: SI_IMAGE_PNG, SI_IMAGE_JPG, SI_IMAGE_GIF
	*
	* @var int
	*/
	'image_type' => SI_IMAGE_PNG,
	
	/**
	* The length of the code to generate.
	*
	* @var int
	*/
	'code_length' => 5,
	
	/**
	* The character set for individual characters in the image.<br />
	* Letters are converted to uppercase.<br />
	* The font must support the letters or there may be problematic substitutions.
	*
	* @var string
	*/
	//  var $charset = 'ABCDEFGHJKLMNPRSTUVWXYZ23456789';
	'charset' => '0123456789',
	
	/**
	* Whether to use a GD font instead of a TTF font.<br />
	* TTF offers more support and options, but use this if your PHP doesn't support TTF.<br />
	*
	* @var boolean
	*/
	'use_gd_font' => false,
	
	/**
	* The approximate size of the font in pixels.<br />
	* This does not control the size of the font because that is determined by the GD font itself.<br />
	* This is used to aid the calculations of positioning used by this class.<br />
	*
	* @var int
	*/
	'gd_font_size' => 20,
	
	/**
	* The font size.<br />
	* Depending on your version of GD, this should be specified as the pixel size (GD1) or point size (GD2)<br />
	*
	* @var int
	*/
	'font_size' => 20,
	
	/**
	* The minimum angle in degrees, with 0 degrees being left-to-right reading text.<br />
	* Higher values represent a counter-clockwise rotation.<br />
	* For example, a value of 90 would result in bottom-to-top reading text.
	*
	* @var int
	*/
	'text_angle_minimum' => -20,
	
	/**
	* The minimum angle in degrees, with 0 degrees being left-to-right reading text.<br />
	* Higher values represent a counter-clockwise rotation.<br />
	* For example, a value of 90 would result in bottom-to-top reading text.
	*
	* @var int
	*/
	'text_angle_maximum' => 20,
	
	/**
	* The X-Position on the image where letter drawing will begin.<br />
	* This value is in pixels from the left side of the image.
	*
	* @var int
	*/
	'text_x_start' => 8,
	
	/**
	* Letters can be spaced apart at random distances.<br />
	* This is the minimum distance between two letters.<br />
	* This should be <i>at least</i> as wide as a font character.<br />
	* Small values can cause letters to be drawn over eachother.<br />
	*
	* @var int
	*/
	'text_minimum_distance' => 20,
	
	/**
	* Letters can be spaced apart at random distances.<br />
	* This is the maximum distance between two letters.<br />
	* This should be <i>at least</i> as wide as a font character.<br />
	* Small values can cause letters to be drawn over eachother.<br />
	*
	* @var int
	*/
	'text_maximum_distance' => 22,
	
	/**
	* The background color for the image.<br />
	* This should be specified in HTML hex format.<br />
	* Make sure to include the preceding # sign!
	*
	* @var string
	*/
	'image_bg_color' => '#E0E0E0',
	
	/**
	* The text color to use for drawing characters.<br />
	* This value is ignored if $use_multi_text is set to true.<br />
	* Make sure this contrasts well with the background color.<br />
	* Specify the color in HTML hex format with preceding # sign
	*
	* @see Securimage::$use_multi_text
	* @var string
	*/
	'text_color' => '#8080ff',
	
	/**
	* Set to true to use multiple colors for each character.
	*
	* @see Securimage::$multi_text_color
	* @var boolean
	*/
	'use_multi_text' => false,
	
	/**
	* String of HTML hex colors to use.<br />
	* Separate each possible color with commas.<br />
	* Be sure to precede each value with the # sign.
	*
	* @var string
	*/
	'multi_text_color' => '#000000,#0000FF,#FF0000,#33FF00,#CC00FF,#FF00B3',
	
	/**
	* Set to true to make the characters appear transparent.
	*
	* @see Securimage::$text_transparency_percentage
	* @var boolean
	*/
	'use_transparent_text' => true,
	
	/**
	* The percentage of transparency, 0 to 100.<br />
	* A value of 0 is completely opaque, 100 is completely transparent (invisble)
	*
	* @see Securimage::$use_transparent_text
	* @var int
	*/
	'text_transparency_percentage' => 15,
	
	
	// Line options
	/**
	* Draw vertical and horizontal lines on the image.
	*
	* @see Securimage::$line_color
	* @see Securimage::$line_distance
	* @see Securimage::$line_thickness
	* @see Securimage::$draw_lines_over_text
	* @var boolean
	*/
	'draw_lines' => true,
	
	/**
	* The color of the lines drawn on the image.<br />
	* Use HTML hex format with preceding # sign.
	*
	* @see Securimage::$draw_lines
	* @var string
	*/
	'line_color' => '#80BFFF',
	
	/**
	* How far apart to space the lines from eachother in pixels.
	*
	* @see Securimage::$draw_lines
	* @var int
	*/
	'line_distance' => 15,
	
	/**
	* How thick to draw the lines in pixels.<br />
	* 1-3 is ideal depending on distance
	*
	* @see Securimage::$draw_lines
	* @see Securimage::$line_distance
	* @var unknown_type
	*/
	'line_thickness' => 1,
	
	/**
	* Set to true to draw angled lines on the image in addition to the horizontal and vertical lines.
	*
	* @see Securimage::$draw_lines
	* @var boolean
	*/
	'draw_angled_lines' => false,
	
	/**
	* Draw the lines over the text.<br />
	* If fales lines will be drawn before putting the text on the image.<br />
	* This can make the image hard for humans to read depending on the line thickness and distance.
	*
	* @var boolean
	*/
	'draw_lines_over_text' => false,
	
	/**
	* For added security, it is a good idea to draw arced lines over the letters to make it harder for bots to segment the letters.<br />
	* Two arced lines will be drawn over the text on each side of the image.<br />
	* This is currently expirimental and may be off in certain configurations.
	*
	* @var boolean
	*/
	'arc_linethrough' => false,
	
	/**
	* The colors or color of the arced lines.<br />
	* Use HTML hex notation with preceding # sign, and separate each value with a comma.<br />
	* This should be similar to your font color for single color images.
	*
	* @var string
	*/
	'arc_line_colors' => '#8080ff',
	
	/**
	* The text with shadow.<br />
	* This should be similar to your font.
	* @var boolean
	*/
	'shadow_text' => false
	
	/*
	*	Authencation
	*/
	//'credits'=>'timnhanh'
);

?>