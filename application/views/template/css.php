<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	:root {
	  --warna-sidebar-border : #860000;
	  --warna-sidebar : #ad00007a;
	  --warna-sidebar-bg : #ededed;
	  --warna-header :#9b1919;
	  --warna-btn-header : #860000;
	  --warna-btn-header-hover : #860000b3;
	}

	@media only screen and (min-width: 769px) {
		.foot-cent-xs{text-align:right;}
		.sidebar-mystyle{background: #ecf0f5 !important;}.skin-blue .sidebar-menu > li.header{background: #ecf0f5 !important;}.skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a{background: var(--warna-sidebar) !important;font-weight: bold;}.skin-blue .sidebar-menu > li > .treeview-menu{background: var(--warna-sidebar) !important;}.skin-blue .sidebar-menu .treeview-menu > li > a{color: var(--warna-sidebar-bg) !important;}.skin-blue .sidebar-menu .treeview-menu > li.active > a, .skin-blue .sidebar-menu .treeview-menu > li > a:hover{color: #fff !important;font-weight: bold;}
	}
	@media only screen and (max-width: 768px) {
		.main-header{position:fixed !important;width:100%;top:-50px;}
		.content-wrapper{margin-top:100px;}
		.foot-cent-xs{text-align:center;}
		.sidebar-mystyle{background: var(--warna-header) !important;border-right-style: dashed;border-color: var(--warna-sidebar);border-width: 2px;}.skin-blue .sidebar-menu > li.header{font-weight: bold;color: #fff !important;background: var(--warna-btn-header-hover) !important;}.skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a{background: var(--warna-btn-header-hover) !important}.skin-blue .sidebar-menu > li > .treeview-menu{background: var(--warna-btn-header-hover) !important;}.skin-blue .sidebar-menu .treeview-menu > li.active > a, .skin-blue .sidebar-menu .treeview-menu > li > a:hover{font-weight: bold;}.skin-blue .sidebar-menu .treeview-menu > li > a{color: #ededed !important;}.skin-blue .sidebar a{color: #ededed !important}.dropmenu{right: 0px !important;}.dropmenu > li > a {color: #555 !important;}.dropmenu > li > a:hover {color: #fff !important;}
	}
	.teks-upper{text-transform: uppercase;}
	.select2-container .select2-selection--single{height:34px !important;}
	.no-right{padding-right:0px !important;}
