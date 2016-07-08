<?
/*------------------------------------------------------------------*/
/*------------------------------------------------------------------*/
/*------------------------------------------------------------------*/
/*

Program     : 
Description : 
Programmer  : ไชยรัตน์  สุนทรวิภาต

Individual
Email       : pipo@sixhead.com
Website     : http://www.sixhead.com
            : http://www.todaysoftware.com

Office
Email       : pipo@digithais.com
Website     : http://www.digithais.com

Date        : 28/06/2007 v1.0	- First Release
			: 29/06/2007 v1.1	- สร้าง Unique Name อัตโนมัติ
Modify log  : 

*/
/*------------------------------------------------------------------*/
/*- Class ----------------------------------------------------------*/

class Css {

	var $CfgClassName	=	"SiXhEaD Css";
	var $CfgVersion		=	"1.1";

	function Css () {
		
	}
	
	function CssLink ($strLinkColor="#006699",$strLinkBackgroundColor="#F8FBFC",$strLinkBorderColor="#E2EFF3",$strRolloverBorderColor="#B7D7E1") {
		$this->intCssUniqueName += 1;

		$CssName	=	"SH_Link" . $this->intCssUniqueName;
		$this->CssLinkAll .= <<<Data
#$CssName				{ float: left; font: 12px Tahoma, Arial, sans-serif; padding: 10px 0; }
#$CssName span			{ font-weight: bold; padding: 2px 6px 3px 6px; border: 1px solid #FFF; }
#$CssName a				{ background: $strLinkBackgroundColor; color: $strLinkColor; text-decoration: none; padding: 2px 6px 3px 6px; border: 1px solid $strLinkBorderColor; }
#$CssName a:hover		{ border-color: $strRolloverBorderColor; }
#$CssName font			{ font: 12px Tahoma, Arial, sans-serif; background: $strLinkBackgroundColor; color: #CCCCCC; padding: 2px 6px 3px 6px; border: 1px solid $strLinkBorderColor; }
#$CssName input			{ color: $strLinkColor; text-align: center; font: 12px Tahoma, Arial, sans-serif; border: 1px solid $strRolloverBorderColor; }

Data;
		return $CssName;
	}

	function CssTable ($intTdBorderSize=0,$strTdBorderColor="#DDDDDD",$strThBorderColor="#999999",$strThBackgroundColor="#DDDDDD",$intBorderSize=0,$strTBBorderColor="#CCCCCC") {
		$this->intCssUniqueName += 1;

		$CssName	=	"SH_Table" . $this->intCssUniqueName;
		if ($intTdBorderSize == 0) { 		
			$this->CssTableAll .= <<<Data
#$CssName table	{ border: ${intBorderSize}px solid $strTBBorderColor; border-collapse: collapse;  }
#$CssName th		{ padding: 2px; border-bottom: 1px solid $strThBorderColor; border-top: 1px solid $strThBorderColor; background: $strThBackgroundColor; }	
#$CssName td		{ padding: 2px; border-bottom: 1px solid $strTdBorderColor; border-top: 1px solid $strTdBorderColor; }

Data;
		}
		else { 
			$this->CssTableAll .= <<<Data
#$CssName table	{ border: ${intBorderSize}px solid $strTBBorderColor; border-collapse: collapse;  }
#$CssName th		{ border: ${intTdBorderSize}px solid $strThBorderColor; padding: 2px; background: $strThBackgroundColor; }	
#$CssName td		{ border: ${intTdBorderSize}px solid $strTdBorderColor; padding: 2px; }

Data;
		}
		return $CssName;
	}

	
	function Generate () {
		if ($this->CssLinkAll || $this->CssTableAll) { 
			$Css	=	<<<Data

<style type="text/css" media="screen">
$this->CssLinkAll$this->CssTableAll</style>
Data;
}
		return $Css;
	}

}


/*------------------------------------------------------------------*/
?>