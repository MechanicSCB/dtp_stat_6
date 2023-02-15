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

            $accidents->where(fn($query) => $participantCategoriesNames->each(fn($v) =>
                $query->orWhereJsonContains('info->participant_categories', $v)
            ));
        }

        if (@$filter['severities']) {
            $accidents->whereIn('severity_id', $filter['severities']);
        }

        if (@$filter['accident_categories']) {
            // ['2','4'] -> ['Падение пассажира', 'Столкновение']
            $accidentCategoriesNames = collect(config('map.accidentCategories'))
                ->filter(fn($v) => in_array($v['id'], $filter['accident_categories']))
                ->pluck('name');
            $accidents->whereIn('category', $accidentCategoriesNames);
        }

        if (@$filter['light_conditions']) {
            $accidentLightConditions= collect(config('map.lightConditions'))
                ->filter(fn($v) => in_array($v['id'], $filter['light_conditions']))
                ->pluck('name');
            $accidents->whereIn('light_conditions', $accidentLightConditions);
        }

        if (@$filter['region']) {
            $accidents->where('region_id', $filter['region']);
        }

        return $accidents;
    }
}
