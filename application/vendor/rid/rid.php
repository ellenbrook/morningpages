<?php defined('SYSPATH') or die('No direct script access.'); 

/**
 * Regressive Imagery Dictionary
 * Source: https://github.com/middric/mood_box
 */
class RID {

  private $tree = array();
  private $categories = array();

  public function load_dictionary($path) {

    try {
      $file = fopen($path, 'r');
    } catch(Exception $e) {
      print "Error loading file.\n";
      var_dump($e);
      exit;
    }

    $primary_category = $secondary_category = $tertiary_category = $pattern = NULL;

    while(!feof($file)) {
      $buffer = fgets($file);
      $tabs = preg_match_all('/\t/', $buffer, $matches);
      $pattern = NULL;

      // Dictionary tree structure defined by tab characters
      switch($tabs) {
        case 0:
          $primary_category = strtolower(trim($buffer));
          $secondary_category = NULL;
          $tertiary_category = NULL;

          // Make sure this is a category
          if($this->ensure_category($primary_category)) {
            $this->tree[$primary_category] = array();
          }
          else {
            $primary_category = NULL;
            $pattern = $this->distill_pattern($buffer);
          }
          break;
        case 1:
          $secondary_category = strtolower(trim($buffer));
          $tertiary_category = NULL;

          if($this->ensure_category($secondary_category)) {
            $this->tree[$primary_category][$secondary_category] = array();
          }
          else {
            $secondary_category = NULL;
            $pattern = $this->distill_pattern($buffer);
          }
          break;
        case 2:
          $tertiary_category = strtolower(trim($buffer));

          if($this->ensure_category($tertiary_category)) {
            $this->tree[$primary_category][$secondary_category][$tertiary_category] = array();
          }
          else {
            $tertiary_category = NULL;
            $pattern = $this->distill_pattern($buffer);
          }
          break;
        case 3:
            $pattern = $this->distill_pattern($buffer);
          break;
      }

      if($pattern) {
        if($tertiary_category) {
          $this->tree[$primary_category][$secondary_category][$tertiary_category][] = $pattern;
          $this->categories[] = $tertiary_category;
        }
        elseif($secondary_category) {
          $this->tree[$primary_category][$secondary_category][] = $pattern;
          $this->categories[] = $secondary_category;
        }
        elseif($primary_category) {
          $this->tree[$primary_category][] = $pattern;
          $this->categories[] = $primary_category;
        }
      }
    }
    $this->categories = array_unique($this->categories);
    fclose($file);
  }

  /**
   * Analyze provided text and return RIDResults
   *
   * @param string $text
   * @return RIDResults
   */
  public function analyze($text) {
    $results = new RIDResults();

    foreach($this->categories as $category) {
      $results->category_count[$category] = 0;
      $results->category_words[$category] = array();
      $results->category_percentage[$category] = 0;
    }
    
    // Tokenize input text and iterate over it
    $token = strtok($text, ' ');
    while($token !== FALSE) {
      // Get rid of anything that isnt alphabetical
      $token = preg_replace('/[^a-zA-Z]*/', '', $token);
      // What category does this word belong to (if any)
      $category = $this->get_category($token);
      
      if($category) {
        if(!isset($results->category_count[$category])) {
          $results->category_count[$category] = 0;
          $results->category_words[$category] = array();
        }
        // Increase category count and store the word for future reference
        $results->category_count[$category]++;
        $results->category_words[$category][] = $token;
        $results->word_count++;
      }

      $token = strtok(' ');
    }

    // Calculate the category percentages
    foreach($results->category_count as $key => $value) {
      if($results->word_count) {
        $results->category_percentage[$key] = ($value / $results->word_count) * 100.0;
      } else {
        $results->category_percentage[$key] = 0;
      }
    }
    
    return $results;
  }

  /**
   * Return array of categories
   *
   * @return array
   */
  public function get_categories() {
    return $this->categories;
  }

  /**
   * Return the category that a word belongs to. If no category return false
   *
   * @param string $token
   * @return mixed
   */
  private function get_category($token) {
    return $this->get_parent($this->tree, $token);
  }

  /**
   * Recursivley get the parent category in the dictionary
   *
   * @param array $array Array to iterate through
   * @param string $needle String to search for
   * @param string $parent
   * @return mixed
   */
  private function get_parent($array, $needle, $parent = NULL) {
    foreach ($array as $key => $value) {
      // if array item is a string replace * wildcard with a regex friendly .* syntax
      if(is_string($value)) {
        $regex = str_replace('\*', '.*', preg_quote($value));
      }
      // if array item is an array itself we still have work to do
      if (is_array($value)) {
          $pass = $parent;
          if (is_string($key)) {
              $pass = $key;
          }
          $found = $this->get_parent($value, $needle, $pass);
          if ($found !== FALSE) {
              return $found;
          }
      // if the array item matches our needle return the parent
      } else if (preg_match('#^' . $regex . '#', $needle)) {
          return $parent;
      }
    }
    // Cant find it, return false
    return FALSE;
  }

  /**
   * Convert dictionary line to just the relevant text
   *
   * @param string $buffer
   * @return string
   */
  private function distill_pattern($buffer) {
    $pattern = explode(' ', trim(strtolower($buffer)));
    return array_shift($pattern);
  }

  /**
   * Ensure string represents a category
   *
   * @param string $cat
   * @return boolean
   */
  private function ensure_category($cat) {
    return !preg_match('/\([0-9]*\)/',$cat);
  }
}

/**
 * Regressive Imagery Dictionary result class
 */
class RIDResults {
  public $category_count = array();
  public $category_words = array();
  public $category_percentage = array();
  public $word_count = 0;
}

?>