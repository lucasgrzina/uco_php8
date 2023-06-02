/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
    var path = CKEDITOR.basePath.split('/');
    
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    var path = CKEDITOR.basePath.split('/');
    //console.debug(path);
    //path[ path.length-2 ] = 'upload_ck_image';
    path.length = path.indexOf('plugins');
	path[path.length] = 'api/upload_ck_image';
    config.filebrowserUploadUrl = path.join('/').replace(/\/+$/, '');     
    config.filebrowserImageUploadUrl = 	config.filebrowserUploadUrl;
    config.filebrowserImageUploadUrl = 	config.filebrowserUploadUrl;
    config.stylesSet = 'service_title';
    config.allowedContent = true;
    console.log(config.allowedContent);
    	
};
console.log(CKEDITOR.stylesSet);
CKEDITOR.stylesSet.add( 'service_title',
		[
			// Block-level styles
			{ name : 'Inner title', element : 'p', attributes : { 'class' : 'subtitle' } },
			{ name : 'Service description', element : 'p', attributes : { 'class' : 'text' } }
			
]);
