<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 * Just a debug class that adds readability to dumps with indentations, color coding and type/parameters.
 * 
 * Usage:
 * debug::dump($my_var); or debug::_dump($my_var); for silent version.
 * $my_var can be anything.
 * 
 * Known issues:
 *  - Infinite recursions are not handled.
 *  - _dump has (loads of) indentation issues
 */
abstract class d
{
	private static $colors = array("green", "red", "blue", "orange", "purple");
	private static $level = 1;
	private static $silent = false;

	public static function _dump($value)
	{
		self::$silent = true;
		echo "\n<!--\n";
		$args = func_get_args();
		if(count($args) > 1)
		{
			foreach($args as $arg)
			{
				self::dodump($arg, true, false, true);
			}
		}
		else
		{
			self::dodump($args, true, false, true);
		}
		echo "\n-->\n";
	}

	public static function dump()
	{
		$args = func_get_args();
		if(count($args) > 1)
		{
			foreach($args as $arg)
			{
				self::dodump($arg);
			}
		}
		else
		{
			self::dodump($args);
		}
	}

	private static function dodump($value, $start = true, $key = false, $silent = false)
	{
		if($start)
		{
			echo self::open_ul();
		}

		if(is_array($value))
		{
			echo self::open_li();
				echo self::get_key_headline($key);
				echo ' ';
				echo self::get_item_headline($value);
				echo self::open_ul();
					$color = self::get_random_color();
					foreach($value as $key => $val)
					{
						self::dodump($val, false, self::output($key, '<span style="color:' . $color . ';">', '</span>', false));
					}
				echo self::close_ul();
			echo self::close_li();
		}
		elseif(is_object($value))
		{
			echo self::open_li();
				echo self::get_key_headline($key);
				echo self::get_item_headline($value);
				echo self::open_ul();
					$color = self::get_random_color();
					foreach((array)$value as $key => $val)
					{
						self::dodump($val, false, self::output($key, '<span style="color:' . $color . ';">', '</span>'));
					}
					if(get_class($value) != 'stdClass')
					{
						$reflect = new ReflectionClass($value);
						$methods = $reflect -> getMethods();
						echo self::open_li() . self::output('Show class info (' . count($methods) . ' methods)', '<a href="#" onclick="var kid=this.parentNode.childNodes[1];if(kid.style.display==\'block\'){kid.style.display=\'none\';}else{kid.style.display=\'block\';};return false;">', '</a>', false);
							echo self::open_ul('<ul class="methods" style="display:none;">');
								echo self::open_li() . self::output('Methods', '<strong>', '</strong>', false);
									echo self::open_ul();
									foreach($methods as $m)
									{
										echo self::open_li();
											if($m -> isPrivate())
											{
												echo self::output('private', '<span style="color:red;">', '</span> ');
											}
											if($m -> isProtected())
											{
												echo self::output('protected', '<span style="color:blue;">', '</span> ');
											}
											if($m -> isPublic())
											{
												echo self::output('public', '<span style="color:green;">', '</span> ');
											}
											if($m -> isStatic())
											{
												echo self::output('static', '<em>', '</em> ');
											}
											echo $m -> name . '(';
											$params = $m -> getParameters();
											$numParams = count($params);
											$i = 1;
											foreach($params as $param)
											{
												echo '<em>' . $param -> getName() . '</em>';
												if($i < $numParams)
												{
													echo ', ';
												}
												$i++;
											}
											echo ')';
										echo self::close_li();
									}
									echo self::close_ul();
								echo self::close_li();

								echo self::open_li() . self::output('Defined in', '<strong>', '</strong>');
									echo self::open_ul();
										echo self::open_li() . $reflect -> getFileName() . ' at line ' . $reflect -> getStartLine() . '</li>'; 
									echo self::close_ul();
								echo self::close_li();
						echo self::close_ul();
					echo self::close_li();
					}
			echo self::close_ul();
			echo self::close_li();
		}
		else
		{
			echo self::open_li();
			echo self::get_key_headline($key);
			echo self::get_item_headline($value);
			if(is_bool($value))
			{
				if($value)
				{
					echo 'true';
				}
				else
				{
					echo 'false';
				}
			}
			else
			{
				echo $value;
			}
			echo self::close_li();
		}

		if($start)
		{
			echo self::close_ul();
		}
	}

	private static function get_random_color()
	{
		return self::$colors[rand(0,count(self::$colors)-1)];;
	}

	private static function open_ul($tag = false)
	{
		self::$level++;
		if(self::$silent)
		{
			return "\n" . str_repeat("\t", self::$level) . "{\n";
		}
		if($tag)
		{
			return $tag;
		}
		return '<ul>';
	}

	private static function close_ul()
	{
		self::$level--;
		if(self::$silent)
		{
			return "\n" . str_repeat("\t", self::$level) . "}\n";
		}
		return '</ul>';
	}

	private static function open_li()
	{
		if(self::$silent)
		{
			return str_repeat("\t", self::$level);
		}
		return '<li>';
	}

	private static function close_li()
	{
		if(self::$silent)
		{
			return "\n";
		}
		return '</li>';
	}

	private static function output($value, $opentag = '', $closetag = '', $indent = true)
	{
		if(self::$silent)
		{
			return (($indent) ? str_repeat("\t", self::$level) . $value : $value);
		}
		return $opentag . $value . $closetag;
	}

	private static function get_key_headline($key, $hidden = false)
	{
		if($key !== false)
		{
			return self::output(str_replace(array('*','\0'), '', trim($key)), '<strong>', '</strong> => ');
		}
		return '';
	}

	private static function get_item_headline($item)
	{
		if(is_array($item))
		{
			return self::output('Array', '<em>', '</em>: ', false) . ' (' . sizeof($item) . '): ';
		}
		elseif(is_object($item))
		{
			return self::output('Object', '<em>', '</em>: ', false) . ' (' . get_class($item) . '): ';
		}
		else
		{
			$type = gettype($item);
			return self::output('(' . ucfirst($type) . '): ', '<em>', '</em>', false);
		}
	}

}