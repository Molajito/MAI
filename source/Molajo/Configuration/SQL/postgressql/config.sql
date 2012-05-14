#
# Configuration
#

/* TABLE */

/* 100 EXTENSION_OPTION_ID_TABLE */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 100, '', '', 0),
(1, 100, '__common', '__common', 1);

/* 200 EXTENSION_OPTION_ID_FIELDS */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 200, '', '', 0),
(1, 200, 'access', 'FIELD_ACCESS_LABEL', 1),
(1, 200, 'alias', 'FIELD_ALIAS_LABEL', 2),
(1, 200, 'catalog_id', 'FIELD_CATALOG_ID_LABEL', 3),
(1, 200, 'attribs', 'FIELD_ATTRIBS_LABEL', 4),
(1, 200, 'category_id', 'FIELD_CATID_LABEL', 5),
(1, 200, 'checked_out', 'FIELD_CHECKED_OUT_LABEL', 6),
(1, 200, 'checked_out_time', 'FIELD_CHECKED_OUT_TIME_LABEL', 7),
(1, 200, 'component_id', 'FIELD_COMPONENT_ID_LABEL', 8),
(1, 200, 'content_table', 'FIELD_CONTENT_TABLE_LABEL', 9),
(1, 200, 'content_email_address', 'FIELD_CONTENT_EMAIL_ADDRESS_LABEL', 10),
(1, 200, 'content_file', 'FIELD_CONTENT_FILE_LABEL', 11),
(1, 200, 'content_link', 'FIELD_CONTENT_LINK_LABEL', 12),
(1, 200, 'content_numeric_value', 'FIELD_CONTENT_NUMERIC_VALUE_LABEL', 13),
(1, 200, 'content_text', 'FIELD_CONTENT_TEXT_LABEL', 14),
(1, 200, 'catalog_type_id', 'FIELD_CATALOG_TYPE_LABEL', 15),
(1, 200, 'created', 'FIELD_CREATED_LABEL', 16),
(1, 200, 'created_by', 'FIELD_CREATED_BY_LABEL', 17),
(1, 200, 'created_by_alias', 'FIELD_CREATED_BY_ALIAS_LABEL', 18),
(1, 200, 'created_by_email', 'FIELD_CREATED_BY_EMAIL_LABEL', 19),
(1, 200, 'created_by_ip_address', 'FIELD_CREATED_BY_IP_ADDRESS_LABEL', 20),
(1, 200, 'created_by_referer', 'FIELD_CREATED_BY_REFERER_LABEL', 21),
(1, 200, 'created_by_website', 'FIELD_CREATED_BY_WEBSITE_LABEL', 22),
(1, 200, 'featured', 'FIELD_FEATURED_LABEL', 23),
(1, 200, 'id', 'FIELD_ID_LABEL', 24),
(1, 200, 'language', 'FIELD_LANGUAGE_LABEL', 25),
(1, 200, 'level', 'FIELD_LEVEL_LABEL', 26),
(1, 200, 'lft', 'FIELD_LFT_LABEL', 27),
(1, 200, 'metadata', 'FIELD_METADATA_LABEL', 28),
(1, 200, 'metadesc', 'FIELD_METADESC_LABEL', 29),
(1, 200, 'metakey', 'FIELD_METAKEY_LABEL', 30),
(1, 200, 'author', 'FIELD_AUTHOR_LABEL', 31),
(1, 200, 'content_rights', 'FIELD_RIGHTS_LABEL', 32),
(1, 200, 'robots', 'FIELD_ROBOTS_LABEL', 33),
(1, 200, 'modified', 'FIELD_MODIFIED_LABEL', 34),
(1, 200, 'modified_by', 'FIELD_MODIFIED_BY_LABEL', 35),
(1, 200, 'ordering', 'FIELD_ORDERING_LABEL', 36),
(1, 200, 'stop_publishing_datetime', 'FIELD_PUBLISH_DOWN_LABEL', 37),
(1, 200, 'start_publishing_datetime', 'FIELD_PUBLISH_UP_LABEL', 38),
(1, 200, 'rgt', 'FIELD_RGT_LABEL', 39),
(1, 200, 'state', 'FIELD_STATUS_LABEL', 40),
(1, 200, 'status_prior_to_version', 'FIELD_STATUS_PRIOR_TO_VERSION_LABEL', 41),
(1, 200, 'stickied', 'FIELD_STICKIED_LABEL', 42),
(1, 200, 'user_default', 'FIELD_USER_DEFAULT_LABEL', 43),
(1, 200, 'category_default', 'FIELD_CATEGORY_DEFAULT_LABEL', 44),
(1, 200, 'title', 'FIELD_TITLE_LABEL', 45),
(1, 200, 'subtitle', 'FIELD_SUBTITLE_LABEL', 46),
(1, 200, 'version', 'FIELD_VERSION_LABEL', 47),
(1, 200, 'version_of_id', 'FIELD_VERSION_OF_ID_LABEL', 48);

/* 210 EXTENSION_OPTION_ID_PUBLISH_FIELDS */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 210, '', '', 0),
(1, 210, 'access', 'FIELD_ACCESS_LABEL', 1),
(1, 210, 'featured', 'FIELD_FEATURED_LABEL', 2),
(1, 210, 'ordering', 'FIELD_ORDERING_LABEL', 3),
(1, 210, 'stop_publishing_datetime', 'FIELD_PUBLISH_DOWN_LABEL', 4),
(1, 210, 'start_publishing_datetime', 'FIELD_PUBLISH_UP_LABEL', 5),
(1, 210, 'state', 'FIELD_STATUS_LABEL', 6),
(1, 210, 'stickied', 'FIELD_STICKIED_LABEL', 7);

/* 220 EXTENSION_OPTION_ID_JSON_FIELDS */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 220, '', '', 0),
(1, 220, 'attribs', 'FIELD_ATTRIBS_LABEL', 1),
(1, 220, 'metadata', 'FIELD_METADATA_LABEL', 2),
(1, 220, 'parameters', 'FIELD_PARAMETERS_LABEL', 3);

/* 230 EXTENSION_OPTION_ID_CATALOG_TYPES */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 230, '', '', 0),
(1, 230, 'catalog_type_id', 'Content Type', 1);

/* 250 EXTENSION_OPTION_ID_STATUS */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 250, '', '', 0),
(1, 250, '2', 'OPTION_STATUS_ARCHIVED', 1),
(1, 250, '1', 'OPTION_STATUS_PUBLISHED', 2),
(1, 250, '0', 'OPTION_STATUS_UNPUBLISHED', 3),
(1, 250, '-1', 'OPTION_STATUS_TRASHED', 4),
(1, 250, '-2', 'OPTION_STATUS_SPAMMED', 5),
(1, 250, '-10', 'OPTION_STATUS_VERSION', 6);

/* USER INTERFACE */

/* 300 EXTENSION_OPTION_ID_TOOLBAR_LIST */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 300, '', '', 0),
(1, 300, 'archive', 'OPTION_TOOLBAR_BUTTON_ARCHIVE', 1),
(1, 300, 'checkin', 'OPTION_TOOLBAR_BUTTON_CHECKIN', 2),
(1, 300, 'delete', 'OPTION_TOOLBAR_BUTTON_DELETE', 3),
(1, 300, 'edit', 'OPTION_TOOLBAR_BUTTON_EDIT', 4),
(1, 300, 'feature', 'OPTION_TOOLBAR_BUTTON_FEATURE', 5),
(1, 300, 'help', 'OPTION_TOOLBAR_BUTTON_HELP', 6),
(1, 300, 'new', 'OPTION_TOOLBAR_BUTTON_NEW', 7),
(1, 300, 'options', 'OPTION_TOOLBAR_BUTTON_OPTIONS', 8),
(1, 300, 'publish', 'OPTION_TOOLBAR_BUTTON_PUBLISH', 9),
(1, 300, 'restore', 'OPTION_TOOLBAR_BUTTON_RESTORE', 10),
(1, 300, 'separator', 'OPTION_TOOLBAR_BUTTON_SEPARATOR', 11),
(1, 300, 'spam', 'OPTION_TOOLBAR_BUTTON_SPAM', 12),
(1, 300, 'sticky', 'OPTION_TOOLBAR_BUTTON_STICKY', 13),
(1, 300, 'trash', 'OPTION_TOOLBAR_BUTTON_TRASH', 14),
(1, 300, 'unpublish', 'OPTION_TOOLBAR_BUTTON_UNPUBLISH', 15);

/* 310 EXTENSION_OPTION_ID_TOOLBAR_EDIT */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 310, '', '', 0),
(1, 310, 'apply', 'OPTION_TOOLBAR_BUTTON_APPLY', 1),
(1, 310, 'close', 'OPTION_TOOLBAR_BUTTON_CLOSE', 2),
(1, 310, 'help', 'OPTION_TOOLBAR_BUTTON_HELP', 3),
(1, 310, 'restore', 'OPTION_TOOLBAR_BUTTON_RESTORE', 4),
(1, 310, 'save', 'OPTION_TOOLBAR_BUTTON_SAVE', 5),
(1, 310, 'saveandnew', 'OPTION_TOOLBAR_BUTTON_SAVEANDNEW', 6),
(1, 310, 'saveascopy', 'OPTION_TOOLBAR_BUTTON_SAVEASCOPY', 7),
(1, 310, 'separator', 'OPTION_TOOLBAR_BUTTON_SEPARATOR', 8);

/* 320 EXTENSION_OPTION_ID_SUBMENU */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 320, '', '', 0),
(1, 320, 'category', 'MANAGER_SUBMENU_CATEGORY', 1),
(1, 320, 'default', 'MANAGER_SUBMENU_DEFAULT', 2),
(1, 320, 'featured', 'MANAGER_SUBMENU_FEATURED', 3),
(1, 320, 'versions', 'MANAGER_SUBMENU_VERSIONS', 4),
(1, 320, 'stickied', 'MANAGER_SUBMENU_STICKIED', 5),
(1, 320, 'unpublished', 'MANAGER_SUBMENU_UNPUBLISHED', 6);

/* 330 EXTENSION_OPTION_ID_FILTERS */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 330, '', '', 0),
(1, 330, 'access', 'MANAGER_OPTION_FILTER_ACCESS', 1),
(1, 330, 'alias', 'MANAGER_OPTION_FILTER_ALIAS', 2),
(1, 330, 'created_by', 'MANAGER_OPTION_FILTER_AUTHOR', 3),
(1, 330, 'category_id', 'MANAGER_OPTION_FILTER_CATEGORY', 4),
(1, 330, 'catalog_type_id', 'MANAGER_OPTION_FILTER_CATALOG_TYPE', 5),
(1, 330, 'created', 'MANAGER_OPTION_FILTER_CREATE_DATE', 6),
(1, 330, 'featured', 'MANAGER_OPTION_FILTER_FEATURED', 7),
(1, 330, 'language', 'MANAGER_OPTION_FILTER_LANGUAGE', 9),
(1, 330, 'modified', 'MANAGER_OPTION_FILTER_UPDATE_DATE', 10),
(1, 330, 'start_publishing_datetime', 'MANAGER_OPTION_FILTER_PUBLISH_DATE', 11),
(1, 330, 'state', 'MANAGER_OPTION_FILTER_STATUS', 12),
(1, 330, 'stickied', 'MANAGER_OPTION_FILTER_STICKIED', 13),
(1, 330, 'title', 'MANAGER_OPTION_FILTER_TITLE', 14),
(1, 330, 'subtitle', 'MANAGER_OPTION_FILTER_SUBTITLE', 15);

/* 340 EXTENSION_OPTION_ID_EDITOR_BUTTONS */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 340, '', '', 0),
(1, 340, 'article', 'MANAGER_EDITOR_BUTTON_ARTICLE', 1),
(1, 340, 'audio', 'MANAGER_EDITOR_BUTTON_AUDIO', 2),
(1, 340, 'file', 'MANAGER_EDITOR_BUTTON_FILE', 3),
(1, 340, 'gallery', 'MANAGER_EDITOR_BUTTON_GALLERY', 4),
(1, 340, 'image', 'MANAGER_EDITOR_BUTTON_IMAGE', 5),
(1, 340, 'pagebreak', 'MANAGER_EDITOR_BUTTON_PAGEBREAK', 6),
(1, 340, 'readmore', 'MANAGER_EDITOR_BUTTON_READMORE', 7),
(1, 340, 'video', 'MANAGER_EDITOR_BUTTON_VIDEO', 8);

/* MIME from ftp://ftp.iana.org/assignments/media-types/ */

/* 400 EXTENSION_OPTION_ID_MIMES_AUDIO */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 400, '', '', 0),
(1, 400, 'rtp-enc-aescm128', 'rtp-enc-aescm128', 1),
(1, 400, 'sp-midi', 'sp-midi', 2),
(1, 400, 'vnd.3gpp.iufp', 'vnd.3gpp.iufp', 3),
(1, 400, 'vnd.4SB', 'vnd.4SB', 4),
(1, 400, 'vnd.CELP', 'vnd.CELP', 5),
(1, 400, 'vnd.audiokoz', 'vnd.audiokoz', 6),
(1, 400, 'vnd.cisco.nse', 'vnd.cisco.nse', 7),
(1, 400, 'vnd.cmles.radio-events', 'vnd.cmles.radio-events', 8),
(1, 400, 'vnd.cns.anp1', 'vnd.cns.anp1', 9),
(1, 400, 'vnd.cns.inf1', 'vnd.cns.inf1', 10),
(1, 400, 'vnd.dece.audio', 'vnd.dece.audio', 11),
(1, 400, 'vnd.digital-winds', 'vnd.digital-winds', 12),
(1, 400, 'vnd.dlna.adts', 'vnd.dlna.adts', 13),
(1, 400, 'vnd.dolby.heaac.1', 'vnd.dolby.heaac.1', 14),
(1, 400, 'vnd.dolby.heaac.2', 'vnd.dolby.heaac.2', 15),
(1, 400, 'vnd.dolby.mlp', 'vnd.dolby.mlp', 16),
(1, 400, 'vnd.dolby.mps', 'vnd.dolby.mps', 17),
(1, 400, 'vnd.dolby.pl2', 'vnd.dolby.pl2', 18),
(1, 400, 'vnd.dolby.pl2x', 'vnd.dolby.pl2x', 19),
(1, 400, 'vnd.dolby.pl2z', 'vnd.dolby.pl2z', 20),
(1, 400, 'vnd.dolby.pulse.1', 'vnd.dolby.pulse.1', 21),
(1, 400, 'vnd.dra', 'vnd.dra', 22),
(1, 400, 'vnd.dts', 'vnd.dts', 23),
(1, 400, 'vnd.dts.hd', 'vnd.dts.hd', 24),
(1, 400, 'vnd.dvb.file', 'vnd.dvb.file', 25),
(1, 400, 'vnd.everad.plj', 'vnd.everad.plj', 26),
(1, 400, 'vnd.hns.audio', 'vnd.hns.audio', 27),
(1, 400, 'vnd.lucent.voice', 'vnd.lucent.voice', 28),
(1, 400, 'vnd.ms-playready.media.pya', 'vnd.ms-playready.media.pya', 29),
(1, 400, 'vnd.nokia.mobile-xmf', 'vnd.nokia.mobile-xmf', 30),
(1, 400, 'vnd.nortel.vbk', 'vnd.nortel.vbk', 31),
(1, 400, 'vnd.nuera.ecelp4800', 'vnd.nuera.ecelp4800', 32),
(1, 400, 'vnd.nuera.ecelp7470', 'vnd.nuera.ecelp7470', 33),
(1, 400, 'vnd.nuera.ecelp9600', 'vnd.nuera.ecelp9600', 34),
(1, 400, 'vnd.octel.sbc', 'vnd.octel.sbc', 35),
(1, 400, 'vnd.qcelp', 'vnd.qcelp', 36),
(1, 400, 'vnd.rhetorex.32kadpcm', 'vnd.rhetorex.32kadpcm', 37),
(1, 400, 'vnd.rip', 'vnd.rip', 38),
(1, 400, 'vnd.sealedmedia.softseal-mpeg', 'vnd.sealedmedia.softseal-mpeg', 39),
(1, 400, 'vnd.vmx.cvsd', 'vnd.vmx.cvsd', 40);

/* 410 EXTENSION_OPTION_ID_MIMES_IMAGE */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 410, '', '', 0),
(1, 410, 'cgm', 'cgm', 1),
(1, 410, 'jp2', 'jp2', 2),
(1, 410, 'jpm', 'jpm', 3),
(1, 410, 'jpx', 'jpx', 4),
(1, 410, 'naplps', 'naplps', 5),
(1, 410, 'png', 'png', 6),
(1, 410, 'prs.btif', 'prs.btif', 7),
(1, 410, 'prs.pti', 'prs.pti', 8),
(1, 410, 'vnd-djvu', 'vnd-djvu', 9),
(1, 410, 'vnd-svf', 'vnd-svf', 10),
(1, 410, 'vnd-wap-wbmp', 'vnd-wap-wbmp', 11),
(1, 410, 'vnd.adobe.photoshop', 'vnd.adobe.photoshop', 12),
(1, 410, 'vnd.cns.inf2', 'vnd.cns.inf2', 13),
(1, 410, 'vnd.dece.graphic', 'vnd.dece.graphic', 14),
(1, 410, 'vnd.dvb.subtitle', 'vnd.dvb.subtitle', 15),
(1, 410, 'vnd.dwg', 'vnd.dwg', 16),
(1, 410, 'vnd.dxf', 'vnd.dxf', 17),
(1, 410, 'vnd.fastbidsheet', 'vnd.fastbidsheet', 18),
(1, 410, 'vnd.fpx', 'vnd.fpx', 19),
(1, 410, 'vnd.fst', 'vnd.fst', 20),
(1, 410, 'vnd.fujixerox.edmics-mmr', 'vnd.fujixerox.edmics-mmr', 21),
(1, 410, 'vnd.fujixerox.edmics-rlc', 'vnd.fujixerox.edmics-rlc', 22),
(1, 410, 'vnd.globalgraphics.pgb', 'vnd.globalgraphics.pgb', 23),
(1, 410, 'vnd.microsoft.icon', 'vnd.microsoft.icon', 24),
(1, 410, 'vnd.mix', 'vnd.mix', 25),
(1, 410, 'vnd.ms-modi', 'vnd.ms-modi', 26),
(1, 410, 'vnd.net-fpx', 'vnd.net-fpx', 27),
(1, 410, 'vnd.radiance', 'vnd.radiance', 28),
(1, 410, 'vnd.sealed-png', 'vnd.sealed-png', 29),
(1, 410, 'vnd.sealedmedia.softseal-gif', 'vnd.sealedmedia.softseal-gif', 30),
(1, 410, 'vnd.sealedmedia.softseal-jpg', 'vnd.sealedmedia.softseal-jpg', 31),
(1, 410, 'vnd.xiff', 'vnd.xiff', 32);

/* 420 EXTENSION_OPTION_ID_MIMES_TEXT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 420, '', '', 0),
(1, 420, 'n3', 'n3', 1),
(1, 420, 'prs.fallenstein.rst', 'prs.fallenstein.rst', 2),
(1, 420, 'prs.lines.tag', 'prs.lines.tag', 3),
(1, 420, 'rtf', 'rtf', 4),
(1, 420, 'rtp-enc-aescm128', 'rtp-enc-aescm128', 5),
(1, 420, 'tab-separated-values', 'tab-separated-values', 6),
(1, 420, 'turtle', 'turtle', 7),
(1, 420, 'vnd-curl', 'vnd-curl', 8),
(1, 420, 'vnd.DMClientScript', 'vnd.DMClientScript', 9),
(1, 420, 'vnd.IPTC.NITF', 'vnd.IPTC.NITF', 10),
(1, 420, 'vnd.IPTC.NewsML', 'vnd.IPTC.NewsML', 11),
(1, 420, 'vnd.abc', 'vnd.abc', 12),
(1, 420, 'vnd.curl', 'vnd.curl', 13),
(1, 420, 'vnd.dvb.subtitle', 'vnd.dvb.subtitle', 14),
(1, 420, 'vnd.esmertec.theme-descriptor', 'vnd.esmertec.theme-descriptor', 15),
(1, 420, 'vnd.fly', 'vnd.fly', 16),
(1, 420, 'vnd.fmi.flexstor', 'vnd.fmi.flexstor', 17),
(1, 420, 'vnd.graphviz', 'vnd.graphviz', 18),
(1, 420, 'vnd.in3d.3dml', 'vnd.in3d.3dml', 19),
(1, 420, 'vnd.in3d.spot', 'vnd.in3d.spot', 20),
(1, 420, 'vnd.latex-z', 'vnd.latex-z', 21),
(1, 420, 'vnd.motorola.reflex', 'vnd.motorola.reflex', 22),
(1, 420, 'vnd.ms-mediapackage', 'vnd.ms-mediapackage', 23),
(1, 420, 'vnd.net2phone.commcenter.command', 'vnd.net2phone.commcenter.command', 24),
(1, 420, 'vnd.si.uricatalogue', 'vnd.si.uricatalogue', 25),
(1, 420, 'vnd.sun.j2me.app-descriptor', 'vnd.sun.j2me.app-descriptor', 26),
(1, 420, 'vnd.trolltech.linguist', 'vnd.trolltech.linguist', 27),
(1, 420, 'vnd.wap-wml', 'vnd.wap-wml', 28),
(1, 420, 'vnd.wap.si', 'vnd.wap.si', 29),
(1, 420, 'vnd.wap.wmlscript', 'vnd.wap.wmlscript', 30);

/* 430 EXTENSION_OPTION_ID_MIMES_VIDEO */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 430, '', '', 0),
(1, 430, 'jpm', 'jpm', 1),
(1, 430, 'mj2', 'mj2', 2),
(1, 430, 'quicktime', 'quicktime', 3),
(1, 430, 'rtp-enc-aescm128', 'rtp-enc-aescm128', 4),
(1, 430, 'vnd-mpegurl', 'vnd-mpegurl', 5),
(1, 430, 'vnd-vivo', 'vnd-vivo', 6),
(1, 430, 'vnd.CCTV', 'vnd.CCTV', 7),
(1, 430, 'vnd.dece-mp4', 'vnd.dece-mp4', 8),
(1, 430, 'vnd.dece.hd', 'vnd.dece.hd', 9),
(1, 430, 'vnd.dece.mobile', 'vnd.dece.mobile', 10),
(1, 430, 'vnd.dece.pd', 'vnd.dece.pd', 11),
(1, 430, 'vnd.dece.sd', 'vnd.dece.sd', 12),
(1, 430, 'vnd.dece.video', 'vnd.dece.video', 13),
(1, 430, 'vnd.directv-mpeg', 'vnd.directv-mpeg', 14),
(1, 430, 'vnd.directv.mpeg-tts', 'vnd.directv.mpeg-tts', 15),
(1, 430, 'vnd.dvb.file', 'vnd.dvb.file', 16),
(1, 430, 'vnd.fvt', 'vnd.fvt', 17),
(1, 430, 'vnd.hns.video', 'vnd.hns.video', 18),
(1, 430, 'vnd.iptvforum.1dparityfec-1010', 'vnd.iptvforum.1dparityfec-1010', 19),
(1, 430, 'vnd.iptvforum.1dparityfec-2005', 'vnd.iptvforum.1dparityfec-2005', 20),
(1, 430, 'vnd.iptvforum.2dparityfec-1010', 'vnd.iptvforum.2dparityfec-1010', 21),
(1, 430, 'vnd.iptvforum.2dparityfec-2005', 'vnd.iptvforum.2dparityfec-2005', 22),
(1, 430, 'vnd.iptvforum.ttsavc', 'vnd.iptvforum.ttsavc', 23),
(1, 430, 'vnd.iptvforum.ttsmpeg2', 'vnd.iptvforum.ttsmpeg2', 24),
(1, 430, 'vnd.motorola.video', 'vnd.motorola.video', 25),
(1, 430, 'vnd.motorola.videop', 'vnd.motorola.videop', 26),
(1, 430, 'vnd.mpegurl', 'vnd.mpegurl', 27),
(1, 430, 'vnd.ms-playready.media.pyv', 'vnd.ms-playready.media.pyv', 28),
(1, 430, 'vnd.nokia.interleaved-multimedia', 'vnd.nokia.interleaved-multimedia', 29),
(1, 430, 'vnd.nokia.videovoip', 'vnd.nokia.videovoip', 30),
(1, 430, 'vnd.objectvideo', 'vnd.objectvideo', 31),
(1, 430, 'vnd.sealed-swf', 'vnd.sealed-swf', 32),
(1, 430, 'vnd.sealed.mpeg1', 'vnd.sealed.mpeg1', 33),
(1, 430, 'vnd.sealed.mpeg4', 'vnd.sealed.mpeg4', 34),
(1, 430, 'vnd.sealed.swf', 'vnd.sealed.swf', 35),
(1, 430, 'vnd.sealedmedia.softseal-mov', 'vnd.sealedmedia.softseal-mov', 36),
(1, 430, 'vnd.uvvu.mp4', 'vnd.uvvu.mp4', 37);

/** MVC */

/* CONTROLLER TASKS */

/* 1100 EXTENSION_OPTION_ID_TASKS_CONTROLLER */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1100, '', '', 0),
(1, 1100, 'add', 'display', 1),
(1, 1100, 'edit', 'display', 2),
(1, 1100, 'display', 'display', 3);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1100, 'apply', 'edit', 4),
(1, 1100, 'cancel', 'edit', 5),
(1, 1100, 'create', 'edit', 6),
(1, 1100, 'save', 'edit', 7),
(1, 1100, 'saveascopy', 'edit', 8),
(1, 1100, 'saveandnew', 'edit', 9),
(1, 1100, 'restore', 'edit', 10);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1100, 'archive', 'multiple', 11),
(1, 1100, 'publish', 'multiple', 12),
(1, 1100, 'unpublish', 'multiple', 13),
(1, 1100, 'spam', 'multiple', 14),
(1, 1100, 'trash', 'multiple', 15),
(1, 1100, 'feature', 'multiple', 16),
(1, 1100, 'unfeature', 'multiple', 17),
(1, 1100, 'sticky', 'multiple', 18),
(1, 1100, 'unsticky', 'multiple', 19),
(1, 1100, 'checkin', 'multiple', 20),
(1, 1100, 'reorder', 'multiple', 21),
(1, 1100, 'orderup', 'multiple', 22),
(1, 1100, 'orderdown', 'multiple', 23),
(1, 1100, 'saveorder', 'multiple', 24),
(1, 1100, 'delete', 'multiple', 25),
(1, 1100, 'copy', 'multiple', 26),
(1, 1100, 'move', 'multiple', 27);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1100, 'login', 'login', 28),
(1, 1100, 'logout', 'logout', 29);

/* 1100 EXTENSION_OPTION_ID_TASKS_CONTROLLER +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1101, '', '', 0),
(1, 1101, 'add', 'display', 1),
(1, 1101, 'edit', 'display', 2),
(1, 1101, 'display', 'display', 3);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1101, 'apply', 'edit', 4),
(1, 1101, 'cancel', 'edit', 5),
(1, 1101, 'create', 'edit', 6),
(1, 1101, 'save', 'edit', 7),
(1, 1101, 'saveascopy', 'edit', 8),
(1, 1101, 'saveandnew', 'edit', 9),
(1, 1101, 'restore', 'edit', 10);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1101, 'archive', 'multiple', 11),
(1, 1101, 'publish', 'multiple', 12),
(1, 1101, 'unpublish', 'multiple', 13),
(1, 1101, 'spam', 'multiple', 14),
(1, 1101, 'trash', 'multiple', 15),
(1, 1101, 'feature', 'multiple', 16),
(1, 1101, 'unfeature', 'multiple', 17),
(1, 1101, 'sticky', 'multiple', 18),
(1, 1101, 'unsticky', 'multiple', 19),
(1, 1101, 'checkin', 'multiple', 20),
(1, 1101, 'reorder', 'multiple', 21),
(1, 1101, 'orderup', 'multiple', 22),
(1, 1101, 'orderdown', 'multiple', 23),
(1, 1101, 'saveorder', 'multiple', 24),
(1, 1101, 'delete', 'multiple', 25),
(1, 1101, 'copy', 'multiple', 26),
(1, 1101, 'move', 'multiple', 27);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1101, 'login', 'login', 28),
(1, 1101, 'logout', 'login', 29);

/* OPTION */

/* 1800 EXTENSION_OPTION_ID_OPTIONS_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 1800, '', '', 0),
(1, 1800, 2552, 2552, 1),
(1, 1801, '', '', 0),
(1, 1801, 2559, 2559, 1);

/* VIEWS */

/* 2000 EXTENSION_OPTION_ID_VIEWS */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 2000, '', '', 0),
(1, 2000, 'display', 'display', 1),
(1, 2000, 'edit', 'edit', 2);

/* 2100 EXTENSION_OPTION_ID_VIEWS_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 2100, '', '', 0),
(1, 2100, 'display', 'display', 1);

/* 2000 EXTENSION_OPTION_ID_VIEWS +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 2001, '', '', 0),
(1, 2001, 'display', 'display', 1),
(1, 2001, 'edit', 'edit', 2);

/* 2100 EXTENSION_OPTION_ID_VIEWS_DEFAULT +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 2101, '', '', 0),
(1, 2101, 'display', 'display', 1);

/* VIEW VIEWS */

/* 3000 EXTENSION_OPTION_ID_VIEWS_DISPLAY */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3000, '', '', 0),
(1, 3000, 'default', 'default', 1),
(1, 3000, 'item', 'item', 1),
(1, 3000, 'items', 'items', 1),
(1, 3000, 'table', 'table', 1);

/* 3100 EXTENSION_OPTION_ID_VIEWS_DISPLAY_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3100, '', '', 0),
(1, 3100, 'default', 'default', 1);

/* 3200 EXTENSION_OPTION_ID_VIEWS_EDIT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3200, '', '', 0),
(1, 3200, 'default', 'default', 1);

/* 3300 EXTENSION_OPTION_ID_VIEWS_EDIT_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3300, '', '', 0),
(1, 3300, 'default', 'default', 1);

/* 3000 EXTENSION_OPTION_ID_VIEWS_DISPLAY +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3001, '', '', 0),
(1, 3001, 'default', 'default', 1);

/* 3100 EXTENSION_OPTION_ID_VIEWS_DISPLAY_DEFAULT +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3101, '', '', 0),
(1, 3101, 'default', 'default', 1);

/* 3200 EXTENSION_OPTION_ID_VIEWS_EDIT +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3201, '', '', 0),
(1, 3201, 'default', 'default', 1);

/* 3300 EXTENSION_OPTION_ID_VIEWS_EDIT_DEFAULT +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 3301, '', '', 0),
(1, 3301, 'default', 'default', 1);

/* VIEW FORMATS */

/* 4000 EXTENSION_OPTION_ID_FORMATS */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4000, '', '', 0),
(1, 4000, 'html', 'html', 1);

/* 4100 EXTENSION_OPTION_ID_FORMATS_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4100, '', '', 0),
(1, 4100, 'html', 'html', 1);

/* 4200 EXTENSION_OPTION_ID_VIEWS_EDIT_FORMATS */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4200, '', '', 0),
(1, 4200, 'error', 'error', 1),
(1, 4200, 'feed', 'feed', 2),
(1, 4200, 'html', 'html', 3),
(1, 4200, 'json', 'json', 4),
(1, 4200, 'opensearch', 'opensearch', 5),
(1, 4200, 'raw', 'raw', 6),
(1, 4200, 'xls', 'xls', 7),
(1, 4200, 'xml', 'xml', 8),
(1, 4200, 'xmlrpc', 'xmlrpc', 9);

/* 4300 EXTENSION_OPTION_ID_VIEWS_EDIT_FORMATS_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4300, '', '', 0),
(1, 4300, 'html', 'html', 1);


/* 4000 EXTENSION_OPTION_ID_FORMATS +application id */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4001, '', '', 0),
(1, 4001, 'html', 'html', 1);

/* 4100 EXTENSION_OPTION_ID_FORMATS_DEFAULT +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4101, '', '', 0),
(1, 4101, 'html', 'html', 1);

/* 4200 EXTENSION_OPTION_ID_VIEWS_EDIT_FORMATS +application id */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4201, '', '', 0),
(1, 4201, 'error', 'error', 1),
(1, 4201, 'feed', 'feed', 2),
(1, 4201, 'html', 'html', 3),
(1, 4201, 'json', 'json', 4),
(1, 4201, 'opensearch', 'opensearch', 5),
(1, 4201, 'raw', 'raw', 6),
(1, 4201, 'xls', 'xls', 7),
(1, 4201, 'xml', 'xml', 8),
(1, 4201, 'xmlrpc', 'xmlrpc', 9);

/* 4300 EXTENSION_OPTION_ID_VIEWS_EDIT_FORMATS_DEFAULT +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 4301, '', '', 0),
(1, 4301, 'html', 'html', 1);

/* 5000 EXTENSION_OPTION_ID_MODEL +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 5000, '', '', 0),
(1, 5000, 'display', 'display', 1),
(1, 5000, 'edit', 'edit', 2);

/* 5001 EXTENSION_OPTION_ID_MODEL +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 5001, '', '', 0),
(1, 5001, 'display', 'display', 1),
(1, 5001, 'edit', 'edit', 2);

/* 6000 EXTENSION_OPTION_ID_PLUGIN_TYPE */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 6000, '', '', 0),
(1, 6000, 'content', 'content', 1);

/** ACL Component Information */

/** 10100 EXTENSION_OPTION_ID_ACL_ITEM_TESTS **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 10100, '', '', 0),
(1, 10100, 'view', 'view', 1),
(1, 10100, 'create', 'create', 2),
(1, 10100, 'edit', 'edit', 3),
(1, 10100, 'publish', 'publish', 4),
(1, 10100, 'delete', 'delete', 5),
(1, 10100, 'administer', 'administer', 6);

/** 10000 EXTENSION_OPTION_ID_TASK_ACL_METHODS **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(1, 10200, '', '', 0),
(1, 10200, 'add', 'create', 1),
(1, 10200, 'administer', 'administer', 2),
(1, 10200, 'apply', 'edit', 3),
(1, 10200, 'archive', 'publish', 4),
(1, 10200, 'cancel', '', 5),
(1, 10200, 'checkin', 'administer', 6),
(1, 10200, 'close', '', 7),
(1, 10200, 'copy', 'create', 8),
(1, 10200, 'create', 'create', 9),
(1, 10200, 'delete', 'delete', 10),
(1, 10200, 'view', 'view', 11),
(1, 10200, 'edit', 'edit', 12),
(1, 10200, 'editstate', 'publish', 13),
(1, 10200, 'feature', 'publish', 14),
(1, 10200, 'login', 'login', 15),
(1, 10200, 'logout', 'logout', 16),
(1, 10200, 'manage', 'edit', 17),
(1, 10200, 'move', 'edit', 18),
(1, 10200, 'orderdown', 'publish', 19),
(1, 10200, 'orderup', 'publish', 20),
(1, 10200, 'publish', 'publish', 21),
(1, 10200, 'reorder', 'publish', 22),
(1, 10200, 'restore', 'publish', 23),
(1, 10200, 'save', 'edit', 24),
(1, 10200, 'saveascopy', 'edit', 25),
(1, 10200, 'saveandnew', 'edit', 26),
(1, 10200, 'saveorder', 'publish', 27),
(1, 10200, 'search', 'view', 28),
(1, 10200, 'spam', 'publish', 29),
(1, 10200, 'state', 'publish', 30),
(1, 10200, 'sticky', 'publish', 31),
(1, 10200, 'trash', 'publish', 32),
(1, 10200, 'unfeature', 'publish', 33),
(1, 10200, 'unpublish', 'publish', 34),
(1, 10200, 'unsticky', 'publish', 35);

#
# login
#

/* TABLE */

/* 100 EXTENSION_OPTION_ID_TABLE */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 100, '', '', 0),
(2559, 100, 'static', 'static', 1);

/** MVC */

/* CONTROLLER TASKS */

/* 1100 EXTENSION_OPTION_ID_TASKS_CONTROLLER */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 1100, '', '', 0),
(2559, 1100, 'display', 'display', 3);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 1100, 'login', 'login', 28),
(2559, 1100, 'logout', 'login', 29);

/* 1100 EXTENSION_OPTION_ID_TASKS_CONTROLLER +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 1101, '', '', 0),
(2559, 1101, 'display', 'display', 3);

INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 1101, 'login', 'login', 28),
(2559, 1101, 'logout', 'login', 29);

/* VIEWS */

/* 2000 EXTENSION_OPTION_ID_VIEWS */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 2000, '', '', 0),
(2559, 2000, 'display', 'display', 1);

/* 2100 EXTENSION_OPTION_ID_VIEWS_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 2100, '', '', 0),
(2559, 2100, 'display', 'display', 1);

/* 2000 EXTENSION_OPTION_ID_VIEWS +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 2001, '', '', 0),
(2559, 2001, 'display', 'display', 1);

/* 2100 EXTENSION_OPTION_ID_VIEWS_DEFAULT +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 2101, '', '', 0),
(2559, 2101, 'display', 'display', 1);

/* VIEW VIEWS */

/* 3000 EXTENSION_OPTION_ID_VIEWS_DISPLAY */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 3000, '', '', 0),
(2559, 3000, 'login', 'login', 1);

/* 3100 EXTENSION_OPTION_ID_VIEWS_DISPLAY_DEFAULT */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 3100, '', '', 0),
(2559, 3100, 'login', 'login', 1);

/* 3000 EXTENSION_OPTION_ID_VIEWS_DISPLAY +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 3001, '', '', 0),
(2559, 3001, 'admin_login', 'admin_login', 1);

/* 3100 EXTENSION_OPTION_ID_VIEWS_DISPLAY_DEFAULT +application id **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 3101, '', '', 0),
(2559, 3101, 'admin_login', 'admin_login', 1);

/* VIEW FORMATS */

/* 4000 EXTENSION_OPTION_ID_FORMATS */
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 4000, '', '', 0),
(2559, 4000, 'html', 'html', 1),
(2559, 4001, 'html', 'html', 1);

/* MODELS */

/* 5000 EXTENSION_OPTION_ID_MODEL +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 5000, '', '', 0),
(2559, 5000, 'static', 'static', 1);

/* 5000 EXTENSION_OPTION_ID_MODEL +application id */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 5001, '', '', 0),
(2559, 5001, 'static', 'static', 1);

/* 6000 EXTENSION_OPTION_ID_PLUGIN_TYPE */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 6000, '', '', 0),
(2559, 6000, 'user', 'user', 1);

/** ACL Component Information */

/** 10000 EXTENSION_OPTION_ID_ACL_IMPLEMENTATION **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 10000, '', '', 0),
(2559, 10000, 1, 'Core ACL Implementation', 1);

/** 10100 EXTENSION_OPTION_ID_ACL_ITEM_TESTS **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 10100, '', '', 0),
(2559, 10100, 'view', 'view', 1);

/** 10000 EXTENSION_OPTION_ID_TASK_ACL_METHODS **/
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2559, 10200, '', '', 0),
(2559, 10200, 'login', 'login', 15),
(2559, 10200, 'logout', 'logout', 16);

/* ARTICLES */

/* 100 EXTENSION_OPTION_ID_TABLE */;
INSERT INTO `molajo_configurations` (`extension_instances_id`, `option_id`, `option_value`, `option_value_literal`, `ordering`) VALUES
(2552, 100, '', '', 0),
(2552, 100, '__articles', '__articles', 1);
