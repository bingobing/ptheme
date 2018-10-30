// JavaScript Document

jQuery(document).ready(function($) {
	
	"use strict";
	
	// admin nav
	$('.pnq-admin-menu li:last').addClass('pnq-admin-menu-last');
	
	// wp color picker
	$('.pnq-input-color').wpColorPicker();
	
	// datepicker
	$('.pnq-input-date').datepicker({ dateFormat: "yy-mm-dd" });
	
	// section help
	$('.pnq-section-help').click(function() {
		$(this).parent().find('.pnq-section-description').fadeToggle();
		return false;
	});
});