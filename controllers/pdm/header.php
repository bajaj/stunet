<script type="text/javascript" src="uploads/files/pdm/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>


	<script type="text/javascript">
	tinyMCE.init({
   		  mode : "textareas"
          elements : "filecontent",
          theme : "advanced",
          theme_advanced_toolbar_location : "top",
          theme_advanced_toolbar_align : "left"
		  });
	
	</script>  
	

<?php ft_make_scripts();?>

  <script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		// Set focus on login username.
		if (document.getElementById("ft_user")) {
			document.getElementById("ft_user").focus();
		}
		// Set global object.
		var ft = {fileactions:{}};
		// Prep upload section.
		$('#uploadsection').parent().ft_upload({
		  header:"<?php echo t('Files for upload:');?>",
		  cancel: "<?php echo t('Cancel upload of this file');?>",
		  upload: "<?php echo t('Now uploading files. Please wait...');?>"
		});
		// Prep file actions.
		$('#filelist').ft_filelist({
		  fileactions: ft.fileactions,
		  rename_link: "<?php echo t('Rename');?>",
		  move_link: "<?php echo t('Move');?>",
		  del_link: "<?php echo t('Delete');?>",
		  duplicate_link: "<?php echo t('Duplicate');?>",
		  unzip_link: "<?php echo t('Unzip');?>",
		  chmod_link: "<?php echo t('chmod');?>",
		  symlink_link: "<?php echo t('Symlink');?>",
		  rename: "<?php echo t('Rename to:');?>",
      move: "<?php echo t('Move to folder:');?>",
      del: "<?php echo t('Do you really want to delete file?');?>",
      del_warning: "<?php echo t('You can only delete empty folders.');?>",
      del_button: "<?php echo t('Yes, delete it');?>",
      duplicate: "<?php echo t('Duplicate to file:');?>",
      unzip: "<?php echo t('Do you really want to unzip file?');?>",
      unzip_button: "<?php echo t('Yes, unzip it');?>",
      chmod: "<?php echo t('Set permissions to:');?>",
      symlink: "<?php echo t('Create symlink called:');?>",
		  directory: "<?php if (!empty($_REQUEST['dir'])) {echo $_REQUEST['dir'];}?>",
		  ok: "<?php echo t('Ok');?>",
		  formpost: "<?php echo ft_get_self();?>",
		  advancedactions: "<?php if (ADVANCEDACTIONS === TRUE) {echo 'true';} else {echo 'false';}?>"
		});

		// Sort select box.
		$('#sort').change(function(){
		  $('#sort_form').submit();
		});
		// Label highlight in 'create' box.
    $('#new input[type=radio]').change(function(){
      $('label').removeClass('label_highlight');
      $('label[@for='+$(this).attr('id')+']').addClass('label_highlight');
    });
<?php echo implode("\r\n", ft_invoke_hook('add_js_call'));?>
	});
	</script>
	