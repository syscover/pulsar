<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Syscover\Pulsar\Libraries\DBLibrary;

class PulsarUpdateV13 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// change lang_002
		DBLibrary::renameColumnWithForeignKey('001_002_country', 'lang_002', 'lang_id_002', 'VARCHAR', 2, false, false, 'fk01_001_002_country', '001_001_lang', 'id_001');
		
		// change country_003
		DBLibrary::renameColumnWithForeignKey('001_003_territorial_area_1', 'country_003', 'country_id_003', 'VARCHAR', 2, false, false, 'fk01_001_003_territorial_area_1', '001_002_country', 'id_002');
		
		// change country_004
		DBLibrary::renameColumnWithForeignKey('001_004_territorial_area_2', 'country_004', 'country_id_004', 'VARCHAR', 2, false, false, 'fk01_001_004_territorial_area_2', '001_002_country', 'id_002');
		// change territorial_area_1_004
		DBLibrary::renameColumnWithForeignKey('001_004_territorial_area_2', 'territorial_area_1_004', 'territorial_area_1_id_004', 'VARCHAR', 6, false, false, 'fk02_001_004_territorial_area_2', '001_003_territorial_area_1', 'id_003');
		

		// change country_005
		DBLibrary::renameColumnWithForeignKey('001_005_territorial_area_3', 'country_005', 'country_id_005', 'VARCHAR', 2, false, false, 'fk01_001_005_territorial_area_3', '001_002_country', 'id_002');
		// change territorial_area_1_005
		DBLibrary::renameColumnWithForeignKey('001_005_territorial_area_3', 'territorial_area_1_005', 'territorial_area_1_id_005', 'VARCHAR', 6, false, false, 'fk02_001_005_territorial_area_3', '001_003_territorial_area_1', 'id_003');
		// change territorial_area_2_005
		DBLibrary::renameColumnWithForeignKey('001_005_territorial_area_3', 'territorial_area_2_005', 'territorial_area_2_id_005', 'VARCHAR', 10, false, false, 'fk03_001_005_territorial_area_3', '001_004_territorial_area_2', 'id_004');

		// change lang_010
		DBLibrary::renameColumnWithForeignKey('001_010_user', 'lang_010', 'lang_id_010', 'VARCHAR', 2, false, false, 'fk01_001_010_user', '001_001_lang', 'id_001');
		// change profile_010
		DBLibrary::renameColumnWithForeignKey('001_010_user', 'profile_010', 'profile_id_010', 'INT', 10, true, false, 'fk02_001_010_user', '001_006_profile', 'id_006');
		
		// change package_007
		DBLibrary::renameColumnWithForeignKey('001_007_resource', 'package_007', 'package_id_007', 'INT', 10, true, false, 'fk01_001_007_resource', '001_012_package', 'id_012');

		// change profile_009
		DBLibrary::renameColumnWithForeignKey('001_009_permission', 'profile_009', 'profile_id_009', 'INT', 10, true, false, 'fk01_001_009_permission', '001_006_profile', 'id_006', 'cascade', 'cascade');
		// change resource_009
		DBLibrary::renameColumnWithForeignKey('001_009_permission', 'resource_009', 'resource_id_009', 'VARCHAR', 30, false, false, 'fk02_001_009_permission', '001_007_resource', 'id_007', 'cascade', 'cascade');
		// change action_009
		DBLibrary::renameColumnWithForeignKey('001_009_permission', 'action_009', 'action_id_009', 'VARCHAR', 25, false, false, 'fk03_001_009_permission', '001_008_action', 'id_008', 'cascade', 'cascade');

		// change package_011
		DBLibrary::renameColumnWithForeignKey('001_011_cron_job', 'package_011', 'package_id_011', 'INT', 10, true, false, 'fk01_001_011_cron_job', '001_012_package', 'id_012');

		// change package_018
		DBLibrary::renameColumnWithForeignKey('001_018_preference', 'package_018', 'package_id_018', 'INT', 10, true, false, 'fk01_001_018_preference', '001_012_package', 'id_012');

		// change resource_014
		DBLibrary::renameColumnWithForeignKey('001_014_attachment_library', 'resource_014', 'resource_id_014', 'VARCHAR', 30, false, false, 'fk01_001_014_attachment_library', '001_007_resource', 'id_007', 'cascade', 'cascade');

		// change resource_015
		DBLibrary::renameColumnWithForeignKey('001_015_attachment_family', 'resource_015', 'resource_id_015', 'VARCHAR', 30, false, false, 'fk01_001_015_attachment_family', '001_007_resource', 'id_007', 'cascade', 'cascade');

		// change lang_016
		DBLibrary::renameColumnWithForeignKey('001_016_attachment', 'lang_016', 'lang_id_016', 'VARCHAR', 2, false, false, 'fk01_001_016_attachment', '001_001_lang', 'id_001');
		// change resource_016
		DBLibrary::renameColumnWithForeignKey('001_016_attachment', 'resource_016', 'resource_id_016', 'VARCHAR', 30, false, false, 'fk02_001_016_attachment', '001_007_resource', 'id_007', 'cascade', 'cascade');
		// change family_016
		DBLibrary::renameColumnWithForeignKey('001_016_attachment', 'family_016', 'family_id_016', 'INT', 10, true, true, 'fk03_001_016_attachment', '001_015_attachment_family', 'id_015');
		// change library_016
		DBLibrary::renameColumnWithForeignKey('001_016_attachment', 'library_016', 'library_id_016', 'INT', 10, true, true, 'fk04_001_016_attachment', '001_014_attachment_library', 'id_014', 'set null', 'cascade');

		// change resource_025
		DBLibrary::renameColumnWithForeignKey('001_025_field_group', 'resource_025', 'resource_id_025', 'VARCHAR', 30, false, false, 'fk01_001_025_field_group', '001_007_resource', 'id_007', 'cascade', 'cascade');

		// change group_026
		DBLibrary::renameColumnWithForeignKey('001_026_field', 'group_026', 'group_id_026', 'INT', 10, true, false, 'fk01_001_026_field', '001_025_field_group', 'id_025', 'cascade', 'cascade');

		// change lang_027
		DBLibrary::renameColumnWithForeignKey('001_027_field_value', 'lang_027', 'lang_id_027', 'VARCHAR', 2, false, false, 'fk01_001_027_field_value', '001_001_lang', 'id_001');
		// change field_027
		DBLibrary::renameColumnWithForeignKey('001_027_field_value', 'field_027', 'field_id_027', 'INT', 10, true, false, 'fk02_001_027_field_value', '001_026_field', 'id_026', 'cascade', 'cascade');

		// change lang_028
		DBLibrary::renameColumnWithForeignKey('001_028_field_result', 'lang_028', 'lang_id_028', 'VARCHAR', 2, false, false, 'fk01_001_028_field_result', '001_001_lang', 'id_001');
		// change field_028
		DBLibrary::renameColumnWithForeignKey('001_028_field_result', 'field_028', 'field_id_028', 'INT', 10, true, false, 'fk02_001_028_field_result', '001_026_field', 'id_026', 'cascade', 'cascade');
		// change resource_028
		DBLibrary::renameColumnWithForeignKey('001_028_field_result', 'resource_028', 'resource_id_028', 'VARCHAR', 30, false, false, 'fk03_001_028_field_result', '001_007_resource', 'id_007', 'cascade', 'cascade');

		// rename columns
		// type_014
		DBLibrary::renameColumn('001_014_attachment_library', 'type_014', 'type_id_014', 'TINYINT', 3, true, false);

		// type_016
		DBLibrary::renameColumn('001_016_attachment', 'type_016', 'type_id_016', 'TINYINT', 3, true, false);
		// object_016
		DBLibrary::renameColumn('001_016_attachment', 'object_016', 'object_id_016', 'INT', 10, true, true);
		
		// object_028
		DBLibrary::renameColumn('001_028_field_result', 'object_028', 'object_id_028', 'INT', 10, true, false);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}