<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: TruncateTable.php
 */
namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Class TruncateTable.
 */
trait TruncateTable
{
    /**
     * @param $table
     *
     * @return bool
     */
    protected function truncate($table)
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                return DB::table($table)->truncate();

            case 'pgsql':
                return  DB::statement('TRUNCATE TABLE '.$table.' RESTART IDENTITY CASCADE');

            case 'sqlite': case 'sqlsrv':
            return DB::statement('DELETE FROM '.$table);
        }

        return false;
    }

    /**
     * @param array $tables
     */
    protected function truncateMultiple(array $tables)
    {
        foreach ($tables as $table) {
            $this->truncate($table);
        }
    }
}
