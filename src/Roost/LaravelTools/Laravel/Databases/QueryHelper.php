<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace Roost\LaravelTools\Laravel\Databases;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;

class QueryHelper
{
    /**
     * Search all string tables
     * @param $queryBuilder Builder
     * @param $tableMigration Migration
     * @param $searchString
     * @return mixed
     */
    public static function stringSearchTable($queryBuilder, $tableMigration, $searchString) {
        $blueprint = new Blueprint('dummy');
        $blueprintFunc = $tableMigration->provideBlueprint();
        $blueprintFunc($blueprint);

        $stringColumns = [];
        foreach($blueprint->getColumns() as $column) {
            if($column['attributes']['type'] === 'string') {
                $stringColumns[] = $column['attributes']['name'];
            }
        }

        foreach($stringColumns as $col) {
            $queryBuilder->orWhere($col, 'LIKE', '%' . $searchString . '%');
        }

        return $queryBuilder->get();
    }
}