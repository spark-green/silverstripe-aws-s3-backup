<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AWSBackupSiteConfig
 *
 * @author julian.smith
 */
class AWSBackupSiteConfig extends DataExtension {
	
	public static $db = array(
		'AWSAccessKey' => 'varchar(255)',
		'AWSSecretKey' => 'varchar(255)',
		'AWSFolder' => 'varchar(255)'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldsToTab(
			'Root.AWSBackup',
			array(
				new HeaderField('aws-backup-heading', 'AWS Backup Settings'),
				new LiteralField('aws-backup-explanation', '<p>This module transfers any .gz files in the assets directory to AWS S3</p>'),
				$a = new TextField('AWSAccessKey','AWS Access Key'),
				$b = new TextField('AWSSecretKey', 'AWS Access Key'),
				$c = new TextField('AWSFolder', 'AWS Backup Storage Folder'),
				new LiteralField('AWSBackupNow', 
					'<input type="button" id="aws-backup-action" value="Transfer Archives to AWS now" class="ss-ui-button ui-button" role="button" aria-disabled="false">'.
					'<script type="text/javascript">'.
						'jQuery(\'#aws-backup-action\').click(function() {'.
							'jQuery("<p id=\'aws-waiter\' class=\'message notice\'>Transferring, please wait.</p>").insertAfter(jQuery("#Form_EditForm_aws-backup-heading"));'.
							'jQuery(\'html\').css(\'cursor\', \'wait\');'.
							'jQuery.ajax(\'dev/tasks/S3Backup\', {'.
								'\'async\':\'false\','.
								'\'success\': function(data){'.
									'jQuery(\'html\').css(\'cursor\', \'default\');'.
									'jQuery("#aws-waiter").removeClass(\'notice\');'.
									'jQuery("#aws-waiter").addClass(\'good\');'.
									'jQuery("#aws-waiter").html("AWS Transfer Complete");'.
									'jQuery("#aws-backup-results").html(data);'.
								'}'. 
							'}); '. 
						'});'.
					'</script>'
				),
				$d = new LiteralField('AWSResponse','<div id="aws-backup-results"></div>')
			)
		);

	}


}
