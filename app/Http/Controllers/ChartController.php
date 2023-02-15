<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Inertia\ResponseFactory;

class ChartController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $filter = request()->all();

        foreach ($filter as &$item){
            sort($item);
        }

        $chartsData = Cache::rememberForever(json_encode($filter), fn() => $this->getChartsData($filter));

        $filterFormData = $this->getFilterFormData();

        $time = tmr();

        return inertia('Charts/Index', compact(
            'filterFormData',
            'chartsData',
            'time'
        ));
    }

    private function filterAccidents(Builder $accidents, array $filter): Builder
    {
        if (@$filter['years'] && @$filter['years'] !== 'all') {
            $accidents->whereIn('year', $filter['years']);
        }

        if (@$filter['severities'] && @$filter['severities'] !== 'all') {
            $accidents->whereIn('severity_id', $filter['severities']);
        }

        if (@$filter['categories'] && @$filter['categories'] !== 'all') {
            $accidents->whereIn('category', $filter['categories']);
        }

        if (@$filter['light_conditions'] && @$filter['light_conditions'] !== 'all') {
            $accidents->whereIn('light_conditions', $filter['light_conditions']);
        }

        if (@$filter['regions'] && @$filter['regions'] !== 'all') {
            $accidents->whereIn('region_id', $filter['regions']);
        }

        if (@$filter['subregions'] && @$filter['subregions'] !== 'all') {
            $accidents->whereIn('subregion', $filter['subregions']);
        }

        return $accidents;
    }

    private function getFilterFormData(): array
    {
        $filterFormData = [];

        $filterFormData['allYears'] = range(2022, 2015);
        $filterFormData['allRegions'] = Region::query()->get(['id', 'name']);
        $filterFormData['allSubregions'] = Cache::rememberForever('allSubregions',
            fn() => DB::table('accidents')->select(['region_id', 'subregion'])->distinct()->get()
        );
        $filterFormData['allCategories'] = Cache::rememberForever('allCategories',
            fn() => DB::table('accidents')->select('category')->distinct()->pluck('category')
        );
        $filterFormData['allLightConditions'] = Cache::rememberForever('allLightConditions',
            fn() => DB::table('accidents')->select('light_conditions')->distinct()->pluck('light_conditions')
        );

        return $filterFormData;
    }

    private function getChartsData(array $filter): array
    {
        $accidents = DB::table('accidents');
        $accidents = $this->filterAccidents($accidents, $filter);

        $chartsData = [];

        $chartsData['main_stat'] = $this->getMainStat(clone($accidents));
        $chartsData['severities'] = $this->getSeveritiesChartData(clone($accidents));
        $chartsData['light_conditions'] = $this->getLightConditionsChartData(clone($accidents));
        $chartsData['months'] = $this->getMonthsChartData(clone($accidents));
        $chartsData['weekdays'] = $this->getWeekdaysChartData(clone($accidents));
        $chartsData['hours'] = $this->getHoursChartData(clone($accidents));

        return $chartsData;
    }

    private function getMainStat(Builder $filteredAccidents): array
    {
        $mainStat = $filteredAccidents
            ->selectRaw("count(id) as number_of_points, sum(participants_count) as participants_count, sum(dead_count) as dead_count, sum(injured_count) as injured_count")
            ->get()
            ->toArray();

        return $mainStat;
    }

    private function getSeveritiesChartData(Builder $filteredAccidents): array
    {
        $severities = [
            1 => ['name' => 'Легкий', 'color' => '#FACC15'],
            2 => ['name' => 'Тяжёлый', 'color' => '#F97316'],
            3 => ['name' => 'С погибшими', 'color' => '#DC2626'],
        ];

        $data = $filteredAccidents
            ->selectRaw("count(id) as number_of_points, severity_id")
            ->groupBy('severity_id')
            ->orderBy('severity_id')
            ->get()
        ;

        $severitiesChartData = [];

        foreach ($data as $item) {
            $severitiesChartData['labels'][] = $severities[$item->severity_id]['name'];
            $severitiesChartData['colors'][] = $severities[$item->severity_id]['color'];
            $severitiesChartData['data'][] = $item->number_of_points;
        }

        return $severitiesChartData;
    }

    private function getLightConditionsChartData(Builder $filteredAccidents): array
    {
        $data = $filteredAccidents
            ->selectRaw("count(id) as number_of_points, light_conditions")
            ->groupBy('light_conditions')
            ->pluck('number_of_points', 'light_conditions');

        $lightConditionsChartData['labels'] = $data->keys()->toArray();
        $lightConditionsChartData['data'] = $data->values()->toArray();

        return $lightConditionsChartData;
    }

    private function getMonthsChartData(Builder $filteredAccidents): array
    {
        $data = $filteredAccidents
            ->selectRaw('count(id) as number_of_points, sum(dead_count) as dead_count, sum(injured_count) as injured_count, "year-month"')
            ->groupBy('year-month')
            ->orderBy('year-month')
            ->get();

        $montChartData['labels'] = $data->pluck('year-month')->map(fn($v) => Carbon::parse($v)->isoFormat('MMM YYYY'));
        $montChartData['number_of_points'] = $data->pluck('number_of_points');
        $montChartData['dead_count'] = $data->pluck('dead_count');
        $montChartData['injured_count'] = $data->pluck('injured_count');

        return $montChartData;
    }

    private function getWeekdaysChartData(Builder $filteredAccidents): array
    {
        $data = $filteredAccidents
            ->selectRaw('count(id) as number_of_points, sum(dead_count) as dead_count, sum(injured_count) as injured_count, weekday')
            ->groupBy('weekday')
            ->orderBy('weekday')
            ->get();

        $days = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб']; //$days = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];

        $weekdaysChartData['labels'] = $data->pluck('weekday')->map(fn($v) => $days[$v]);
        $weekdaysChartData['number_of_points'] = $data->pluck('number_of_points');
        $weekdaysChartData['dead_count'] = $data->pluck('dead_count');
        $weekdaysChartData['injured_count'] = $data->pluck('injured_count');

        return $weekdaysChartData;
    }

    private function getHoursChartData(Builder $filteredAccidents): array
    {
        $data = $filteredAccidents
            ->selectRaw('count(id) as number_of_points, sum(dead_count) as dead_count, sum(injured_count) as injured_count, hour')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $hoursChartData['labels'] = $data->pluck('hour');
        $hoursChartData['number_of_points'] = $data->pluck('number_of_points');
        $hoursChartData['dead_count'] = $data->pluck('dead_count');
        $hoursChartData['injured_count'] = $data->pluck('injured_count');

        return $hoursChartData;
    }
}
