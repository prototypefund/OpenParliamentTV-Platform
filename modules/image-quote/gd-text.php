<?php
// SOURCE: https://github.com/stil/gd-text
class Point{private $x;private $y;public function __construct($x,$y){$this->x=$x;$this->y=$y;}public function getX(){return $this->x;}public function getY(){return $this->y;}}class Rectangle extends Point{private $width;private $height;public function __construct($x,$y,$width,$height){parent::__construct($x,$y);$this->width=$width;$this->height=$height;}public function getWidth(){return $this->width;}public function getHeight(){return $this->height;}public function getLeft(){return $this->getX();}public function getTop(){return $this->getY();}public function getRight(){return $this->getX()+$this->width;}public function getBottom(){return $this->getY()+$this->height;}}class Box{protected $im;protected $strokeSize=0;protected $strokeColor;protected $fontSize=12;protected $fontColor;protected $alignX='left';protected $alignY='top';protected $textWrapping=TextWrapping::WrapWithOverflow;protected $lineHeight=1.25;protected $baseline=0.2;protected $fontFace=null;protected $debug=false;protected $textShadow=false;protected $backgroundColor=false;protected $box;public function __construct(&$image){$this->im=$image;$this->fontColor=new Color(0,0,0);$this->strokeColor=new Color(0,0,0);$this->box=new Rectangle(0,0,100,100);}public function setFontColor(Color $color){$this->fontColor=$color;}public function setFontFace($path){$this->fontFace=$path;}public function setFontSize($v){$this->fontSize=$v;}public function setStrokeColor(Color $color){$this->strokeColor=$color;}public function setStrokeSize($v){$this->strokeSize=$v;}public function setTextShadow(Color $color,$xShift,$yShift){$this->textShadow=array('color'=>$color,'offset'=>new Point($xShift,$yShift));}public function setBackgroundColor(Color $color){$this->backgroundColor=$color;}public function setLineHeight($v){$this->lineHeight=$v;}public function setBaseline($v){$this->baseline=$v;}public function setTextAlign($x='left',$y='top'){$xAllowed=array('left','right','center');$yAllowed=array('top','bottom','center');if(!in_array($x,$xAllowed)){throw new \InvalidArgumentException('Invalid horizontal alignement value was specified.');}if(!in_array($y,$yAllowed)){throw new \InvalidArgumentException('Invalid vertical alignement value was specified.');}$this->alignX=$x;$this->alignY=$y;}public function setBox($x,$y,$width,$height){$this->box=new Rectangle($x,$y,$width,$height);}public function enableDebug(){$this->debug=true;}public function setTextWrapping($textWrapping){$this->textWrapping=$textWrapping;}public function draw($text){if(!isset($this->fontFace)){throw new \InvalidArgumentException('No path to font file has been specified.');}switch($this->textWrapping){case TextWrapping::NoWrap:$lines=array($text);break;case TextWrapping::WrapWithOverflow:default:$lines=$this->wrapTextWithOverflow($text);break;}if($this->debug){$this->drawFilledRectangle($this->box,new Color(rand(180,255),rand(180,255),rand(180,255),80));}$lineHeightPx=$this->lineHeight*$this->fontSize;$textHeight=count($lines)*$lineHeightPx;switch($this->alignY){case VerticalAlignment::Center:$yAlign=($this->box->getHeight()/2)-($textHeight/2);break;case VerticalAlignment::Bottom:$yAlign=$this->box->getHeight()-$textHeight;break;case VerticalAlignment::Top:default:$yAlign=0;}$n=0;foreach($lines as $line){$box=$this->calculateBox($line);switch($this->alignX){case HorizontalAlignment::Center:$xAlign=($this->box->getWidth()-$box->getWidth())/2;break;case HorizontalAlignment::Right:$xAlign=($this->box->getWidth()-$box->getWidth());break;case HorizontalAlignment::Left:default:$xAlign=0;}$yShift=$lineHeightPx*(1-$this->baseline);$xMOD=$this->box->getX()+$xAlign;$yMOD=$this->box->getY()+$yAlign+$yShift+($n*$lineHeightPx);if($line&&$this->backgroundColor){$backgroundHeight=$this->fontSize;$this->drawFilledRectangle(new Rectangle($xMOD,$this->box->getY()+$yAlign+($n*$lineHeightPx)+($lineHeightPx-$backgroundHeight)+(1-$this->lineHeight)*13*(1/50*$this->fontSize),$box->getWidth(),$backgroundHeight),$this->backgroundColor);}if($this->debug){$this->drawFilledRectangle(new Rectangle($xMOD,$this->box->getY()+$yAlign+($n*$lineHeightPx),$box->getWidth(),$lineHeightPx),new Color(rand(1,180),rand(1,180),rand(1,180)));}if($this->textShadow!==false){$this->drawInternal(new Point($xMOD+$this->textShadow['offset']->getX(),$yMOD+$this->textShadow['offset']->getY()),$this->textShadow['color'],$line);}$this->strokeText($xMOD,$yMOD,$line);$this->drawInternal(new Point($xMOD,$yMOD),$this->fontColor,$line);$n++;}}protected function wrapTextWithOverflow($text){$lines=array();$explicitLines=preg_split('/\n|\r\n?/',$text);foreach($explicitLines as $line){$words=explode(" ",$line);$line=$words[0];for($i=1;$i<count($words);$i++){$box=$this->calculateBox($line." ".$words[$i]);if($box->getWidth()>=$this->box->getWidth()){$lines[]=$line;$line=$words[$i];}else{$line.=" ".$words[$i];}}$lines[]=$line;}return $lines;}protected function getFontSizeInPoints(){return 0.75*$this->fontSize;}protected function drawFilledRectangle(Rectangle $rect,Color $color){imagefilledrectangle($this->im,$rect->getLeft(),$rect->getTop(),$rect->getRight(),$rect->getBottom(),$color->getIndex($this->im));}protected function calculateBox($text){$bounds=imagettfbbox($this->getFontSizeInPoints(),0,$this->fontFace,$text);$xLeft=$bounds[0];$xRight=$bounds[2];$yLower=$bounds[1];$yUpper=$bounds[5];return new Rectangle($xLeft,$yUpper,$xRight-$xLeft,$yLower-$yUpper);}protected function strokeText($x,$y,$text){$size=$this->strokeSize;if($size<=0)return;for($c1=$x-$size;$c1<=$x+$size;$c1++){for($c2=$y-$size;$c2<=$y+$size;$c2++){$this->drawInternal(new Point($c1,$c2),$this->strokeColor,$text);}}}protected function drawInternal(Point $position,Color $color,$text){imagettftext($this->im,$this->getFontSizeInPoints(),0,$position->getX(),$position->getY(),$color->getIndex($this->im),$this->fontFace,$text);}}class Color{protected $red;protected $green;protected $blue;protected $alpha;public function __construct($red=0,$green=0,$blue=0,$alpha=null){$this->red=$red;$this->green=$green;$this->blue=$blue;$this->alpha=$alpha;}public static function parseString($str){$str=str_replace('#','',$str);if(strlen($str)==6){$r=hexdec(substr($str,0,2));$g=hexdec(substr($str,2,2));$b=hexdec(substr($str,4,2));}else if(strlen($str)==3){$r=hexdec(str_repeat(substr($str,0,1),2));$g=hexdec(str_repeat(substr($str,1,1),2));$b=hexdec(str_repeat(substr($str,2,1),2));}else{throw new \InvalidArgumentException('Unrecognized color.');}return new Color($r,$g,$b);}public static function fromHsl($h,$s,$l){$fromFloat=function(array $rgb){foreach($rgb as&$v){$v=(int)round($v*255);};return new Color($rgb[0],$rgb[1],$rgb[2]);};if($s==0){return $fromFloat(array($l,$l,$l));}$chroma=(1-abs(2*$l-1))*$s;$h_=$h*6;$x=$chroma*(1-abs((fmod($h_,2))-1));$m=$l-round($chroma/2,10);if($h_>=0&&$h_<1)$rgb=array(($chroma+$m),($x+$m),$m);elseif($h_>=1&&$h_<2)$rgb=array(($x+$m),($chroma+$m),$m);elseif($h_>=2&&$h_<3)$rgb=array($m,($chroma+$m),($x+$m));elseif($h_>=3&&$h_<4)$rgb=array($m,($x+$m),($chroma+$m));elseif($h_>=4&&$h_<5)$rgb=array(($x+$m),$m,($chroma+$m));elseif($h_>=5&&$h_<6)$rgb=array(($chroma+$m),$m,($x+$m));else throw new \InvalidArgumentException('Invalid hue, it should be a value between 0 and 1.');return $fromFloat($rgb);}public function getIndex($image){$index=$this->hasAlphaChannel()?imagecolorexactalpha($image,$this->red,$this->green,$this->blue,$this->alpha):imagecolorexact($image,$this->red,$this->green,$this->blue);if($index!==-1){return $index;}return $this->hasAlphaChannel()?imagecolorallocatealpha($image,$this->red,$this->green,$this->blue,$this->alpha):imagecolorallocate($image,$this->red,$this->green,$this->blue);}public function hasAlphaChannel(){return $this->alpha!==null;}public function toArray(){return array($this->red,$this->green,$this->blue);}}abstract class HorizontalAlignment{const Left='left';const Right='right';const Center='center';}abstract class TextWrapping{const NoWrap=1;const WrapWithOverflow=2;}abstract class VerticalAlignment{const Top='top';const Bottom='bottom';const Center='center';} ?>
