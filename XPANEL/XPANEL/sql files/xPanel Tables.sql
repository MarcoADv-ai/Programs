ALTER TABLE `login`
ADD `master_acc_id` INT NOT NULL;

CREATE TABLE IF NOT EXISTS `x_vote_points` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `master_id` int NOT NULL,
  `rank_id` int NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mac_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delay_expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `x_master_accounts_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `master_id` int NOT NULL,
  `date` datetime NOT NULL DEFAULT '2022-09-20 04:36:29',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `x_mp_donations_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `preference_id` varchar(100) NOT NULL,
  `master_id` int NOT NULL DEFAULT '0',
  `client_id` int NOT NULL DEFAULT '0',
  `items` longtext NOT NULL,
  `credits` int NOT NULL DEFAULT '0',
  `payment_status` varchar(100) NOT NULL,
  `create_date` datetime NOT NULL,
  `mc_gross` float NOT NULL DEFAULT '0',
  `mc_fee` float NOT NULL DEFAULT '0',
  `mc_currency` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `x_paypal_donations_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `master_id` int unsigned DEFAULT '0',
  `server_name` varchar(255) DEFAULT NULL,
  `credits` int DEFAULT '0',
  `bonus` int DEFAULT '0',
  `items` mediumtext,
  `payment_status` varchar(20) DEFAULT NULL,
  `pending_reason` varchar(20) DEFAULT NULL,
  `payment_date` varchar(40) DEFAULT NULL,
  `mc_gross` varchar(20) DEFAULT NULL,
  `mc_fee` varchar(20) DEFAULT NULL,
  `mc_currency` varchar(3) DEFAULT NULL,
  `txn_id` varchar(20) DEFAULT NULL,
  `txn_type` varchar(20) DEFAULT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `payer_email` varchar(60) DEFAULT NULL,
  `payer_status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `txn_id` (`txn_id`),
  KEY `account_id` (`master_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='All PayPal transactions that go through the IPN handler.';

CREATE TABLE IF NOT EXISTS `x_transfer_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `master_id` int unsigned NOT NULL,
  `transfer_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `account` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `char` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `amount` int unsigned NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `master_id` (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `x_vote4points_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `master_id` int DEFAULT NULL,
  `rank_id` int unsigned DEFAULT '0',
  `points` int unsigned DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `master_id` (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `x_wiki_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `x_wiki_posts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `icon` longtext COLLATE utf8mb4_unicode_ci,
  `archive` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_x_wiki_posts_x_wiki_categories` (`category_id`),
  CONSTRAINT `FK_x_wiki_posts_x_wiki_categories` FOREIGN KEY (`category_id`) REFERENCES `x_wiki_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `payload` text COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ES',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_general_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_general_ci,
  `donation_points` int NOT NULL DEFAULT '0',
  `vote_points` int NOT NULL DEFAULT '0',
  `role` enum('User','Admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'User',
  `status` int NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `mvp_rank` (
  `char_id` int(11) NOT NULL,
  `mvp_kills` int(11) DEFAULT '0',
  PRIMARY KEY (`char_id`),
  KEY `mvp_kills` (`mvp_kills`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pvp_rank` (
  `char_id` int(10) unsigned NOT NULL,
  `kill` int(10) unsigned NOT NULL DEFAULT '0',
  `dead` int(10) unsigned NOT NULL DEFAULT '0',
  `point` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`char_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pvp_rank_archive` (
  `char_id` int(10) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `kill` int(10) unsigned NOT NULL,
  `dead` int(10) unsigned NOT NULL,
  `point` int(10) NOT NULL,
  PRIMARY KEY (`char_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rank_bg` (
  `char_id` int(11) NOT NULL,
  `top_damage` int(11) NOT NULL DEFAULT '0',
  `damage_done` int(11) NOT NULL DEFAULT '0',
  `damage_received` int(11) NOT NULL DEFAULT '0',
  `cq_emperium_kill` int(11) NOT NULL DEFAULT '0',
  `cq_barricade_kill` int(11) NOT NULL DEFAULT '0',
  `cq_gstone_kill` int(11) NOT NULL DEFAULT '0',
  `cq_wins` int(11) NOT NULL DEFAULT '0',
  `cq_lost` int(11) NOT NULL DEFAULT '0',
  `tvt_kills` int(11) NOT NULL DEFAULT '0',
  `tvt_deaths` int(11) NOT NULL DEFAULT '0',
  `tvt_wins` int(11) NOT NULL DEFAULT '0',
  `tvt_lost` int(11) NOT NULL DEFAULT '0',
  `tvt_tie` int(11) NOT NULL DEFAULT '0',
  `cs_emperium_kill` int(11) NOT NULL DEFAULT '0',
  `cs_barricade_kill` int(11) NOT NULL DEFAULT '0',
  `cs_gstone_kill` int(11) NOT NULL DEFAULT '0',
  `cs_wins` int(11) NOT NULL DEFAULT '0',
  `cs_lost` int(11) NOT NULL DEFAULT '0',
  `cs_tie` int(11) NOT NULL DEFAULT '0',
  `dte_emperium_kill` int(11) NOT NULL DEFAULT '0',
  `dte_wins` int(11) NOT NULL DEFAULT '0',
  `dte_lost` int(11) NOT NULL DEFAULT '0',
  `dte_tie` int(11) NOT NULL DEFAULT '0',
  `bz_wins` int(11) NOT NULL DEFAULT '0',
  `bz_lost` int(11) NOT NULL DEFAULT '0',
  `bz_tie` int(11) NOT NULL DEFAULT '0',
  `koe_emperium_kill` int(11) NOT NULL DEFAULT '0',
  `koe_barricade_kill` int(11) NOT NULL DEFAULT '0',
  `koe_wins` int(11) NOT NULL DEFAULT '0',
  `koe_lost` int(11) NOT NULL DEFAULT '0',
  `koe_tie` int(11) NOT NULL DEFAULT '0',
  `ct_base` int(11) NOT NULL DEFAULT '0',
  `ct_take` int(11) NOT NULL DEFAULT '0',
  `ct_droped` int(11) NOT NULL DEFAULT '0',
  `ct_barricade_kill` int(11) NOT NULL DEFAULT '0',
  `ct_wins` int(11) NOT NULL DEFAULT '0',
  `ct_lost` int(11) NOT NULL DEFAULT '0',
  `ct_tie` int(11) NOT NULL DEFAULT '0',
  `ti_skulls` int(11) NOT NULL DEFAULT '0',
  `ti_wins` int(11) NOT NULL DEFAULT '0',
  `ti_lost` int(11) NOT NULL DEFAULT '0',
  `ti_tie` int(11) NOT NULL DEFAULT '0',
  `td_kills` int(11) NOT NULL DEFAULT '0',
  `td_deaths` int(11) NOT NULL DEFAULT '0',
  `td_wins` int(11) NOT NULL DEFAULT '0',
  `td_lost` int(11) NOT NULL DEFAULT '0',
  `td_tie` int(11) NOT NULL DEFAULT '0',
  `kill_count` int(11) NOT NULL DEFAULT '0',
  `death_count` int(11) NOT NULL DEFAULT '0',
  `win` int(11) NOT NULL DEFAULT '0',
  `lost` int(11) NOT NULL DEFAULT '0',
  `tie` int(11) NOT NULL DEFAULT '0',
  `leader_win` int(11) NOT NULL DEFAULT '0',
  `leader_lost` int(11) NOT NULL DEFAULT '0',
  `leader_tie` int(11) NOT NULL DEFAULT '0',
  `deserter` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `sp_heal_potions` int(11) NOT NULL DEFAULT '0',
  `hp_heal_potions` int(11) NOT NULL DEFAULT '0',
  `yellow_gemstones` int(11) NOT NULL DEFAULT '0',
  `red_gemstones` int(11) NOT NULL DEFAULT '0',
  `blue_gemstones` int(11) NOT NULL DEFAULT '0',
  `poison_bottles` int(11) NOT NULL DEFAULT '0',
  `acid_demostration` int(11) NOT NULL DEFAULT '0',
  `acid_demostration_fail` int(11) NOT NULL DEFAULT '0',
  `support_skills_used` int(11) NOT NULL DEFAULT '0',
  `healing_done` int(11) NOT NULL DEFAULT '0',
  `wrong_support_skills_used` int(11) NOT NULL DEFAULT '0',
  `wrong_healing_done` int(11) NOT NULL DEFAULT '0',
  `sp_used` int(11) NOT NULL DEFAULT '0',
  `zeny_used` int(11) NOT NULL DEFAULT '0',
  `spiritb_used` int(11) NOT NULL DEFAULT '0',
  `ammo_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`char_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rank_bg_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `killer` varchar(25) NOT NULL,
  `killer_id` int(11) NOT NULL,
  `killed` varchar(25) NOT NULL,
  `killed_id` int(11) NOT NULL,
  `map` varchar(11) NOT NULL DEFAULT '',
  `skill` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `killer_id` (`killer_id`),
  KEY `killed_id` (`killed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rank_bg_skill_count` (
  `char_id` int(10) unsigned NOT NULL DEFAULT '0',
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`char_id`,`id`),
  KEY `char_id` (`char_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rank_guild` (
  `guild_id` int(11) NOT NULL,
  `castle_id` int(11) NOT NULL,
  `capture` int(10) unsigned NOT NULL DEFAULT '0',
  `emperium` int(10) unsigned NOT NULL DEFAULT '0',
  `treasure` int(10) unsigned NOT NULL DEFAULT '0',
  `top_eco` int(10) unsigned NOT NULL DEFAULT '0',
  `top_def` int(10) unsigned NOT NULL DEFAULT '0',
  `invest_eco` int(10) unsigned NOT NULL DEFAULT '0',
  `invest_def` int(10) unsigned NOT NULL DEFAULT '0',
  `offensive_score` int(10) unsigned NOT NULL DEFAULT '0',
  `defensive_score` int(10) unsigned NOT NULL DEFAULT '0',
  `posesion_time` int(10) unsigned NOT NULL DEFAULT '0',
  `zeny_eco` int(10) unsigned NOT NULL DEFAULT '0',
  `zeny_def` int(10) unsigned NOT NULL DEFAULT '0',
  `skill_battleorder` int(10) unsigned NOT NULL DEFAULT '0',
  `skill_regeneration` int(10) unsigned NOT NULL DEFAULT '0',
  `skill_restore` int(10) unsigned NOT NULL DEFAULT '0',
  `skill_emergencycall` int(10) unsigned NOT NULL DEFAULT '0',
  `off_kill` int(10) unsigned NOT NULL DEFAULT '0',
  `off_death` int(10) unsigned NOT NULL DEFAULT '0',
  `def_kill` int(10) unsigned NOT NULL DEFAULT '0',
  `def_death` int(10) unsigned NOT NULL DEFAULT '0',
  `ext_kill` int(10) unsigned NOT NULL DEFAULT '0',
  `ext_death` int(10) unsigned NOT NULL DEFAULT '0',
  `ali_kill` int(10) unsigned NOT NULL DEFAULT '0',
  `ali_death` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`guild_id`,`castle_id`),
  KEY `castle_id` (`castle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rank_skill_count` (
  `char_id` int(10) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '2023-01-01',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`char_id`,`date`,`type`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rank_woe` (
  `char_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT '2022-01-01',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `guild_id` int(11) NOT NULL DEFAULT '0',
  `kill_count` int(11) NOT NULL DEFAULT '0',
  `death_count` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `top_damage` int(11) NOT NULL DEFAULT '0',
  `damage_done` int(11) NOT NULL DEFAULT '0',
  `damage_received` int(11) NOT NULL DEFAULT '0',
  `emperium_damage` int(11) NOT NULL DEFAULT '0',
  `guardian_damage` int(11) NOT NULL DEFAULT '0',
  `barricade_damage` int(11) NOT NULL DEFAULT '0',
  `gstone_damage` int(11) NOT NULL DEFAULT '0',
  `emperium_kill` int(11) NOT NULL DEFAULT '0',
  `guardian_kill` int(11) NOT NULL DEFAULT '0',
  `barricade_kill` int(11) NOT NULL DEFAULT '0',
  `gstone_kill` int(11) NOT NULL DEFAULT '0',
  `sp_heal_potions` int(11) NOT NULL DEFAULT '0',
  `hp_heal_potions` int(11) NOT NULL DEFAULT '0',
  `yellow_gemstones` int(11) NOT NULL DEFAULT '0',
  `red_gemstones` int(11) NOT NULL DEFAULT '0',
  `blue_gemstones` int(11) NOT NULL DEFAULT '0',
  `poison_bottles` int(11) NOT NULL DEFAULT '0',
  `acid_demostration` int(11) NOT NULL DEFAULT '0',
  `acid_demostration_fail` int(11) NOT NULL DEFAULT '0',
  `support_skills_used` int(11) NOT NULL DEFAULT '0',
  `healing_done` int(11) NOT NULL DEFAULT '0',
  `wrong_support_skills_used` int(11) NOT NULL DEFAULT '0',
  `wrong_healing_done` int(11) NOT NULL DEFAULT '0',
  `sp_used` int(11) NOT NULL DEFAULT '0',
  `zeny_used` int(11) NOT NULL DEFAULT '0',
  `spiritb_used` int(11) NOT NULL DEFAULT '0',
  `ammo_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`char_id`,`date`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
