<?php
class AutoDoc{
  private static $signatureRe = '/^\s*\/\/\s*>\s*(.*)/';
  private static $commentLine = '/^\s*\/\/\\s*(.*)/';
  private static $startExample ='/^(\s*)\/\*\s*>/';
  private static $endExample ='/^\s*\*\//';
  private static $getHeader ='/^\s*([a-zA-Z0-9$._]*)/';

  private $lines, $idx, $docs;
  public function __construct(){
    $this->docs = [];
  }

  public function out($title, $filename){
    ksort($this->docs);
    $file = fopen($filename, 'w');
    fwrite($file, "## $title\n\n");
    foreach($this->docs as $key=>$val){
      fwrite($file, "* [$key](#$key)\n");
    }
    foreach($this->docs as $key=>$val){
      fwrite($file, "### <a name='$key'></a>$key\n$val\n\n");
    }
  }

  public function read($filename){
    $this->lines = explode("\n", file_get_contents($filename));
    $this->len = count($this->lines);
    $this->idx = 0;
    while($this->idx < $this->len){
      $line = $this->lines[$this->idx];
      if (preg_match(AutoDoc::$signatureRe, $line, $matches)){
        $this->extractComment($matches);
      }
      $this->idx += 1;
    }
  }

  private function extractComment($matches){
    $codeBlock = true;
    $header = $matches[1];
    $out = ['```javascript', $header];
    preg_match(AutoDoc::$getHeader, $header, $matches);
    $header = $matches[1];
    $this->idx += 1;
    while($this->idx < $this->len){
      $line = $this->lines[$this->idx];
      if (preg_match(AutoDoc::$signatureRe, $line, $matches)){
        if (!$codeBlock) {
          $out[] = '```javascript';
          $codeBlock = true;
        }
        $out[] = $matches[1];
      } else {
        if ($codeBlock){
          $out[] = '```';
          $codeBlock = false;
        }
        if (preg_match(AutoDoc::$commentLine, $line, $matches)){
          $out[] = trim($matches[1]);
        } else if (preg_match(AutoDoc::$startExample, $line, $matches)){
          if ($codeBlock){
            $out[] = '```';
            $codeBlock = false;
          }
          $leadingWSre = '/^' . $matches[1] . '/';
          $out[] = '```javascript';
          $this->idx +=1;
          while($this->idx < $this->len){
            $line = $this->lines[$this->idx];
            if(preg_match(AutoDoc::$endExample, $line, $matches)){
              break;
            }
            $out[] = preg_replace($leadingWSre, '', $line);
            $this->idx += 1;
          }
          $out[] = '```';
        } else {
          break;
        }
      }
      $this->idx += 1;
    }
    if ($codeBlock){
      $out[] = '```';
      $codeBlock = false;
    }
    $this->docs[$header] = implode("\n", $out);
  }
}
?>
