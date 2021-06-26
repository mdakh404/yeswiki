<?php

$GLOBALS['translations'] = array_merge($GLOBALS['translations'], array(

// libs/attach.lib.php
'ATTACH_ACTION_ATTACH' => 'Action {{attach ...}}',
'ATTACH_PARAM_DESC_REQUIRED' => '"desc" parameter required for an image',
'ATTACH_PARAM_HEIGHT_NOT_NUMERIC' => '"height" parameter, in pixels, should be a number integer',
'ATTACH_PARAM_WIDTH_NOT_NUMERIC' => '"width" parameter, in pixels, should be a number integer',
'ATTACH_UPLOAD_FORM_FOR_FILE' => 'Upload form for file',
'ATTACH_BACK_TO_PAGE' => 'Back to the page',
'NO_RIGHT_TO_WRITE_IN_THIS_PAGE' => 'No rights to write in this page',
'ATTACH_ACTION_FULLIMAGELINK_TEXT' => "Add a link to only display the full image",


// actions/filemanager.php
'ATTACH_NO_RIGHTS_TO_ACCESS_FILEMANAGER' => 'You have no rights to access the filemanager',

// actions/player.php
'ATTACH_ACTION_PLAYER' => 'Action {{player ...}}',
'ATTACH_DOWNLOAD_THE_FILE' => 'Download the file',
'ATTACH_URL_NOT_VALID' => 'Invalid URL or not openable',
'ATTACH_PARAM_URL_REQUIRED' => '"url" parameter required',
'ATTACH_PLAYER_CAN_ONLY_OPEN_FILES_LIKE' => 'The player can only open .mp3, .flv and .mm files and your URL',
'ATTACH_NOT_LINKED_TO_GOOD_FILE_EXTENSION' => 'is not linked to those type of files',

// actions/pointimage.php
'ATTACH_ACTION_POINTIMAGE' => 'Action {{pointimage ...}}',
'ATTACH_PARAM_FILE_NOT_FOUND' => '"file" parameter required',
'ATTACH_PARAM_FILE_MUST_BE_IMAGE' => '"file" parameter must be an image (.gif,.jpg,.jpeg or .png)',
'ATTACH_DEFAULT_MARKER' => 'Default marker',
'ATTACH_ADD_MARKER' => 'Add a marker',
'ATTACH_TITLE' => 'Title',
'ATTACH_DESCRIPTION' => 'Description',
'ATTACH_CANCEL' => 'Cancel',
'ATTACH_SAVE' => 'Save',

// actions/video.php
'ATTACH_ACTION_VIDEO_PARAM_ERROR' => 'The action video must be called with parameters « id » and « serveur ». For « serveur », only values « vimeo » or « youtube » or « peertube » are allowed.',
        
// actions/pdf.php
'ATTACH_ACTION_PDF_PARAM_URL_ERROR' => 'The action pdf must be called with parameter « url » and the given url must be on the same host than the wiki(for example \'xxx.yyy.com\'), same schema (for example \'https\') and the same port if specified (for example \'8080\').',

// handler edit
'ACTIVATE_JS_TO_UPLOAD_FILES' => 'Activate JavaScript to upload files',
'UPLOAD_A_FILE' => 'Upload a file',
'UPLOAD_A_FILE_SHORT' => 'File',
'UPLOAD_FILE' => 'Upload file',
'CANCEL_THIS_UPLOAD' => 'Cancel this upload',
'INSERT' => 'Insert',
'DOWNLOAD_LINK_TEXT' => 'Download link text',
'IMAGE_ALIGN' => 'Image alignment',
'IMAGE_SIZE' => 'Image size',
'THUMBNAIL' => 'Thumbnail',
'MEDIUM' => 'Medium',
'BIG' => 'Big',
'ORIGINAL_SIZE' => 'Original size',
'CAPTION' => 'Caption text',
'SEE_THE_ADVANCED_PARAMETERS' => 'See the advanced parameters',
'ADVANCED_PARAMETERS' => 'Advanced parameters',
'ASSOCIATED_LINK' => 'Associated link',
'GRAPHICAL_EFFECTS' => 'Graphical effects',
'WHITE_BORDER' => 'White border',
'DROP_SHADOW' => 'Drop shadow',
'ZOOM_HOVER' => 'Zoom on hover',
'ALT_INFOS' => 'This texte will be displayed instead of the image, if the image is not found',
'ALTERNATIVE_TEXT' => 'Alternative text',
'LEFT' => 'Left',
'CENTER' => 'Center',
'RIGHT' => 'Right',
'FAILED' => 'Failed',

'ADMINS_ONLY_ACTION' => 'Admins group\'s members restricted action',

'MEDIA_FILES_DIR_LISTING' => 'List files directory files',
'MEDIA_FILE' => 'File',
'MEDIA_NAME' => 'Media',
'MEDIA_UPLOAD_TIME' => 'Upload time',
'MEDIA_PAGE' => 'Page',
'MEDIA_PAGE_VERSION' => 'Page version',
'MEDIA_PAGE_VERSION' => 'Media version',
'MEDIA_LATEST_VERSION' => 'Last',
'MEDIA_FILE_EXPLANATION' => 'Name of the file into "files" directory. May contain more than one proposal, each proposal then corresponds to a media name. No active page was found using any of those combinaisons.',
'MEDIA_NAME_EXPLANATION' => 'Name typed in file parameter of {{attach}}. May contain more than one proposal, each proposal then corresponds to a file name. No active page was found using any of those combinaisons.. Cell merged with "File" for bazar records.',
'MEDIA_UPLOAD_TIME_EXPLANATION' => 'Date and time of this file upload. Cell merged with "File" for bazar records.',
'MEDIA_PAGE_EXPLANATION' => 'Wiki page calling the file ({attach} action or bazar field). In the case of bazar fields, also gives the field name.',
'MEDIA_PAGE_VERSION_EXPLANATION' => 'Most recent version of this page calling the file. Cell merged with "Page" for bazar records.',
'MEDIA_PAGE_VERSION_EXPLANATION' => 'Most recent version of this media called by the same page. Cell merged with "Page" for bazar records.',
'MEDIA_INACTIVE_PAGE' => 'Not in any active page',
'MEDIA_USED_ON_PAGE_LATEST_VERSION' => 'Last',
'MEDIA_UNUSED_ON_PAGE_LATEST_VERSION' => 'Older',
'MEDIA_DELETE' => 'Delete file',
'MEDIA_UNABLE_TO_UNDERSTAND_FILE_NAME' => 'Unable to understand file name',
'MEDIA_UNABLE_TO_DELETE_FILE' => 'Unable to delete file',
'MEDIA_FIELD' => 'field',
'MEDIA_EXPLANATIONS' => 'Explanations',
'MEDIA_NOTICES' => 'Notices',
'MEDIA_FOLLOWING_PAGE_CONTAINS_EMPTY_ATTACH' => 'The following Wiki page contains an empty {{attach}}',

));
