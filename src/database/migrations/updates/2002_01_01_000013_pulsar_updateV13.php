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
		DBLibrary::changeColumnNameWithForeignKey('001_002_country', 'lang_002', 'lang_id_002', 'VARCHAR', 2, false, false, 'fk01_001_002_country', '001_001_lang', 'id_001');
		// change country_003
		DBLibrary::changeColumnNameWithForeignKey('001_003_territorial_area_1', 'country_003', 'country_id_003', 'VARCHAR', 2, false, false, 'fk01_001_003_territorial_area_1', '001_002_country', 'id_002');
		// change country_004
		DBLibrary::changeColumnNameWithForeignKey('001_004_territorial_area_2', 'country_004', 'country_id_004', 'VARCHAR', 2, false, false, 'fk01_001_004_territorial_area_2', '001_002_country', 'id_002');
		// change country_005
		DBLibrary::changeColumnNameWithForeignKey('001_005_territorial_area_3', 'country_005', 'country_id_005', 'VARCHAR', 2, false, false, 'fk01_001_005_territorial_area_3', '001_002_country', 'id_002');
		
		// change territorial_area_1_004
		DBLibrary::changeColumnNameWithForeignKey('001_004_territorial_area_2', 'territorial_area_1_004', 'territorial_area_1_id_004', 'VARCHAR', 6, false, false, 'fk02_001_004_territorial_area_2', '001_003_territorial_area_1', 'id_003');
		// change territorial_area_1_005
		DBLibrary::changeColumnNameWithForeignKey('001_005_territorial_area_3', 'territorial_area_1_005', 'territorial_area_1_id_005', 'VARCHAR', 6, false, false, 'fk02_001_005_territorial_area_3', '001_003_territorial_area_1', 'id_003');
		// change territorial_area_2_005
		DBLibrary::changeColumnNameWithForeignKey('001_005_territorial_area_3', 'territorial_area_2_005', 'territorial_area_2_id_005', 'VARCHAR', 10, false, false, 'fk03_001_005_territorial_area_3', '001_004_territorial_area_2', 'id_004');

		// change lang_010
		DBLibrary::changeColumnNameWithForeignKey('001_010_user', 'lang_010', 'lang_id_010', 'VARCHAR', 2, false, false, 'fk01_001_010_user', '001_001_lang', 'id_001');
		// change profile_010
		DBLibrary::changeColumnNameWithForeignKey('001_010_user', 'profile_010', 'profile_id_010', 'INT', 10, true, false, 'fk02_001_010_user', '001_006_profile', 'id_006');
		// change package_007
		DBLibrary::changeColumnNameWithForeignKey('001_007_resource', 'package_007', 'package_id_007', 'INT', 10, true, false, 'fk01_001_007_resource', '001_012_package', 'id_012');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){}
}