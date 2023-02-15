<?php


namespace App\Classes;


use Illuminate\Database\Query\Builder;

class AccidentFilter
{
    public function filter(Builder $accidents, array $filter): Builder
    {
        if (strlen($filter['period'] ?? '') === 4 && @$filter['period'] !== 'null') {
            $accidents->where('year', $filter['period']);
        } elseif (@$filter['period'] && @$filter['period'] !== 'null') {
            $accidents->where('datetime', 'like', "{$filter['period']}%");
        }

        if (@$filter['participant_categories'] && ! in_array('6', $filter['participant_categories'] ?? [])) {
            // ['2','5'] -> ['Велосипедисты', 'Дети']
            $participantCategoriesNames = collect(config('map.participantCategories'))
                ->filter(fn($v) => in_array($v['id'], $filter['participant_categories']))
                ->pluck('name');

            if(! isJoined($accidents, 'accident_infos')){
                $accidents->join('accident_infos', 'accidents.id', '=', 'accident_infos.accident_id');
            }

            $accidents->where(fn($query) => $participantCategoriesNames->each(fn($v) =>
                $query->orWhereJsonContains('info->participant_categories', $v)
            ));
        }

        if (@$filter['severities']) {
            $accidents->whereIn('severity_id', $filter['severities']);
        }

        if (@$filter['accident_categories']) {
            $accidents->whereIn('accident_category_id', $filter['accident_categories']);
        }

        if (@$filter['light_conditions']) {
            $accidents->whereIn('light_conditions_id', $filter['light_conditions']);
        }

        if (@$filter['region']) {
            $accidents->where('region_id', $filter['region']);
        }

        return $accidents;
    }
}
