<?php

// Data functions (insert, update, delete, form) for table classattendance

// This script and data application were generated by AppGini 5.70
// Download AppGini for free from https://bigprof.com/appgini/download/

function classattendance_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('classattendance');
	if(!$arrPerm[1]){
		return false;
	}

	$data['Subject'] = makeSafe($_REQUEST['Subject']);
		if($data['Subject'] == empty_lookup_value){ $data['Subject'] = ''; }
	$data['Student'] = makeSafe($_REQUEST['Student']);
		if($data['Student'] == empty_lookup_value){ $data['Student'] = ''; }
	$data['RegNo'] = makeSafe($_REQUEST['Student']);
		if($data['RegNo'] == empty_lookup_value){ $data['RegNo'] = ''; }
	$data['Class'] = makeSafe($_REQUEST['Student']);
		if($data['Class'] == empty_lookup_value){ $data['Class'] = ''; }
	$data['Stream'] = makeSafe($_REQUEST['Student']);
		if($data['Stream'] == empty_lookup_value){ $data['Stream'] = ''; }
	$data['Attended'] = makeSafe($_REQUEST['Attended']);
		if($data['Attended'] == empty_lookup_value){ $data['Attended'] = ''; }
	$data['Date'] = intval($_REQUEST['DateYear']) . '-' . intval($_REQUEST['DateMonth']) . '-' . intval($_REQUEST['DateDay']);
	$data['Date'] = parseMySQLDate($data['Date'], '1');
	if($data['Subject']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Subject': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	if($data['Student']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Student': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}

	// hook: classattendance_before_insert
	if(function_exists('classattendance_before_insert')){
		$args=array();
		if(!classattendance_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `classattendance` set       `Subject`=' . (($data['Subject'] !== '' && $data['Subject'] !== NULL) ? "'{$data['Subject']}'" : 'NULL') . ', `Student`=' . (($data['Student'] !== '' && $data['Student'] !== NULL) ? "'{$data['Student']}'" : 'NULL') . ', `RegNo`=' . (($data['RegNo'] !== '' && $data['RegNo'] !== NULL) ? "'{$data['RegNo']}'" : 'NULL') . ', `Class`=' . (($data['Class'] !== '' && $data['Class'] !== NULL) ? "'{$data['Class']}'" : 'NULL') . ', `Stream`=' . (($data['Stream'] !== '' && $data['Stream'] !== NULL) ? "'{$data['Stream']}'" : 'NULL') . ', `Attended`=' . (($data['Attended'] !== '' && $data['Attended'] !== NULL) ? "'{$data['Attended']}'" : 'NULL') . ', `Date`=' . (($data['Date'] !== '' && $data['Date'] !== NULL) ? "'{$data['Date']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"classattendance_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: classattendance_after_insert
	if(function_exists('classattendance_after_insert')){
		$res = sql("select * from `classattendance` where `id`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!classattendance_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('classattendance', $recID, getLoggedMemberID());

	return $recID;
}

function classattendance_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('classattendance');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='classattendance' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='classattendance' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: classattendance_before_delete
	if(function_exists('classattendance_before_delete')){
		$args=array();
		if(!classattendance_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `classattendance` where `id`='$selected_id'", $eo);

	// hook: classattendance_after_delete
	if(function_exists('classattendance_after_delete')){
		$args=array();
		classattendance_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='classattendance' and pkValue='$selected_id'", $eo);
}

function classattendance_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('classattendance');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='classattendance' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='classattendance' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['Subject'] = makeSafe($_REQUEST['Subject']);
		if($data['Subject'] == empty_lookup_value){ $data['Subject'] = ''; }
	if($data['Subject']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Subject': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['Student'] = makeSafe($_REQUEST['Student']);
		if($data['Student'] == empty_lookup_value){ $data['Student'] = ''; }
	if($data['Student']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Student': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['RegNo'] = makeSafe($_REQUEST['Student']);
		if($data['RegNo'] == empty_lookup_value){ $data['RegNo'] = ''; }
	$data['Class'] = makeSafe($_REQUEST['Student']);
		if($data['Class'] == empty_lookup_value){ $data['Class'] = ''; }
	$data['Stream'] = makeSafe($_REQUEST['Student']);
		if($data['Stream'] == empty_lookup_value){ $data['Stream'] = ''; }
	$data['Attended'] = makeSafe($_REQUEST['Attended']);
		if($data['Attended'] == empty_lookup_value){ $data['Attended'] = ''; }
	$data['Date'] = intval($_REQUEST['DateYear']) . '-' . intval($_REQUEST['DateMonth']) . '-' . intval($_REQUEST['DateDay']);
	$data['Date'] = parseMySQLDate($data['Date'], '1');
	$data['selectedID']=makeSafe($selected_id);

	// hook: classattendance_before_update
	if(function_exists('classattendance_before_update')){
		$args=array();
		if(!classattendance_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `classattendance` set       `Subject`=' . (($data['Subject'] !== '' && $data['Subject'] !== NULL) ? "'{$data['Subject']}'" : 'NULL') . ', `Student`=' . (($data['Student'] !== '' && $data['Student'] !== NULL) ? "'{$data['Student']}'" : 'NULL') . ', `RegNo`=' . (($data['RegNo'] !== '' && $data['RegNo'] !== NULL) ? "'{$data['RegNo']}'" : 'NULL') . ', `Class`=' . (($data['Class'] !== '' && $data['Class'] !== NULL) ? "'{$data['Class']}'" : 'NULL') . ', `Stream`=' . (($data['Stream'] !== '' && $data['Stream'] !== NULL) ? "'{$data['Stream']}'" : 'NULL') . ', `Attended`=' . (($data['Attended'] !== '' && $data['Attended'] !== NULL) ? "'{$data['Attended']}'" : 'NULL') . ', `Date`=' . (($data['Date'] !== '' && $data['Date'] !== NULL) ? "'{$data['Date']}'" : 'NULL') . " where `id`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="classattendance_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: classattendance_after_update
	if(function_exists('classattendance_after_update')){
		$res = sql("SELECT * FROM `classattendance` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id'];
		$args = array();
		if(!classattendance_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='classattendance' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function classattendance_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('classattendance');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_Subject = thisOr(undo_magic_quotes($_REQUEST['filterer_Subject']), '');
	$filterer_Student = thisOr(undo_magic_quotes($_REQUEST['filterer_Student']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: Subject
	$combo_Subject = new DataCombo;
	// combobox: Student
	$combo_Student = new DataCombo;
	// combobox: Date
	$combo_Date = new DateCombo;
	$combo_Date->DateFormat = "mdy";
	$combo_Date->MinYear = 1900;
	$combo_Date->MaxYear = 2100;
	$combo_Date->DefaultDate = parseMySQLDate('1', '1');
	$combo_Date->MonthNames = $Translation['month names'];
	$combo_Date->NamePrefix = 'Date';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='classattendance' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='classattendance' and pkValue='".makeSafe($selected_id)."'");
		if($arrPerm[2]==1 && getLoggedMemberID()!=$ownerMemberID){
			return "";
		}
		if($arrPerm[2]==2 && getLoggedGroupID()!=$ownerGroupID){
			return "";
		}

		// can edit?
		if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){
			$AllowUpdate=1;
		}else{
			$AllowUpdate=0;
		}

		$res = sql("select * from `classattendance` where `id`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'classattendance_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_Subject->SelectedData = $row['Subject'];
		$combo_Student->SelectedData = $row['Student'];
		$combo_Date->DefaultDate = $row['Date'];
	}else{
		$combo_Subject->SelectedData = $filterer_Subject;
		$combo_Student->SelectedData = $filterer_Student;
	}
	$combo_Subject->HTML = '<span id="Subject-container' . $rnd1 . '"></span><input type="hidden" name="Subject" id="Subject' . $rnd1 . '" value="' . html_attr($combo_Subject->SelectedData) . '">';
	$combo_Subject->MatchText = '<span id="Subject-container-readonly' . $rnd1 . '"></span><input type="hidden" name="Subject" id="Subject' . $rnd1 . '" value="' . html_attr($combo_Subject->SelectedData) . '">';
	$combo_Student->HTML = '<span id="Student-container' . $rnd1 . '"></span><input type="hidden" name="Student" id="Student' . $rnd1 . '" value="' . html_attr($combo_Student->SelectedData) . '">';
	$combo_Student->MatchText = '<span id="Student-container-readonly' . $rnd1 . '"></span><input type="hidden" name="Student" id="Student' . $rnd1 . '" value="' . html_attr($combo_Student->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_Subject__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['Subject'] : $filterer_Subject); ?>"};
		AppGini.current_Student__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['Student'] : $filterer_Student); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(Subject_reload__RAND__) == 'function') Subject_reload__RAND__();
				if(typeof(Student_reload__RAND__) == 'function') Student_reload__RAND__();
			}, 10); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function Subject_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#Subject-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_Subject__RAND__.value, t: 'classattendance', f: 'Subject' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="Subject"]').val(resp.results[0].id);
							$j('[id=Subject-container-readonly__RAND__]').html('<span id="Subject-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=subjects_view_parent]').hide(); }else{ $j('.btn[id=subjects_view_parent]').show(); }


							if(typeof(Subject_update_autofills__RAND__) == 'function') Subject_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'classattendance', f: 'Subject' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_Subject__RAND__.value = e.added.id;
				AppGini.current_Subject__RAND__.text = e.added.text;
				$j('[name="Subject"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=subjects_view_parent]').hide(); }else{ $j('.btn[id=subjects_view_parent]').show(); }


				if(typeof(Subject_update_autofills__RAND__) == 'function') Subject_update_autofills__RAND__();
			});

			if(!$j("#Subject-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_Subject__RAND__.value, t: 'classattendance', f: 'Subject' },
					success: function(resp){
						$j('[name="Subject"]').val(resp.results[0].id);
						$j('[id=Subject-container-readonly__RAND__]').html('<span id="Subject-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=subjects_view_parent]').hide(); }else{ $j('.btn[id=subjects_view_parent]').show(); }

						if(typeof(Subject_update_autofills__RAND__) == 'function') Subject_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_Subject__RAND__.value, t: 'classattendance', f: 'Subject' },
				success: function(resp){
					$j('[id=Subject-container__RAND__], [id=Subject-container-readonly__RAND__]').html('<span id="Subject-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=subjects_view_parent]').hide(); }else{ $j('.btn[id=subjects_view_parent]').show(); }

					if(typeof(Subject_update_autofills__RAND__) == 'function') Subject_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function Student_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#Student-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_Student__RAND__.value, t: 'classattendance', f: 'Student' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="Student"]').val(resp.results[0].id);
							$j('[id=Student-container-readonly__RAND__]').html('<span id="Student-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=students_view_parent]').hide(); }else{ $j('.btn[id=students_view_parent]').show(); }


							if(typeof(Student_update_autofills__RAND__) == 'function') Student_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'classattendance', f: 'Student' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_Student__RAND__.value = e.added.id;
				AppGini.current_Student__RAND__.text = e.added.text;
				$j('[name="Student"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=students_view_parent]').hide(); }else{ $j('.btn[id=students_view_parent]').show(); }


				if(typeof(Student_update_autofills__RAND__) == 'function') Student_update_autofills__RAND__();
			});

			if(!$j("#Student-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_Student__RAND__.value, t: 'classattendance', f: 'Student' },
					success: function(resp){
						$j('[name="Student"]').val(resp.results[0].id);
						$j('[id=Student-container-readonly__RAND__]').html('<span id="Student-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=students_view_parent]').hide(); }else{ $j('.btn[id=students_view_parent]').show(); }

						if(typeof(Student_update_autofills__RAND__) == 'function') Student_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_Student__RAND__.value, t: 'classattendance', f: 'Student' },
				success: function(resp){
					$j('[id=Student-container__RAND__], [id=Student-container-readonly__RAND__]').html('<span id="Student-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=students_view_parent]').hide(); }else{ $j('.btn[id=students_view_parent]').show(); }

					if(typeof(Student_update_autofills__RAND__) == 'function') Student_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/classattendance_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/classattendance_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'AttendanceManagement details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm[1] && !$selected_id){ // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return classattendance_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return classattendance_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'AppGini.closeParentModal(); return false;';
	}else{
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return classattendance_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#Subject').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#Subject_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#Student').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#Student_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#Attended').prop('disabled', true);\n";
		$jsReadOnly .= "\tjQuery('#Date').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#DateDay, #DateMonth, #DateYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif(($AllowInsert && !$selected_id) || ($AllowUpdate && $selected_id)){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(Subject)%%>', $combo_Subject->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(Subject)%%>', $combo_Subject->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(Subject)%%>', urlencode($combo_Subject->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(Student)%%>', $combo_Student->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(Student)%%>', $combo_Student->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(Student)%%>', urlencode($combo_Student->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(Date)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_Date->GetHTML(true) . '</div>' : $combo_Date->GetHTML()), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(Date)%%>', $combo_Date->GetHTML(true), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'Subject' => array('subjects', 'Subject'), 'Student' => array('students', 'Student'));
	foreach($lookup_fields as $luf => $ptfc){
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']){
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']){
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(id)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Subject)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Student)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Attended)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Date)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Subject)%%>', safe_html($urow['Subject']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Subject)%%>', html_attr($row['Subject']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Subject)%%>', urlencode($urow['Subject']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Student)%%>', safe_html($urow['Student']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Student)%%>', html_attr($row['Student']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Student)%%>', urlencode($urow['Student']), $templateCode);
		$templateCode = str_replace('<%%CHECKED(Attended)%%>', ($row['Attended'] ? "checked" : ""), $templateCode);
		$templateCode = str_replace('<%%VALUE(Date)%%>', @date('m/d/Y', @strtotime(html_attr($row['Date']))), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Date)%%>', urlencode(@date('m/d/Y', @strtotime(html_attr($urow['Date'])))), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(Subject)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Subject)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(Student)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Student)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%CHECKED(Attended)%%>', '', $templateCode);
		$templateCode = str_replace('<%%VALUE(Date)%%>', '1', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Date)%%>', urlencode('1'), $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode = str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == ''){
		$templateCode .= "\n\n<script>\$j(function(){\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption){
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id){
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';

	$templateCode .= "\tStudent_update_autofills$rnd1 = function(){\n";
	$templateCode .= "\t\t\$j.ajax({\n";
	if($dvprint){
		$templateCode .= "\t\t\turl: 'classattendance_autofill.php?rnd1=$rnd1&mfk=Student&id=' + encodeURIComponent('".addslashes($row['Student'])."'),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET'\n";
	}else{
		$templateCode .= "\t\t\turl: 'classattendance_autofill.php?rnd1=$rnd1&mfk=Student&id=' + encodeURIComponent(AppGini.current_Student{$rnd1}.value),\n";
		$templateCode .= "\t\t\tcontentType: 'application/x-www-form-urlencoded; charset=" . datalist_db_encoding . "', type: 'GET', beforeSend: function(){ \$j('#Student$rnd1').prop('disabled', true); \$j('#StudentLoading').html('<img src=loading.gif align=top>'); }, complete: function(){".(($arrPerm[1] || (($arrPerm[3] == 1 && $ownerMemberID == getLoggedMemberID()) || ($arrPerm[3] == 2 && $ownerGroupID == getLoggedGroupID()) || $arrPerm[3] == 3)) ? "\$j('#Student$rnd1').prop('disabled', false); " : "\$j('#Student$rnd1').prop('disabled', true); ")."\$j('#StudentLoading').html('');}\n";
	}
	$templateCode.="\t\t});\n";
	$templateCode.="\t};\n";
	if(!$dvprint) $templateCode.="\tif(\$j('#Student_caption').length) \$j('#Student_caption').click(function(){ Student_update_autofills$rnd1(); });\n";


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('classattendance');
	if($selected_id){
		$jdata = get_joined_record('classattendance', $selected_id);
		if($jdata === false) $jdata = get_defaults('classattendance');
		$rdata = $row;
	}
	$cache_data = array(
		'rdata' => array_map('nl2br', array_map('addslashes', $rdata)),
		'jdata' => array_map('nl2br', array_map('addslashes', $jdata))
	);
	$templateCode .= loadView('classattendance-ajax-cache', $cache_data);

	// hook: classattendance_dv
	if(function_exists('classattendance_dv')){
		$args=array();
		classattendance_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>