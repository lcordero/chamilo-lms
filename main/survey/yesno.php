<?php 
/*
    DOKEOS - elearning and course management software

    For a full list of contributors, see documentation/credits.html
   
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.
    See "documentation/licence.html" more details.
 
    Contact: 
		Dokeos
		Rue des Palais 44 Paleizenstraat
		B-1030 Brussels - Belgium
		Tel. +32 (2) 211 34 56
*/

/**
*	@package dokeos.survey
* 	@author 
* 	@version $Id: yesno.php 10223 2006-11-27 14:45:59Z pcool $
*/

// name of the language file that needs to be included 
$language_file = 'survey';

require_once ('../inc/global.inc.php');
//api_protect_admin_script();
$cidReq=$_GET['cidReq'];
$curr_dbname = $_REQUEST['curr_dbname'];
$add_question = $_REQUEST['add_question'];
$groupid = $_REQUEST['groupid'];
$surveyid = $_REQUEST['surveyid'];
if(isset($_REQUEST['questtype']))
$add_question12=$_REQUEST['questtype'];
else
$add_question12=$_REQUEST['add_question'];
require_once ("select_question.php");
require_once (api_get_path(LIBRARY_PATH).'/fileManage.lib.php');
require_once (api_get_path(CONFIGURATION_PATH) ."/add_course.conf.php");
require_once (api_get_path(LIBRARY_PATH)."/add_course.lib.inc.php");
require_once (api_get_path(LIBRARY_PATH)."/surveymanager.lib.php");
$status = surveymanager::get_status();
if($status==5)
{
api_protect_admin_script();
}
require_once (api_get_path(LIBRARY_PATH)."/usermanager.lib.php");
$interbredcrump[] = array ("url" => "survey_list.php?cidReq=$cidReq&n=$n", "name" => get_lang('Survey'));
$table_question = Database :: get_course_table('questions');
$Add = get_lang("addnewquestiontype");
$Multi = get_lang("yesno");
$groupid = $_REQUEST['groupid'];
$surveyid = $_REQUEST['surveyid'];
//$tool_name = get_lang('QuestionType');
if ($_POST['action'] == 'addquestion')
{
   if(isset($_POST['next']))
	{
        $enter_question=$_POST['enterquestion'];
		$answers=$_POST['mutlichkboxtext'];
		$rating=$_POST['chkboxpoint'];	
		$answerT=$_POST['radiotrue'];
		$answerD=$_POST['radiodefault'];
		$alignment='';
		$open_ans="";
		$count=count($_POST['mutlichkboxtext']);		
		$noans=0;
		$nopoint=0;
		for($i=0;$i<$count;$i++)
		{		
			$answers[$i]=trim($answers[$i]);
			if(empty($answers[$i]))
				$noans++;
			if(!is_numeric($rating[$i]))
				$number=1;
		}
		$enter_question=trim($enter_question);
		if(empty($enter_question)) 
		$error_message = get_lang('PleaseEnterAQuestion')."<br>";			
		if ($noans)
		$error_message = $error_message."<br>".get_lang('PleasFillAllAnswer');
		if(isset($error_message))
        {
			//Display::display_error_message($error_message);	
		}
		else
		{
		  $groupid = $_POST['groupid'];	
		  $questtype = $_REQUEST['questtype'];
		  $curr_dbname = $_REQUEST['curr_dbname'];
		  $enter_question = addslashes($enter_question); SurveyManager::create_question($groupid,$surveyid,$questtype,$enter_question,$alignment,$answers,$open_ans,$answerT,$answerD,$rating,$curr_dbname);
		  $cidReq = $_GET['cidReq'];		 header("location:select_question_group.php?groupid=$groupid&surveyid=$surveyid&cidReq=$cidReq&curr_dbname=$curr_dbname");
		  exit;
		}
	}
	elseif(isset($_POST['back']))
	{
	   $groupid = $_REQUEST['groupid'];
	   $surveyid = $_REQUEST['surveyid'];
	   $cidReq = $_GET['cidReq'];
	   $curr_dbname = $_REQUEST['curr_dbname'];
	   header("location:addanother.php?groupid=$groupid&surveyid=$surveyid&cidReq=$cidReq&curr_dbname=$curr_dbname");
	   exit;
	}
	elseif(isset($_POST['saveandexit']))
	{
	  $enter_question=$_POST['enterquestion'];
		$answers=$_POST['mutlichkboxtext'];
		$rating=$_POST['chkboxpoint'];	
		$answerT=$_POST['radiotrue'];
		$answerD=$_POST['radiodefault'];
		$alignment='';
		$open_ans="";
		$count=count($_POST['mutlichkboxtext']);		
		$noans=0;
		$nopoint=0;
		for($i=0;$i<$count;$i++)
		{		
			$answers[$i]=trim($answers[$i]);
			if(empty($answers[$i]))
				$noans++;
			if(empty($rating[$i])&&($rating[$i]!='0'))
				$nopoint++;
		}
		$enter_question=trim($enter_question);
		if(empty($enter_question)) 
		$error_message = get_lang('PleaseEnterAQuestion')."<br>";			
		if ($noans)
		$error_message = $error_message."<br>".get_lang('PleasFillAllAnswer');
		if(isset($error_message))
        {
			//Display::display_error_message($error_message);	
		}
		else
		{

	     $groupid = $_REQUEST['groupid'];
	     $surveyid = $_REQUEST['surveyid'];
	     $questtype = $_REQUEST['questtype'];	
		 $curr_dbname = $_REQUEST['curr_dbname'];
		 $enter_question = addslashes($enter_question); SurveyManager::create_question($groupid,$surveyid,$questtype,$enter_question,$alignment,$answers,$open_ans,$answerT,$answerD,$rating,$curr_dbname);
         $cidReq = $_GET['cidReq'];
	     header("location:survey_list.php?cidReq=$cidReq&n=$n");
	     exit;
	   }
	}
}
?>
<?
$tool = get_lang('AddAnotherQuestion');
Display::display_header($tool);
select_question_type($add_question12,$groupid,$surveyid,$cidReq,$curr_dbname);
?>
<table>
<tr>
<td>
<?php api_display_tool_title($Add);?>
</td>
<td>
<?php api_display_tool_title($Multi);?>

</td>
</tr>
</table>
<?php
if( isset($error_message) )
{
	Display::display_error_message($error_message);	
}
?>
<SCRIPT LANGUAGE="JAVASCRIPT">
function checkLength(form){
    if (form.description.value.length > 250){
        alert("Text too long. Must be 250 characters or less");
        return false;
    }
    return true;
}
</SCRIPT>
<form method="POST" name = "yesno" id="yesno" action="<?php echo $_SERVER['PHP_SELF'];?>?cidReq=<?php echo $cidReq; ?>&add_question=<?php echo $add_question; ?>&groupid=<?php echo $groupid; ?>&surveyid=<?php echo $surveyid; ?>&curr_dbname=<?php echo $curr_dbname; ?>">
<input type="hidden" name="groupid" value="<?php echo $groupid; ?>">
<input type="hidden" name="surveyid" value="<?php echo $surveyid; ?>">
<input type="hidden" name="questtype" value="<?php echo $add_question12; ?>">
<input type="hidden" name="curr_dbname" value="<?php echo $curr_dbname; ?>">
<!--<input type="hidden" name="cidReq" value="<?php echo $cidReq; ?>">-->
<input type="hidden" name="action" value="addquestion" >
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="outerBorder_innertable">
<tr> 
<td class="pagedetails_heading"><a class="form_text_bold"><strong>Question</strong></a></td>
</tr>
</table>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="outerBorder_innertable">
				<tr class="white_bg"> 
					<td height="30" class="form_text1"> 
						Enter the question.        
					</td>
					<td class="form_text1" align="right">&nbsp;
					</td>
				</tr>
				<tr class="form_bg"> 
					<td width="542" height="30" colspan="2" ><?php  api_disp_html_area('enterquestion',$enterquestion,'200px');?>
					</td>
				</tr>
			</table>
			<br>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="outerBorder_innertable">
			<tr> 
				<td class="pagedetails_heading"><a class="form_text_bold"><strong>Answer</strong></a></td>
			</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="outerBorder_innertable">
				<tr class="white_bg"> 
					<td height="30"><span class="form_text1">Enter the answers</span>.
					</td>
					<td>&nbsp;</td>
					<td width="192" align="right">&nbsp; </td>
				</tr>
			</table>										
			<table ID="tblFields" width="70%" border="0" cellpadding="0" cellspacing="0" class="outerBorder_innertable">
<?php	
	$start=1;$end=2;$upx=2;$upy=1;$dwnx=0;$dwny=1;$jd=0;$sn=1;
	$id="id";
	$tempmutlichkboxtext="jkjk";		
	if(isset($_POST['radiodefault']))
	$tempradiodefault=$_POST['radiodefault'];
	else
	$tempradiodefault=1;
	$tempchkboxpoint="jkjk";	
	if(isset($_POST['radiotrue']))
	$tempradiotrue=$_POST['radiotrue'];
	else
	$tempradiotrue=1;
	$up="up";
	$down="down";
	$flag=1;
	if(isset($_POST['mutlichkboxtext']))
	$end=count($_POST['mutlichkboxtext']);	
	for($i=$start;$i<=$end;$i++)
	{	
		$id="id".$i."_x";
		//echo ",".$id;
		if(isset($_POST[$id]))
		{
				$jd=$i;
				$flag=0;
				$end=count($_POST['mutlichkboxtext']);
				if($end<=3)
				{
					$end=3;
				}
				else
				$end-=1;
				break;
				//echo ",while checking id,end=".$end;
		}
	}	
	for($i=$start;$i<=$end;$i++)
	{
		
		$up="up".$i."_x";
		$down="down".$i."_x";		
		if(isset($_POST[$up])||isset($_POST[$down]))
		{			
			$flag=0;
			if(isset($_POST[$up]))
			{
				$tempmutlichkboxtext=$_POST['mutlichkboxtext'];
				if($tempradiodefault==$i)
					$tempradiodefault--;
				elseif($tempradiodefault==$i-1)
					$tempradiodefault++;
				$tempchkboxpoint=$_POST['chkboxpoint'];
				if($tempradiotrue==$i)
					$tempradiotrue--;
				elseif($tempradiotrue==$i-1)
					$tempradiotrue++;										
				$tempm=	$tempmutlichkboxtext[$i-2];
				$tempchkboxp=$tempchkboxpoint[$i-2];
				$tempmutlichkboxtext[$i-2]=$tempmutlichkboxtext[$i-1];
				$tempchkboxpoint[$i-2]=$tempchkboxpoint[$i-1];
				$tempmutlichkboxtext[$i-1]=$tempm;
				$tempchkboxpoint[$i-1]=$tempchkboxp;
				$_POST['mutlichkboxtext']=$tempmutlichkboxtext;
				$_POST['chkboxpoint']=$tempchkboxpoint;
			}
			if(isset($_POST[$down]))
			{
				$tempmutlichkboxtext=$_POST['mutlichkboxtext'];
				if($tempradiodefault==$i)
					$tempradiodefault++;
				elseif($tempradiodefault==$i+1)
					$tempradiodefault--;
				$tempchkboxpoint=$_POST['chkboxpoint'];
				if($tempradiotrue==$i)
					$tempradiotrue++;
				elseif($tempradiotrue==$i+1)
					$tempradiotrue--;
				$tempm=	$tempmutlichkboxtext[$i];
				$tempchkboxp=$tempchkboxpoint[$i];
				$tempmutlichkboxtext[$i]=$tempmutlichkboxtext[$i-1];
				$tempchkboxpoint[$i]=$tempchkboxpoint[$i-1];
				$tempmutlichkboxtext[$i-1]=$tempm;
				$tempchkboxpoint[$i-1]=$tempchkboxp;
				$_POST['mutlichkboxtext']=$tempmutlichkboxtext;
				$_POST['chkboxpoint']=$tempchkboxpoint;
			}
			//echo ",while checking up/down end=".$end;
			$jd=0;
			break;		
		}
	}	
	if($flag==1)
	{
		if(isset($_POST['addnewrows']))
		{								
				$end=count($_POST['mutlichkboxtext']);							
				if($end<10)
				{
					$end=$end+$_POST['addnewrows'];
					if($end>10)
						$end=10;
				}
				else
				$end=10;
			//echo ",while checking select end=".$end;
			/*else
			$end=$end+$_POST['addnewrows'];*/
		}
	}		
	//echo ",after select end=".$end;	
	for($i=$start;$i<=$end;$i++)
	{
		if($i==$jd)
		/*{
			if($end<=3);
			else;
			//continue;
		}*/
		{
			$end++;
		}
		else
		{
			$k=$i-1;
			$post_text = $_POST['mutlichkboxtext'];
			//$post_check=$_POST['radiodefault'];
			$post_point=$_POST['chkboxpoint'];
			//$post_true=$_POST['radiotrue'];	
?>					
			<tr class="form_bg" id="0"> 					
					<td width="16" height="30" align="left" class="form_text"> 
					  <?php echo $sn;?>
					</td>					
					<td class="form_bg"><textarea name="mutlichkboxtext[]" cols="50" rows="3" class="text_field" style="width:100%;"><?php echo $post_text[$k]; ?></textarea> 
					</td>					
					<td width="10" class="form_text"><img src="../img/blank.gif" width="10" height="8">
					</td>
					<td width="10" class="form_text"><img src="../img/blank.gif" width="10" height="8">
					</td>					
<?					if($i>$start)
					{
?>
					<td width="30" align="center" class="form_text1"> 
						<input type="image" src="../img/up.gif" width="24" height="24" border="0" onclick="this.form.submit();" name="<?echo "up".$i;?>" style="cursor:hand"> 
					</td>
<?					}
					else
					{
?>						<td width="30" align="center" class="form_text1"> 
						</td>
<?					}
					$sn++;
?>

<?					if($i<$end)
					{
?>
					<td width="30" align="center" class="form_text"> 
						<input type="image" src="../img/down.gif" width="24" height="24" border="0" onclick="this.form.submit();" name="<?echo "down".$i;?>" style="cursor:hand"> 
					</td>
<?					}
					else
					{
?>						<td width="30" align="center" class="form_text1"> 
						</td>
<?					}
?>
					<td width="30" align="center" class="form_text">					
			</tr>
<?		}	
	} 	
?>		
            </table>
            <br>
			<br>
			<div align="center">
			<input type="HIDDEN" name="end1" value="<?php echo $end; ?>">
<?			
            if(isset($_POST['add_question']))
			{
?>				<input type="hidden" name="add_question" value="<?php echo $_POST['add_question'];?>" >
<?			}

			$sql = "SELECT * FROM $curr_dbname.survey WHERE survey_id='$surveyid'";
			$res=api_sql_query($sql);
			$obj=mysql_fetch_object($res);
			switch($obj->template)
			{
				case "template1":
					$temp = 'white';
					break;
				case "template2":
					$temp = 'bluebreeze';
					break;
				case "template3":
					$temp = 'brown';
					break;
				case "template4":
					$temp = 'grey';
					break;	
				case "template5":
					$temp = 'blank';
					break;
			}
?>
						<input type="submit"  name="back" value="<?php echo get_lang("Back");?>">
						<input type="submit"  name="saveandexit" value="<?php echo get_lang("SaveAndExit");?>">
						<input type="button" value="<?php echo get_lang('preview');?>" onClick="preview('yesno','<?php echo $temp;?>','<?php echo $Multi; ?>')">
						<input type="submit"  name="next" value="<?php echo get_lang("next");?>"> 
			</div>
<!--this partcular field helps in identify the item to be add at the itemadd.php-->			
</form>
</div>  
<div id=bottomnav align="center"></DIV>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">
function preview(form,temp,qtype)
{
	var ques = editor.getHTML();
    //alert(ques);
	var id_str = "";
	
	for(i=0;i<eval("document."+form+"['mutlichkboxtext[]'].length");i++)
	{
		var box = (eval("document."+form+"['mutlichkboxtext[]']["+i+"]"));
			id_str += box.value+"|";
	}
	window.open(temp+'.php?temp=<?php echo $temp;?>&ques='+ques+'&ans='+id_str+'&qtype='+qtype, 'popup', 'width=800,height=600,scrollbars=yes,toolbar = no, status = no');
}
</script>
<?php
Display :: display_footer();
?>