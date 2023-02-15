<script setup>
import {ref} from "vue";

let showData = ref(false);
</script>

<template>
    <Head title="Скачать данные"/>
    <h2 class="mt-12 text-center text-3xl font-bold text-[#18334A]">Скачать данные</h2>
    <div class="download_page mx-auto pt-8 lg:max-w-[900px] md:max-w-[720px] max-w-[540px] px-[15px] text-[#18334A]">
        <div class="post mt-5 mb-5 ml-auto mr-auto">
            <p>Здесь вы можете скачать данные по ДТП, чтобы использовать их в исследованиях и анализировать с помощью
                своих инструментов.&nbsp;Первоисточник данных – официальный сайт ГИБДД, но эти данные приведены с&nbsp;изменениями
                (например, корректировки координат).</p>
            <br>
            <p>
                <button class="underline hover:text-[#0056b3]" @click="showData = !showData">
                    Структура данных
                </button>
            </p>
            <div v-if="showData">
                <div class="border px-4 overflow-x-auto text-sm">
                    <pre><code><samp>
{
    "id": 384094, # идентификатор
    "tags": ["Дорожно-транспортные происшествия"], # показатели с официального сайта ГИБДД
    "light": "Светлое время суток", # время суток
    "point": {"lat": 50.6039, "long": 36.5578}, # координаты
    "nearby": [ "Нерегулируемый перекрёсток неравнозначных улиц (дорог)", "Жилые дома индивидуальной застройки"], # координаты
    "region": "Белгород", # город/район
    "address": "г Белгород, ул Сумская, 30", # адрес
    "weather": ["Ясно"], # погода
    "category": "Столкновение", # тип ДТП
    "datetime": "2017-08-05 13:06:00", # дата и время
    "severity": "Легкий", # тяжесть ДТП/вред здоровью
    "vehicles": [ # участники – транспортные средства
      {
        "year": 2010, # год производства транспортного средства
        "brand": "ВАЗ", # марка транспортного средства
        "color": "Иные цвета", # цвет транспортного средства
        "model": "Priora", # модель транспортного средства
        "category": "С-класс (малый средний, компактный) до 4,3 м", # категория транспортного средства
        "participants": [ # участники внутри транспортных средств
          {
            "role": "Водитель", # роль участника
            "gender": "Женский", # пол участника
            "violations": [], # нарушения правил участником
            "health_status": "Раненый, находящийся...", # состояние здоровья участника
            "years_of_driving_experience": 11 # стаж вождения участника (только у водителей)
          }
        ]
      },
    ],
    "dead_count": 0, # кол-во погибших в ДТП
    "participants": [], # участники без транспортных средств (описание, как у участников внутри транспортных средств)
    "injured_count": 2, # кол-во раненых в ДТП
    "parent_region": "Белгородская область", # регион
    "road_conditions": ["Сухое"], # состояние дорожного покрытия
    "participants_count": 3, # кол-во участников ДТП
    "participant_categories": ["Все участники", "Дети"] # категории участников
  }
                            </samp></code></pre>
                </div>
            </div>

            <table class="table">
                <tbody>
                <tr>
                    <td><b>Алтайский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">47.60 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/altaiskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Амурская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">19.77 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/amurskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Архангельская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">22.17 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/arkhangelskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Астраханская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">20.98 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/astrakhanskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Белгородская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">19.76 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/belgorodskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Брянская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">16.86 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/brianskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Владимирская область</b></td>
                    <td class="small">январь 2015 - июнь 2022</td>
                    <td class="small">34.81 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/vladimirskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Волгоградская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">39.15 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/volgogradskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Вологодская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">25.47 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/vologodskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Воронежская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">47.63 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/voronezhskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Еврейская автономная область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">3.55 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/evreiskaia-avtonomnaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Забайкальский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">21.60 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/zabaikalskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ивановская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">21.51 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/ivanovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Иркутская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">49.05 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/irkutskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Кабардино-Балкарская Республика</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">10.13 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kabardino-balkarskaia-respublika.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Калининградская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">17.85 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kaliningradskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Калужская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">24.69 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kaluzhskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Камчатский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">8.37 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kamchatskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Карачаево-Черкесская Республика</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">8.28 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/karachaevo-cherkesskaia-respublika.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Кемеровская область - Кузбасс</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">48.20 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kemerovskaia-oblast-kuzbass.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Кировская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">27.68 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kirovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Костромская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">12.62 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kostromskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Краснодарский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">103.17 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/krasnodarskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Красноярский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">63.52 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/krasnoiarskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Курганская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">17.42 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kurganskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Курская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">24.50 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/kurskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ленинградская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">43.76 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/leningradskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Липецкая область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">23.33 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/lipetskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Магаданская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">3.97 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/magadanskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Москва</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">141.02 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/moskva.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Московская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">92.53 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/moskovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Мурманская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">13.18 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/murmanskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ненецкий автономный округ</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">0.50 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/nenetskii-avtonomnyi-okrug.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Нижегородская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">82.42 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/nizhegorodskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Новгородская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">17.75 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/novgorodskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Новосибирская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">41.19 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/novosibirskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Омская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">45.76 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/omskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Оренбургская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">33.08 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/orenburgskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Орловская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">13.73 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/orlovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Пензенская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">31.83 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/penzenskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Пермский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">49.49 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/permskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Приморский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">43.78 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/primorskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Псковская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">15.76 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/pskovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Адыгея (Адыгея)</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">7.91 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-adygeia-adygeia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Алтай</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">5.32 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-altai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Башкортостан</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">65.67 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-bashkortostan.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Бурятия</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">18.98 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-buriatiia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Дагестан</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">25.61 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-dagestan.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Ингушетия</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">3.66 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-ingushetiia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Калмыкия</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">7.73 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-kalmykiia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Карелия</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">11.00 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-kareliia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Коми</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">17.08 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-komi.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Крым</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">30.42 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-krym.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Марий Эл</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">14.09 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-marii-el.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Мордовия</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">15.09 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-mordoviia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Саха (Якутия)</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">14.34 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-sakha-iakutiia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Северная Осетия-Алания</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">13.02 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-severnaia-osetiia-alaniia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Татарстан (Татарстан)</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">71.82 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-tatarstan-tatarstan.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Тыва</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">8.96 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-tyva.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Республика Хакасия</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">9.96 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/respublika-khakasiia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ростовская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">57.17 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/rostovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Рязанская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">30.56 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/riazanskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Самарская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">57.95 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/samarskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Санкт-Петербург</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">91.45 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/sankt-peterburg.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Саратовская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">49.48 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/saratovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Сахалинская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">10.87 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/sakhalinskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Свердловская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">48.79 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/sverdlovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Севастополь</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">9.63 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/sevastopol.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Смоленская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">15.43 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/smolenskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ставропольский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">47.26 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/stavropolskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Тамбовская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">23.38 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/tambovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Тверская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">28.41 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/tverskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Томская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">11.24 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/tomskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Тульская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">34.76 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/tulskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Тюменская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">43.85 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/tiumenskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Удмуртская Республика</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">29.26 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/udmurtskaia-respublika.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ульяновская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">21.64 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/ulianovskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Хабаровский край</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">30.72 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/khabarovskii-krai.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ханты-Мансийский автономный округ - Югра</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">28.55 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/khanty-mansiiskii-avtonomnyi-okrug-iugra.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Челябинская область</b></td>
                    <td class="small">январь 2015 - июнь 2022</td>
                    <td class="small">62.75 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/cheliabinskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Чеченская Республика</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">4.40 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/chechenskaia-respublika.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Чувашская Республика - Чувашия</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">21.99 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/chuvashskaia-respublika-chuvashiia.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Чукотский автономный округ</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">0.35 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/chukotskii-avtonomnyi-okrug.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ямало-Ненецкий автономный округ</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">7.63 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/iamalo-nenetskii-avtonomnyi-okrug.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                <tr>
                    <td><b>Ярославская область</b></td>
                    <td class="small">январь 2015 - ноябрь 2022</td>
                    <td class="small">29.05 mb</td>
                    <td class="text-center"><a href="https://cms.dtp-stat.ru/media/opendata/iaroslavskaia-oblast.geojson" class="button">Скачать&nbsp;.geojson</a></td>
                </tr>

                </tbody>
            </table>

        </div>
    </div>
</template>

<style scoped>
.download_page p {
    text-align: justify;
    margin-bottom: 12px;
}

.download_page table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.download_page table td, .download_page table th {
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.download_page .small {
    font-size: 80%;
    font-weight: 400;
}
a.button {
    background: #EBEEF0;
}
.button, .button:hover {
    border-radius: 20px;
    padding: 8px 16px 8px 16px !important;
    text-decoration: none;
    color: inherit;
    font-weight: 500;
}
</style>
