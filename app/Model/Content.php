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
    return array('Article', 'Content', 'Document', 'Event', 'Form', 'News', 'ViewList');
  }

  public function replace_tokens($html) {
    $original = $this->data[$this->alias]['content'];

    // Loop through token matches on $original, building up value with all token replacements in $replacement
    $replacement = '';
    $last_offset = 0;
    // token format is: ${token_type:token_value}
    $count = preg_match_all('/\$\{\s*([^}:]+)\s*:\s*([^}:]*)\s*\}/', $original, $matches, PREG_OFFSET_CAPTURE);
    if($count) {
      for($i = 0; $i < $count; $i++) {
        $token = $matches[0][$i][0];
        $token_offset = $matches[0][$i][1];
        $token_type = $matches[1][$i][0];
        $token_value = $matches[2][$i][0];

        // Append to replacement the interval between end of last token up to beginning of this token
        $replacement .= substr($original, $last_offset, $token_offset - $last_offset);
        $token_replacement = '';
        if($token_type == 'img') {
            // Generate the <img> tag and use the src attribute as the token replacement
            $img = $html->image($token_value);
            if(preg_match('/src="([^"]*)"/', $img, $img_match)) {
                $token_replacement = $img_match[1];
            }
        }
        else if($token_type == 'link') {
          // Link token value format is: ${controller:action:parameter}, where action and parameter are optional
          if(preg_match_all('/\s*([^:\s]*)\s*(?::\s*([^:\s]*))?\s*(?::\s*([^:\s]*))?/', $token_value, $link_matches)) {
            $token_replacement = $html->url(array(
              'controller' => $link_matches[1][0],
              'action' => $link_matches[2][0],
              $link_matches[3][0]
            ));
          }
        }
        // Append token replacement and capture end of current token to define start interval for next interation
        $replacement .= $token_replacement;
        $last_offset = $token_offset + strlen($token);
      }
    }
    // Append remaining text without tokens from the end of the last token (if any) to the end of the original string
    $replacement .= substr($original, $last_offset);

    $this->data[$this->alias]['content'] = $replacement;
  }
}
?>
