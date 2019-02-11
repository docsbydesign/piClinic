<?php
/*
 *
 * Source file: C:\Users\BobW\Documents\GitHub\merceru-tco\CTS\tools\UIText.csv
 *
 *	Copyright (c) 2018, Robert B. Watson
 *
 *	This file is part of the piClinic Console.
 *
 *  piClinic Console is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  piClinic Console is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with piClinic Console software at https://github.com/MercerU-TCO/CTS/blob/master/LICENSE. 
 *	If not, see <http://www.gnu.org/licenses/>.
 *
 */

// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once './api/api_common.php';
require_once './uitext/appMenuText.php';
exitIfCalledFromBrowser(__FILE__);

// These are create here to build appMenuText.php

$unused = TEXT_BLANK_STAFF_OPTION;
$unused = TEXT_BLANK_VISIT_OPTION;
$unused = TEXT_CLINIC_ADMIN;
$unused = TEXT_CLINIC_COMMENT;
$unused = TEXT_CLINIC_COMMENT_TITLE;
$unused = TEXT_CLINIC_HELP;
$unused = TEXT_CLINIC_HOME;
$unused = TEXT_CLINIC_REPORTS;
// used in ui_common.php
$unused = TEXT_DATE_MONTH_PLACEHOLDER;
$unused = TEXT_DATE_DAY_PLACEHOLDER;
$unused = TEXT_DATE_YEAR_PLACEHOLDER;
unset($unused);
// EOF