/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	 config.uiColor = '#AADC6E';
        //config.toolbar = 'Basic',
          config.uiColor = '#9AB8F3'
           config.width = '700';
            config.height = '300';

        CKEDITOR.config.toolbar = [['Bold','Italic','Underline','Strike','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Styles','Font','FontSize','-','-','NumberedList','BulletedList','-','-','TextColor','BGColor','Table']];


};

