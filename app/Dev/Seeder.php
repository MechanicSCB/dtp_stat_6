<?php


namespace App\Dev;


use App\Classes\GeoCalc;
use App\Classes\TileHandler;
use App\Models\Accident;
use App\Models\AccidentCategory;
use App\Models\AccidentInfo;
use App\Models\LightCondition;
use App\Models\Region;
use App\Models\Severity;
use App\Models\Subregion;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Seeder
{
    private Collection $regionFilenames;

    public function seed()
    {
        $regionIds = Region::pluck('id');
        //df(tmr(@$this->start), 'seed');
        //$regionIds = $regionIds->take(5);
        //$regionIds = collect(77);
        $existedRegionIds = Accident::pluck('region_id')->unique()->values()->toArray();
        //df(tmr(@$this->start), $existedRegionIds);
        //
        foreach ($regionIds as $regionId) {
            if (in_array($regionId, $existedRegionIds)) {
                continue;
            }

            $this->seedRegionAccidents($regionId);
        }

        df(tmr(@$this->start), $regionIds);
    }

    public function seedRegionAccidents(int $regionId): bool
    {
        set_time_limit(350);
        //$regionId = 50;
        //df(tmr(@$this->start), $regionId, 'seed');

        // GET DATA FROM REGIONS JSON
        $data = $this->getDataFromRegionsJson($regionId);
        //$data = array_slice($data, 0, 40000, true);
        //df(tmr(@$this->start), $regionId, count($data),array_slice($data, 0, 10, true));

        // GET ACCIDENTS ARRAY FROM DTP DATA
        $accidents = [];
        $accidentInfos = [];
        $severities = Severity::query()->pluck('id', 'name');
        $subregions = Subregion::query()->where('region_id', $regionId)->pluck('id', 'name');
        $accidentCategories = AccidentCategory::query()->pluck('id', 'name');
        $lightConditions = LightCondition::query()->pluck('id', 'name');

        foreach ($data as $dtp) {
            $lat = $dtp['point']['lat'];
            $lon = $dtp['point']['long'];
            $lonlat1 = number_format(truncate($lon, 1), 1) . '-' . number_format(truncate($lat, 1), 1);

            $accidents[$dtp['id']] = [
                'id' => $dtp['id'],
                'latitude' => $lat,
                'longitude' => $lon,
                'lonlat1' => $lonlat1,
                'quadkey' => (new GeoCalc())->getQuadkeyFromGeoCoords($lat, $lon),
                'severity_id' => $severities[$dtp['severity']],
                'datetime' => $datetime = $dtp['datetime'],
                'year' => $year = substr($datetime, 0, 4),
                'region_id' => $regionId,
                'subregion_id' => $subregions[$dtp['region']],
                'accident_category_id' => $accidentCategories[$dtp['category']],
                'dead_count' => $dtp['dead_count'],
                'injured_count' => $dtp['injured_count'],
                'participants_count' => $dtp['participants_count'],
                'light_conditions_id' => $lightConditions[$dtp['light']],
                'year-month' => $year . '-' . substr($datetime, 5, 2),
                'weekday' => Carbon::parse($dtp['datetime'])->weekday(),
                'hour' => substr($datetime, 11, 2),
            ];

            $info = collect($dtp)->only(['tags', 'nearby', 'scheme', 'address', 'weather', 'vehicles', 'participants', 'road_conditions', 'participant_categories']);
            //$info = collect($dtp)->except(['id','light','point','region','category','datetime','severity','dead_count','injured_count','parent_region','participants_count']);

            $accidentInfos[$dtp['id']] = ['accident_id' => $dtp['id'], 'info' => json_encode($info)];
        }

        // SAVE ACCIDENTS TO DB
        foreach (array_chunk($accidents, 1000) as $chunk) {
            Accident::upsert($chunk, ['id']);
        }

        // SAVE ACCIDENT INFOS TO DB
        foreach (array_chunk($accidentInfos, 1000) as $chunk) {
            AccidentInfo::upsert($chunk, ['accident_id']);
        }

        return true;
        //df(tmr(@$this->start), "done $regionId: " . count($data));
    }

    private function createMissedRelatedModels(array $missedRelatedModels, int $regionId): void
    {
        foreach ($missedRelatedModels as $table => $missedNames) {
            if ($table === 'subregions') {
                $missedNames = array_map(fn($v) => ['region_id' => $regionId, 'name' => $v], $missedNames);

                DB::table($table)->insert($missedNames);
            } else {
                foreach ($missedNames as $missedName) {
                    DB::table($table)->insert(['name' => $missedName]);
                }
            }
        }
    }

    private function getMissedRelatedModels(array $data, int $regionId): array
    {
        $missedNames = [];
        $models = [
            'severity' => 'severities',
            'category' => 'accident_categories',
            'participant_categories' => 'participant_categories',
            'tags' => 'tags',
            'light' => 'light_conditions',
            'nearby' => 'nearby_objects',
            'weather' => 'weather_conditions',
            'road_conditions' => 'road_conditions',
            'region' => 'subregions',
            'vehicles.*.participants.*.health_status' => 'health_statuses',
            'participants.*.health_status' => 'health_statuses',
            'vehicles.*.participants.*.violations' => 'violations',
            'participants.*.violations' => 'violations',
        ];

        $modelArrays = $this->getRelatedModelsArrays($data, $models);

        foreach ($modelArrays as $table => $names) {
            $existedNames = DB::table($table);

            if ($table === 'subregions') {
                $existedNames->whereRegionId($regionId);
            }

            $existedNames = $existedNames->pluck('name')->toArray();
            $missedNames[$table] = array_filter(array_diff($names, $existedNames));
        }

        return $missedNames;
    }

    private function createVehiclesAndParticipants(array $data): void
    {
        $vehicles = [];
        $participants = [];
        $participant_violation = [];
        $healthStatusIds = HealthStatus::pluck('id', 'name');
        $violationsIds = Violation::pluck('id', 'name');

        foreach ($data as $dtp) {
            foreach ($dtp['vehicles'] as $vehicleKey => $vehicle) {
                foreach ($vehicle['participants'] as $participantKey => $participant) {
                    $participantToDb = $this->getParticipantToDbFromDtpParticipant(
                        $participant,
                        $dtp['id'],
                        $participantKey,
                        $vehicleKey,
                        $healthStatusIds
                    );

                    $participantViolations = $this->getParticipantViolations($participant['violations'], $violationsIds, $participantToDb['id']);
                    $participant_violation = [...$participant_violation, ...$participantViolations];
                    $participants[] = $participantToDb;
                }

                $vehicle['id'] = "{$dtp['id']}-$vehicleKey";
                $vehicle['accident_id'] = $dtp['id'];
                unset($vehicle['participants']);
                $vehicles[] = $vehicle;
            }

            foreach ($dtp['participants'] as $participantKey => $participant) {
                $participantToDb = $this->getParticipantToDbFromDtpParticipant(
                    $participant,
                    $dtp['id'],
                    $participantKey,
                    null,
                    $healthStatusIds
                );

                $participantViolations = $this->getParticipantViolations($participant['violations'], $violationsIds, $participantToDb['id']);
                $participant_violation = [...$participant_violation, ...$participantViolations];
                $participants[] = $participantToDb;
            }
        }

        foreach (array_chunk($vehicles, 5000) as $chunk) {
            Vehicle::upsert($chunk, ['id']);
        }

        foreach (array_chunk($participants, 5000) as $chunk) {
            Participant::upsert($chunk, ['id']);
        }

        foreach (array_chunk($participant_violation, 5000) as $chunk) {
            DB::table('participant_violation')->upsert($chunk, ['participant_id', 'violation_id']);
        }

        //df(tmr(@$this->start), $vehicles, $participants);
    }

    private function createManyToManyRelations(array $data): void
    {
        $relations = [
            'ParticipantCategory' => 'participant_categories',
            'NearbyObject' => 'nearby',
            'Tag' => 'tags',
            'RoadCondition' => 'road_conditions',
            'WeatherCondition' => 'weather',
        ];

        $pivots = [];

        foreach ($relations as $modelName => $field) {
            $modelClass = "App\Models\\$modelName";
            $relatedModelsIds[$modelName] = $modelClass::pluck('id', 'name');
        }

        foreach ($data as $dtp) {
            foreach ($relations as $modelName => $field) {
                foreach ($dtp[$field] as $item) {
                    $modelKey = Str::snake($modelName) . '_id';

                    $pivots[$modelName][] = [
                        'accident_id' => $dtp['id'],
                        $modelKey => $relatedModelsIds[$modelName][$item],
                    ];
                }
            }
        }

        // TODO delete all upserted accidents pivot data

        foreach ($pivots as $modelName => $pivotData) {
            foreach (array_chunk($pivotData, 20000) as $chunk) {
                $tableName = 'accident_' . Str::snake($modelName);
                $foreignKey = Str::snake($modelName) . '_id';
                DB::table($tableName)->upsert($chunk, ['accident_id', $foreignKey]);
            }
        }
    }

    private function createManyToManyRelationsSlow(Collection $upsertedAccidents, array $data): void
    {
        $relations = [
            'participantCategories' => [
                'model_class' => 'App\Models\ParticipantCategory',
                'data_field' => 'participant_categories',
            ],
            'nearbyObjects' => [
                'model_class' => 'App\Models\NearbyObject',
                'data_field' => 'nearby',
            ],
            'tags' => [
                'model_class' => 'App\Models\Tag',
                'data_field' => 'tags',
            ],
            'roadConditions' => [
                'model_class' => 'App\Models\RoadCondition',
                'data_field' => 'road_conditions',
            ],
            'weatherConditions' => [
                'model_class' => 'App\Models\WeatherCondition',
                'data_field' => 'weather',
            ],
        ];

        foreach ($relations as $relationName => $relation) {
            $nearbyObjectsIds = $relation['model_class']::pluck('id', 'name');

            foreach ($upsertedAccidents as $upsertedAccident) {
                $relatedNearbyObjectsIds = $nearbyObjectsIds->only($data[$upsertedAccident['id']][$relation['data_field']]);
                $upsertedAccident->$relationName()->detach(); // TODO why doesn't work sync
                $upsertedAccident->$relationName()->attach($relatedNearbyObjectsIds);
            }
        }
    }

    private function getAccidentsFromDtpData(array $data, int $regionId): ?array
    {
        $tileHandler = new TileHandler();

        foreach ($data as $dtp) {
            $accidents[$dtp['id']] = [
                'id' => $dtp['id'],
                'latitude' => $lat = $dtp['point']['lat'],
                'longitude' => $long = $dtp['point']['long'],
                'quadkey' => $tileHandler->getQuadkeyFromGeoCoords($lat, $long),
                'datetime' => $dtp['datetime'],
            ];
        }

        return $accidents;
    }

    private function getDataFromRegionsJson(int $regionId): ?array
    {
        $data = [];

        $this->regionFilenames ??= Region::pluck('dtp_stat_filename', 'id');

        if (! $filename = $this->regionFilenames[$regionId]) {
            return null;
        }

        $filepath = base_path("_backups/datas/dtp-stat-2023-01/$filename.json");
        $jsonData = file_get_contents($filepath);
        $tmp = json_decode($jsonData, 1)['features'];

        foreach ($tmp as $item) {
            $data[$item['properties']['id']] = $item['properties'];
        }

        return $data;
    }

    private function checkParticipantModelsExisting(?array $data): bool
    {
        $models = [
            'role' => 'participant_roles',
            'violations' => 'violations',
            'health_status' => 'health_statuses',
        ];

        $modelArrays = $this->getParticipantModelsArrays($data, $models);

        //df(tmr(@$this->start), $modelArrays);
        foreach ($modelArrays as $table => $names) {
            $existedNames = DB::table($table)->pluck('name')->toArray();
            $missedNames = array_diff($names, $existedNames);

            if ($missedNames) {
                $missedNames = array_map(fn($v) => ['name' => $v], $missedNames);
                //df(tmr(@$this->start), $missedNames);

                DB::table($table)->insert($missedNames);
                df(tmr(@$this->start), $table, $missedNames);
            }
        }

        return true;
    }

    private function getParticipantModelsArrays(?array $data, $models): array
    {
        $modelArrays = [];

        foreach ($data as $dtp) {
            $participants = $dtp['participants'];

            foreach ($dtp['vehicles'] as $vehicle) {
                $participants = [...$participants, ...$vehicle['participants']];
            }

            foreach ($models as $key => $table) {
                foreach ($participants as $participant) {
                    $modelArrays[$table][] = $participant[$key];
                }
            }
        }

        foreach ($modelArrays as &$array) {
            $array = Arr::flatten($array);
            $array = array_unique($array);
            $array = array_filter($array);
            $array = array_values($array);
            $array = array_map('trim', $array);
        }

        return $modelArrays;
    }

    private function checkVehicleModelsExisting(?array $data): bool
    {
        $models = [
            'brand' => 'vehicle_brands',
            'color' => 'vehicle_colors',
            'model' => 'vehicle_models',
            'category' => 'vehicle_categories',
        ];

        $modelArrays = $this->getVehicleModelsArrays($data, $models);

        foreach ($modelArrays as $table => $names) {
            $existedNames = DB::table($table)->pluck('name')->toArray();
            $missedNames = array_diff($names, $existedNames);

            if ($missedNames) {
                $missedNames = array_map(fn($v) => ['name' => $v], $missedNames);
                //df(tmr(@$this->start), $missedNames);

                DB::table($table)->insert($missedNames);
                df(tmr(@$this->start), $table, $missedNames);
            }
        }

        return true;
    }

    private function getVehicleModelsArrays(?array $data, $models): array
    {
        $modelArrays = [];

        foreach ($data as $dtp) {
            $vehicles = $dtp['vehicles'];

            foreach ($models as $key => $table) {
                foreach ($vehicles as $vehicle) {
                    $modelArrays[$table][] = $vehicle[$key];
                }
            }
        }

        foreach ($modelArrays as &$array) {
            $array = Arr::flatten($array);
            $array = array_unique($array);
            $array = array_filter($array);
            $array = array_values($array);
            $array = array_map('trim', $array);
        }

        return $modelArrays;
    }

    private function getRelatedModelsArrays(?array $data, $models): array
    {
        $modelArrays = [];

        foreach ($data as $dtp) {
            foreach ($models as $key => $table) {
                if (strpos($key, '.')) {
                    $modelArrays[$table][] = data_get($dtp, $key);
                } else {
                    $modelArrays[$table][] = $dtp[$key];
                }
            }
        }

        foreach ($modelArrays as &$array) {
            $array = Arr::flatten($array);
            $array = array_unique($array);
            $array = array_values($array);
        }

        return $modelArrays;
    }

    private function getParticipantToDbFromDtpParticipant(array $participant, int $dtpId, int $participantKey, ?int $vehicleKey, Collection $healthStatusIds): array
    {
        $participantToDb['id'] = is_null($vehicleKey) ? "$dtpId-$participantKey" : "$dtpId-$vehicleKey-$participantKey";
        $participantToDb['role'] = $participant['role'];
        $participantToDb['gender'] = ($participant['gender'] === 'Мужской');
        $participantToDb['accident_id'] = $dtpId;
        $participantToDb['vehicle_id'] = is_null($vehicleKey) ? null : "$dtpId-$vehicleKey";
        $participantToDb['health_status_id'] = @$healthStatusIds[$participant['health_status']];
        $participantToDb['years_of_driving_experience'] = @$participant['years_of_driving_experience'];

        return $participantToDb;
    }

    private function getParticipantViolations(array $violations, Collection $violationsIds, string $participantId): array
    {
        $result = [];

        foreach ($violations as $violation) {
            $result[] = [
                'participant_id' => $participantId,
                'violation_id' => $violationsIds[$violation],
            ];
        }

        return $result;
    }
}
