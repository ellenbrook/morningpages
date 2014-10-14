<?php defined('SYSPATH') or die('No direct script access.');

Kohana::load((Kohana::find_file('../vendor/erusev/parsedown', 'Parsedown')));

class markdown extends Parsedown {
	
	private static $instance = false;
	
	public static function instance($name = null)
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function identifyLink($Excerpt)
    {
        $extent = $Excerpt['text'][0] === '!' ? 1 : 0;

        if (strpos($Excerpt['text'], ']') and preg_match('/\[((?:[^][]|(?R))*)\]/', $Excerpt['text'], $matches))
        {
            $Link = array('text' => $matches[1], 'label' => strtolower($matches[1]));

            $extent += strlen($matches[0]);

            $substring = substr($Excerpt['text'], $extent);

            if (preg_match('/^\s*\[([^][]+)\]/', $substring, $matches))
            {
                $Link['label'] = strtolower($matches[1]);

                if (isset($this->Definitions['Reference'][$Link['label']]))
                {
                    $Link += $this->Definitions['Reference'][$Link['label']];

                    $extent += strlen($matches[0]);
                }
                else
                {
                    return;
                }
            }
            elseif (isset($this->Definitions['Reference'][$Link['label']]))
            {
                $Link += $this->Definitions['Reference'][$Link['label']];

                if (preg_match('/^[ ]*\[\]/', $substring, $matches))
                {
                    $extent += strlen($matches[0]);
                }
            }
            elseif (preg_match('/^\([ ]*(.*?)(?:[ ]+[\'"](.+?)[\'"])?[ ]*\)/', $substring, $matches))
            {
                $Link['url'] = $matches[1];

                if (isset($matches[2]))
                {
                    $Link['title'] = $matches[2];
                }

                $extent += strlen($matches[0]);
            }
            else
            {
                return;
            }
        }
        else
        {
            return;
        }

        $url = str_replace(array('&', '<'), array('&amp;', '&lt;'), $Link['url']);

        if ($Excerpt['text'][0] === '!')
        {
            $Element = array(
                'name' => 'img',
                'attributes' => array(
                    'alt' => $Link['text'],
                    'class' => '',
                    'src' => $url,
                ),
            );
        }
        else
        {
            $Element = array(
                'name' => 'a',
                'handler' => 'line',
                'text' => $Link['text'],
                'attributes' => array(
                    'href' => $url,
                ),
            );
        }

        if (isset($Link['title']))
        {
            $Element['attributes']['title'] = $Link['title'];
        }

        return array(
            'extent' => $extent,
            'element' => $Element,
        );
    }
	
	public function convert($content = '')
	{
		return $this->text($content);
	}
	
}
