DROP TABLE IF EXISTS `#__securitycheck_db`;
CREATE TABLE `#__securitycheck_db` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`Product` VARCHAR(35) NOT NULL,
`Type` VARCHAR(35),
`Vulnerableversion` VARCHAR(10) DEFAULT '---',
`modvulnversion` VARCHAR(2) DEFAULT '==',
`Joomlaversion` VARCHAR(10) DEFAULT 'Notdefined',
`modvulnjoomla` VARCHAR(2) DEFAULT '==',
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
INSERT INTO `#__securitycheck_db` (`product`,`type`,`vulnerableversion`,`modvulnversion`,`Joomlaversion`,`modvulnjoomla`) VALUES 
('Joomla!','core','3.0.0','==','3.0.0','=='),
('com_fss','component','1.9.1.1447','<=','3.0.0','>='),
('com_commedia','component','3.1','<=','3.0.0','>='),
('Joomla!','core','3.0.1','<=','3.0.1','<='),
('com_jnews','component','7.9.1','<','3.0.0','>='),
('com_bch','component','---','==','3.0.0','>='),
('com_aclassif','component','---','==','3.0.0','>='),
('com_rsfiles','component','1.0.0 Rev 11','==','3.0.0','>='),
('Joomla!','core','3.0.2','<=','3.0.0','>='),
('com_jnews','component','8.0.1','<=','3.0.0','>='),
('com_attachments','component','3.1.1','<','3.0.0','>='),
('Joomla!','core','3.1.4','<=','3.0.0','>='),
('com_sectionex','component','2.5.96','<=','3.0.0','>='),
('com_joomsport','component','1.7.1','<','3.0.0','>='),
('Joomla!','core','3.1.5','<=','3.0.0','>='),
('com_flexicontent','component','2.1.3','<=','3.0.0','>='),
('com_mijosearch','component','2.0.1','<=','3.0.0','>='),
('com_acesearch','component','3.0','==','3.0.0','>='),
('com_melody','component','1.6.25','<=','3.0.0','>='),
('com_sexypolling','component','1.0.8','<=','3.0.0','>='),
('com_komento','component','1.7.2','<=','3.0.0','>='),
('com_community','component','2.6','==','3.0.0','>='),
('Joomla!','core','3.2.2','<=','3.0.0','>='),
('com_youtubegallery','component','3.4.0','==','3.0.0','>='),
('com_pbbooking','component','2.4','==','3.0.0','>='),
('com_extplorer','component','2.1.3','==','3.0.0','>='),
('com_freichat','component','3.5','<=','3.0.0','>='),
('com_multicalendar','component','4.0.2','==','3.0.0','>='),
('com_kunena','component','3.0.4','==','3.0.0','>='),
('com_jchat','component','2.2','==','3.0.0','>='),
('com_youtubegallery','component','4.1.7','<=','3.0.0','>='),
('com_kunena','component','3.0.5','==','3.0.0','>='),
('com_spidervideoplayer','component','2.8.3','==','3.0.0','>='),
('com_akeeba','component','3.11.4','<','3.0.0','>='),
('com_spidercalendar','component','3.2.6','<=','3.0.0','>='),
('com_spidercontacts','component','1.3.6','<=','3.0.0','>='),
('com_formmaker','component','3.4.1','<','3,0.0','>='),
('com_facegallery','component','1.0','==','3.0.0','>='),
('com_macgallery','component','1.5','<=','3.0.0','>='),
('Joomla!','core','3.3.4','<','3.0.0','>='),
('com_creativecontactform','component','2.0.0','<=','3.0.0','>='),
('com_xcloner-backupandrestore','component','3.5.1','==','3.0.0','>='),
('com_eventbooking','component','---','==','3.0.0','>='),
('com_hdflvplayer','component','2.1.0.1','<=','3.0.0','>='),
('com_jclassifiedsmanager','component','2.0.0','<','3.0.0','>='),
('com_simplephotogallery','component','1.0','==','3.0.0','>='),
('com_ecommercewd','component','1.2.5','==','3.0.0','>='),
('com_spiderfaq','component','1.1','==','3.0.0','>='),
('com_rand','component','1.5','==','3.0.0','>='),
('com_gallery_wd','component','1.2.5','==','3.0.0','>='),
('com_contactformmaker','component','1.0.1','==','3.0.0','>='),
('com_osproperty','component','2.8.0','<','3.0.0','>='),
('com_eqfullevent','component','1.0.0','<=','3.0.0','>='),
('Joomla!','core','3.4.1','<=','3.0.0','>='),
('com_kunena','component','4.0.2','==','3.0.0','>='),
('com_j2store','component','3.1.6','==','2.5.0,3.0.0','>=,>='),
('com_kunena','component','4.0.3','<=','2.5.0,3.0.0','>=,>='),
('com_helpdeskpro','component','1.4.0','<','2.5.0,3.0.0','>=,>='),
('mod_jshopping_products_wfl','module','4.10.4','<=','3.0.0','>='),
('Joomla!','core','3.4.3','<=','3.0.0','>='),
('com_komento','component','2.0.5','<','3.0.0','>='),
('Joomla!','core','3.4.4','<=','3.0.0','>='),
('com_rpl','component','8.9.2','==','3.0.0','>='),
('com_jnews','component','8.5.1','<=','2.5.0,3.0.0','>=,>='),
('Joomla!','core','3.4.5','<=','3.0.0','>='),
('Joomla!','core','3.4.6','<=','3.0.0','>='),
('com_pricelist','component','2.3.1','==','3.0.0','>='),
('com_poweradmin','component','2.3.0','<=','3.0.0','>='),
('com_easy_youtube_gallery','component','1.0.2','==','3.0.0','>='),
('com_icagenda','component','3.5.15','<=','2.5.0,3.0.0','>=,>='),
('com_jem','component','2.1.15','<=','2.5.0,3.0.0','>=,>='),
('com_extplorer','component','2.1.9','==','2.5.0,3.0.0','>=,>='),
('com_securitycheck','component','2.8.10','<','2.5.0,3.0.0','>=,>='),
('com_securitycheckpro','component','2.8.10','<','2.5.0,3.0.0','>=,>='),
('com_jumi','component','3.0.5','==','2.5.0,3.0.0','>=,>='),
('com_jobgrokapp','component','3.1-1.2.55','==','3.0.0','>='),
('com_joomdoc','component','4.0.3','==','2.5.0,3.0.0','>=,>='),
('com_payplans','component','3.3.6','==','3.0.0','>='),
('com_affiliate','component','1.0.3','==','2.5.0','>='),
('com_maqmahelpdesk','component','4.2.3','==','3.0.0','>='),
('com_affiliatetracker','component','2.0.3','==','2.5.0,3.0.0','>=,>='),
('com_enmasse','component','6.4','<=','3.0.0','>='),
('com_bt_media','component','1.0','==','2.5.0,3.0.0','>=,>='),
('com_publisher','component','3.0.11','==','2.5.0,3.0.0','>=,>='),
('com_services','component','---','==','3.0.0','>='),
('com_branch','component','3.0','==','3.0.0','>='),
('com_zhgooglemap','component','8.1.2.0','==','2.5.0,3.0.0','>=,>='),
('com_guru','component','5.0.1','<=','2.5.0,3.0.0','>=,>='),
('com_gallery','component','1.1.5','==','3.0.0','>='),
('com_catalog','component','1.0.4','==','3.0.0','>='),
('com_slider','component','1.0.9','==','3.0.0','>='),
('Joomla!','core','3.6.0','==','3.0.0','>='),
('com_videoflow','component','1.1.5','<=','2.5.0,3.0.0','>=,>='),
('com_k2','component','2.7.1','<','2.5.0,3.0.0','>=,>='),
('com_registrationpro','component','3.2.12','==','3.0.0','>='),
('com_videogallerylite','component','1.0.9','<=','3.0.0','>='),
('com_eventbooking','component','2.10.1','==','3.0.0','>='),
('com_videogallerylite','component','1.1.1','==','3.0.0','>='),
('com_catalog','component','1.0.7','==','3.0.0','>='),
('com_portfoliogallery','component','1.0.6','==','3.0.0','>='),
('mod_dvfoldercontent','module','1.0.2','==','3.0.0','>='),
('com_googlemaps','component','1.0.9','==','3.0.0','>='),
('com_slider','component','1.1.0','==','3.0.0','>='),
('Joomla!','core','3.6.3','<=','3.0.0','>='),
('com_kunena','component','5.0.3','<=','3.0.0','>='),
('Joomla!','core','3.6.4','<=','3.0.0','>='),
('com_dtregister','component','3.1.12','<','3.0.0','>='),
('com_rpl','component','8.9.2','==','3.0.0','>='),
('com_kunena','component','5.0.5','<','3.0.0','>='),
('com_securitycheck','component','2.8.18','<','2.5.0,3.0.0','>=,>='),
('com_securitycheckpro','component','2.8.18','<','2.5.0,3.0.0','>=,>='),
('com_rsmonials','component','2.2','<=','3.0.0','>='),
('com_altauserpoints','component','1.1','==','3.0.0','>='),
('com_os_cck','component','1.1','==','3.0.0','>='),
('com_aysquiz','component','1.0','==','3.0.0','>='),
('com_monthlyarchive','component','3.6.4','==','3.0.0','>='),
('com_jux_eventon','component','1.0.1','==','3.0.0','>='),
('com_product','component','2.2','==','3.0.0','>='),
('com_advertisementboard','component','3.0.4','==','3.0.0','>='),
('com_simplemembership','component','3.3.3','==','3.0.0','>='),
('com_alfcontact','component','3.2.3','==','3.0.0','>='),
('com_vikrentcar','component','1.11','==','3.0.0','>='),
('com_vikrentitems','component','1.3','==','3.0.0','>='),
('com_vikappointments','component','1.5','==','3.0.0','>='),
('com_jcart','component','2.0','==','3.0.0','>='),
('com_opencart','component','2.0','==','3.0.0','>='),
('com_extrasearch','component','2.2.8','==','3.0.0','>='),
('com_modern_booking','component','1.0','==','3.0.0','>='),
('com_focalpoint','component','1.2.3','==','3.0.0','>='),
('com_jobgroklist','component','3.1-1.2.58','==','3.0.0','>='),
('com_jobgrokapp','component','3.1-1.2.55','==','3.0.0','>='),
('com_joomloc','component','1.3.3','==','3.0.0','>='),
('com_joomloc','component','4.1.3','==','3.0.0','>='),
('com_osservicesbooking','component','2.5.1','==','3.0.0','>='),
('com_osproperty','component','3.0.9','==','3.0.0','>='),
('com_booklibrary','component','3.6.14','==','3.0.0','>='),
('com_vehiclemanager','component','3.9.4','==','3.0.0','>='),
('mod_repowa','module','1.0','==','3.0.0','>='),
('mod_ap_portfolio','module','3.3.1','<=','3.0.0','>='),
('com_realstatemanager','component','3.9.7','==','3.0.0','>='),
('com_booklibrary','component','3.5.4','==','3.0.0','>='),
('com_rsappt-pro','component','4.0.1','==','3.0.0','>='),
('com_modern_booking','component','1.0','==','3.0.0','>='),
('com_directorix','component','1.1.1','==','3.0.0','>='),
('com_jcruisereservation','component','3.0','==','3.0.0','>='),
('com_mostwantedrealestate','component','1.1.0','==','3.0.0','>='),
('com_googlemaplocator','component','4.0','==','3.0.0','>='),
('com_docmanpaypal','component','3.1','==','3.0.0','>='),
('com_eventix','component','1.0','==','3.0.0','>='),
('com_magicdealsweb','component','1.2.0','==','3.0.0','>='),
('com_multitier','component','3.1','==','3.0.0','>='),
('com_userextranet','component','1.3.1','==','3.0.0','>='),
('com_guesser','component','1.0.4','==','3.0.0','>='),
('com_recipe','component','2.2','==','3.0.0','>='),
('com_abstract','component','2.1','==','3.0.0','>='),
('com_k2ajaxsearch','component','2.2','==','3.0.0','>='),
('com_jegridfolio','component','---','==','3.0.0','>='),
('com_jepropertyfinder','component','1.6.3','==','3.0.0','>='),
('Joomla!','core','3.6.5','<=','3.0.0','>='),
('com_jdbexport','component','3.2.10','==','3.0.0','>='),
('com_myportfolio','component','3.0.2','==','3.0.0','>='),
('com_jgrid','component','4.44','==','3.0.0','>='),
('Joomla!','core','3.7.0','==','3.0.0','>='),
('com_videoflow','component','1.2.0','==','3.0.0','>='),
('com_kunena','component','5.0.9','<','3.0.0','>='),
('com_payage','component','2.0.6','<','3.0.0','>='),
('com_hikashop','component','3.1.0','==','3.0.0','>='),
('Joomla!','core','3.7.2','<=','3.0.0','>='),
('Joomla!','core','3.6.5','<=','3.0.0','>='),
('Joomla!','core','3.6.3','<=','3.0.0','>='),
('com_ccnewsletter','component','2.1.9','==','3.0.0','>='),
('com_extplorer','component','2.1.9','<=','3.0.0','>='),
('com_calendarplanner','component','1.0.1','==','3.0.0','>='),
('mod_byebyepassword','module','1.0.4','<=','3.0.0','>='),
('com_zcalendar','component','4.3.6','<=','3.0.0','>='),
('com_ccnewsletter','component','2.1.9','<=','3.0.0','>='),
('com_lmsking','component','3.2.3.19','<=','3.0.0','>='),
('com_twitchtv','component','1.1','<=','3.0.0','>='),
('com_kissgallery','component','1.0.0','<=','3.0.0','>='),
('com_registrationpro','component','4.1.3','==','3.0.0','>='),
('com_rpl','component','1.0.0','>=','3.0.0','>='),
('com_simgenealogy','component','2.1.8','<','3.0.0','>='),
('com_payplans','component','3.6.3','<','3.0.0','>='),
('com_joomanager','component','2.0.0','<=','3.0.0','>='),
('Joomla!','core','3.7.5','<=','3.0.0','>='),
('com_userextranet','component','1.3.2','<=','3.0.0','>='),
('com_surveyforce','component','3.2.4','==','3.0.0','>='),
('com_spmoviedb','component','1.3','==','3.0.0','>='),
('com_ns_downloadshop','component','2.2.6','==','3.0.0','>='),
('com_zhyandexmap','component','6.1.1.0','==','3.0.0','>='),
('com_price_alert','component','3.0.4','<=','3.0.0','>='),
('com_ajaxquiz','component','1.8.0','==','3.0.0','>='),
('plugin_googlemap3','plugin','3.5','==','3.0.0','>='),
('com_hdwplayer','component','4.0.0','<=','3.0.0','>='),
('com_jsjobs','component','1.1.8','==','3.0.0','>='),
('Joomla!','core','3.8.1','<=','3.0.0','>='),
('com_virtuemart','component','3.2.4','==','3.0.0','>='),
('com_jbuildozer','component','1.4.1','==','3.0.0','>='),
('com_jevideogallery','component','3.0.5','==','3.0.0','>='),
('com_nge','component','2.1.0','==','3.0.0','>='),
('com_bigfileuploader','component','1.0.2','==','3.0.0','>='),
('com_bookpro','component','1.0','==','3.0.0','>='),
('com_b2jcontact','component','2.1.14','<=','3.0.0','>='),
('com_myproject','component','2.0','==','3.0.0','>='),
('com_userbench','component','1.0','==','3.0.0','>='),
('com_easydiscuss','component','4.0.20','<=','3.0.0','>='),
('com_guru','component','5.0.15','<=','3.0.0','>='),
('com_ajaxquiz','component','2.0','<=','3.0.0','>='),
('com_enmasse','component','1.0','>=','3.0.0','>='),
('com_simplephotogallery','component','3.5.0','<=','3.0.0','>='),
('com_jtagmembersdirectory','component','5.3.7','==','3.0.0','>='),
('com_jssupportticket','component','1.1.0','==','3.0.0','>='),
('Joomla!','core','3.8.3','<=','3.0.0','>='),
('com_jce','component','2.6.25','==','3.0.0','>='),
('com_jssupportticket','component','1.1.0','==','3.0.0','>='),
('com_jsjobs','component','1.1.9','<=','3.0.0','>='),
('com_ccnewsletter','component','2.2.2','<=','3.0.0','>='),
('com_jimtawl','component','2.2.6','==','3.0.0','>='),
('com_zhgooglemap','component','8.4.0.0','<=','3.0.0','>='),
('com_zhyandexmap','component','6.2.1.0','<=','3.0.0','>='),
('com_zhbaidumap','component','3.0.0.1','<=','3.0.0','>='),
('com_jsptickets','component','1.1','<=','3.0.0','>='),
('com_solidres','component','2.5','<=','3.0.0','>='),
('com_timetable','component','1.6','<=','3.0.0','>='),
('com_jsplocation','component','2.4','<=','3.0.0','>='),
('com_smartshoutbox','component','2.9.5','<=','3.0.0','>='),
('plg_sige','plugin','3.2.3','<=','3.0.0','>='),
('com_saxumastro','component','4.0.14','<=','3.0.0','>='),
('com_saxumnumerology','component','3.0.4','<=','3.0.0','>='),
('com_saxumpicker','component','3.2.10','<=','3.0.0','>='),
('com_biblestudy','component','9.1.1','<=','3.0.0','>='),
('com_socialpinboard','component','2.0','==','3.0.0','>='),
('com_osproperty','component','3.12.8','<=','3.0.0','>='),
('com_realpin','component','1.5.04','<=','3.0.0','>='),
('com_media_library','component','4.0.12','<=','3.0.0','>='),
('com_jsautoz','component','1.0.9','<=','3.0.0','>='),
('com_jticketing','component','2.0.16','<=','3.0.0','>='),
('com_invitex','component','3.0.5','<=','3.0.0','>='),
('com_abook','component','3.1.3','<=','3.0.0','>='),
('com_jmsmusic','component','1.1.1','<=','3.0.0','>='),
('com_formmaker','component','3.6.14','<=','3.0.0','>='),
('com_jgive','component','2.0.9','<=','3.0.0','>='),
('com_ekrishta','component','2.9','<=','3.0.0','>='),
('com_prayercenter','component','3.0.2','<=','3.0.0','>='),
('com_cwtags','component','2.0.8','<=','3.0.0','>='),
('com_squadmanagement','component','1.0.3','<=','3.0.0','>='),
('com_neorecruit','component','4.2.1','<=','3.0.0','>='),
('com_checklist','component','1.1.1.003','<=','3.0.0','>='),
('com_simplecalendar','component','3.1.9','<=','3.0.0','>='),
('com_bookpro','component','2.3','==','3.0.0','>='),
('com_dtracker','component','3.0','==','3.0.0','>='),
('com_jquickcontact','component','1.3.2.3','<=','3.0.0','>='),
('com_fastball','component','10.0.0','<=','3.0.0','>='),
('com_dtregister','component','3.2.7','<=','3.0.0','>='),
('com_jomestate','component','3.7','<=','3.0.0','>='),
('Joomla!','core','3.8.5','<=','3.0.0','>='),
('com_kunena','component','5.0.13','<=','3.0.0','>='),
('com_gmap','component','4.2.3','<=','3.0.0','>='),
('com_attachments','component','3.2.5','<=','3.0.0','>='),
('com_visualcalendar','component','3.1.5','<=','3.0.0','>='),
('com_nexevocontact','component','1.0.1','<=','3.0.0','>='),
('Joomla!','core','3.8.7','<=','3.0.0','>='),
('com_ekrishta','component','2.10','==','3.0.0','>='),
('com_cb','component','2.1.4','<=','3.0.0','>='),
('Joomla!','core','3.8.8','<=','3.0.0','>='),
('com_medialibrary','component','4.0.12','<=','3.0.0','>='),
('com_advertisementboard','component','3.1.0','<=','3.0.0','>='),
('com_kunena','component','5.1.1','<=','3.0.0','>='),
('com_jbusinessdirectory','component','4.9.3','<=','3.0.0','>='),
('com_jcomments','component','3.0.5','<=','3.0.0','>='),
('com_mobile','component','2.1.24','==','3.0.0','>='),
('Joomla!','core','3.8.11','<=','3.0.0','>='),
('com_virtuemart_magiczoomplus','component','4.9.4','<=','3.0.0','>='),
('com_magiczoomplus','component','3.3.4','<=','3.0.0','>='),
('com_timetableschedule','component','3.6.8','==','3.0.0','>='),
('com_articleman','component','4.3.9','==','3.0.0','>='),
('com_aindexdictionaries','component','1.0','==','3.0.0','>='),
('com_rbids','component','4.3.8','==','3.0.0','>='),
('com_collectionfactory','component','4.1.9','==','3.0.0','>='),
('com_swapfactory','component','2.2.1','==','3.0.0','>=').
('com_extroform','component','2.1.5','==','3.0.0','>=').
('com_dutchfactory','component','2.0.2','==','3.0.0','>='),
('com_socialfactory','component','3.8.3','==','3.0.0','>='),
('com_jobsfactory','component','2.0.4','==','3.0.0','>='),
('com_questions','component','1.4.3','==','3.0.0','>='),
('com_pennyfactory','component','2.0.4','==','3.0.0','>='),
('com_rafflefactory','component','3.5.2','==','3.0.0','>='),
('com_pofos','component','1.6.1','==','3.0.0','>='),
('com_auctionfactory','component','4.5.5','==','3.0.0','>='),
('com_microdealfactory','component','2.4.0','==','3.0.0','>='),
('jckeditor','plugin','6.4.4','==','3.0.0','>='),
('cwattachments','plugin','1.0.6','==','3.0.0','>='),
('Joomla!','core','3.8.12','<=','3.0.0','>='),
('com_kunena','component','5.1.5','<','3.0.0','>='),
('com_jimtawl','component','2.2.7','==','3.0.0','>=');

CREATE TABLE IF NOT EXISTS `#__securitycheckpro_update_database` (
`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`version` VARCHAR(10),
`last_check` DATETIME,
`message` VARCHAR(300),
PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
INSERT INTO `#__securitycheckpro_update_database` (`version`) VALUES ('1.1.27');