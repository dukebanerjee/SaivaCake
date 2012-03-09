<?php
class Content extends AppModel {
  public $name = 'Content';
  
  public $belongsTo = array(
    'Author' => array(
      'className' => 'User',
      'foreignKey' => 'author_id',
      'fields' => array('username') 
    )
  );

  public static function content_types() {
    return array('Content', 'Event');
  }

  public function replace_tokens($html) {
    $original = $this->data[$this->alias]['content'];

    $replacement = '';
    $last_offset = 0;    
    $count = preg_match_all('/\$\{\s*([^}:]+)\s*:\s*([^}:]*)\s*\}/', $original, $matches, PREG_OFFSET_CAPTURE);
    if($count) {
      for($i = 0; $i < $count; $i++) {
        $token = $matches[0][$i][0];
        $token_offset = $matches[0][$i][1];
        $token_type = $matches[1][$i][0];
        $token_value = $matches[2][$i][0];

        $replacement .= substr($original, $last_offset, $token_offset - $last_offset);
        $token_replacement = '';
        if($token_type == 'img') {
            $img = $html->image($token_value);
            if(preg_match('/src="([^"]*)"/', $img, $img_match)) {
                $token_replacement = $img_match[1];
            }
        }
	else if($token_type == 'link') {
	    if(preg_match_all('/\s*([^:\s]*)\s*(?::\s*([^:\s]*))?\s*(?::\s*([^:\s]*))?/', $token_value, $link_matches)) {
                $token_replacement = $html->url(array(
		    'controller' => $link_matches[1][0],
		    'action' => $link_matches[2][0],
		    $link_matches[3][0]
		));
	    }
	}
        $replacement .= $token_replacement;
        $last_offset = $token_offset + strlen($token);
      }
    }
    $replacement .= substr($original, $last_offset);

    $this->data[$this->alias]['content'] = $replacement;
  }
}
?>
