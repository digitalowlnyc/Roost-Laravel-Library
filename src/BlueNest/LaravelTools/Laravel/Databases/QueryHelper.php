<?php
/**
 * Creator: Bryan Mayor
 * Company: Blue Nest Digital, LLC
 * License: (All rights reserved)
 */

namespace BlueNest\LaravelTools\Helpers;

class QueryHelper
{
    /**
     * Search all string tables
     * @param $query
     * @param $tableMigration
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
            $queryBuilder->OrWhere($col, 'LIKE', '%' . $searchString . '%');
        }

        return $query->get();
    }
}