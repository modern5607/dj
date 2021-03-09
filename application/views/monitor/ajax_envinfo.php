<table>
    <tr>
        <td>
            <div id="chart1" style="border: 1px solid; width:1200px;  height: 400px;"></div>
        </td>
        <tr>
        <td>
            <div id="chart2" style="border: 1px solid; width:1200px; height: 400px;"></div>
        </td>
        </tr>
        
    </tr>

</table>

<script>
    google.charts.setOnLoadCallback(drawBasic1);
    google.charts.setOnLoadCallback(drawBasic2);


//위치 1번 그래프
    function drawBasic1() {
        var data1 = new google.visualization.DataTable();
        //그래프에 표시할 컬럼 추가
        data1.addColumn('datetime', '날짜');
        data1.addColumn('number', '온도(°C)');
        data1.addColumn('number', '습도(%)');

        //그래프에 표시할 데이터
        var dataRow = [];

        var TEMPMAX = -100;
        var TEMPMIN = 100;
        var HUMMAX = -100;
        var HUMMIN = 100;
        <?php foreach ($envslist1 as $row) { ?>
            var year = <?= date("Y", strtotime($row->DATE)) ?>;
            var month = <?= date("m", strtotime($row->DATE)) ?>;
            var day = <?= date("d", strtotime($row->DATE)) ?>;
            var hour = <?= date("H", strtotime($row->DATE)) ?>;
            var min = <?= date("i", strtotime($row->DATE)) ?>;
            var sec = <?= date("s", strtotime($row->DATE)) ?>;
            var loc = <?= $row->LOCATION; ?>;
            var temp=<?=$row->TEMP?>;
            var hum=<?=$row->HUM?>;

            if(temp>TEMPMAX)
            TEMPMAX = temp;
            if(temp<TEMPMIN)
            TEMPMIN = temp;

            if(hum>HUMMAX)
            HUMMAX = hum;
            if(hum<HUMMIN)
            HUMMIN = hum;
            




            var date = new Date(year, month, day, hour, min, sec);
            dataRow = [date, <?= $row->TEMP ?>, <?= $row->HUM ?>];
            data1.addRow(dataRow);
        <?php } ?>

        var MAX = (TEMPMAX>HUMMAX)?TEMPMAX:HUMMAX;
        var MIN = (TEMPMIN<HUMMIN)?TEMPMIN:HUMMIN;

        console.log(MAX,MIN);

        var tmpOption = {

            hAxis: {
                format: 'hh:mm',
                gridlines: {
                    count: 1,
                    units: {
                        years: {
                            format: ['yyyy년']
                        },
                        months: {
                            format: ['MM월']
                        },
                        days: {
                            format: ['dd일']
                        },
                        hours: {
                            format: ['HH시']
                        },
                        minutes: {
                            format: ['mm분']
                        },
                        second: {
                            format: ['ss초']
                        },
                    }
                }
            },

            vAxes: [{
                title: "기온",
                viewWindow: {
                    min: MIN-5,
                    max:MAX+5
                }
            }],
            series: [{
                targetAxisIndex: 0,
                pointSize: 0
            }],

            focusTarget: 'category',
            smoothLine: true
            //crosshair: { trigger: 'selection' },

        };

        var options1 = tmpOption;
        options1.title = "A동 온습도 현황";
        var chart1 = new google.visualization.LineChart(document.getElementById('chart1'));
        window.addEventListener('resize', function() {
            options1.title = "A동 온습도 현황";
            chart1.draw(data1, options1);
        }, false); //화면 크기에 따라 그래프 크기 변경
        chart1.draw(data1, options1);

    }




//위치 2번 그래프
    function drawBasic2() {
        var data1 = new google.visualization.DataTable();
        //그래프에 표시할 컬럼 추가
        data1.addColumn('datetime', '날짜');
        data1.addColumn('number', '온도(°C)');
        data1.addColumn('number', '습도(%)');

        //그래프에 표시할 데이터
        var dataRow = [];

        var TEMPMAX = -100;
        var TEMPMIN = 100;
        var HUMMAX = -100;
        var HUMMIN = 100;
        <?php foreach ($envslist2 as $row) { ?>
            var year = <?= date("Y", strtotime($row->DATE)) ?>;
            var month = <?= date("m", strtotime($row->DATE)) ?>;
            var day = <?= date("d", strtotime($row->DATE)) ?>;
            var hour = <?= date("H", strtotime($row->DATE)) ?>;
            var min = <?= date("i", strtotime($row->DATE)) ?>;
            var sec = <?= date("s", strtotime($row->DATE)) ?>;
            var loc = <?= $row->LOCATION; ?>;
            var temp=<?=$row->TEMP?>;
            var hum=<?=$row->HUM?>;

            if(temp>TEMPMAX)
            TEMPMAX = temp;
            if(temp<TEMPMIN)
            TEMPMIN = temp;

            if(hum>HUMMAX)
            HUMMAX = hum;
            if(hum<HUMMIN)
            HUMMIN = hum;
            




            var date = new Date(year, month, day, hour, min, sec);
            dataRow = [date, <?= $row->TEMP ?>, <?= $row->HUM ?>];
            data1.addRow(dataRow);
        <?php } ?>

        var MAX = (TEMPMAX>HUMMAX)?TEMPMAX:HUMMAX;
        var MIN = (TEMPMIN<HUMMIN)?TEMPMIN:HUMMIN;

        console.log(MAX,MIN);

        var tmpOption = {

            hAxis: {
                format: 'hh:mm',
                gridlines: {
                    count: 1,
                    units: {
                        years: {
                            format: ['yyyy년']
                        },
                        months: {
                            format: ['MM월']
                        },
                        days: {
                            format: ['dd일']
                        },
                        hours: {
                            format: ['HH시']
                        },
                        minutes: {
                            format: ['mm분']
                        },
                        second: {
                            format: ['ss초']
                        },
                    }
                }
            },

            vAxes: [{
                title: "기온",
                viewWindow: {
                    min: MIN-5,
                    max:MAX+5
                }
            }],
            series: [{
                targetAxisIndex: 0,
                pointSize: 0
            }],

            focusTarget: 'category',
            smoothLine: true
            //crosshair: { trigger: 'selection' },

        };

        var options1 = tmpOption;
        options1.title = "B동 온습도 현황";
        var chart1 = new google.visualization.LineChart(document.getElementById('chart2'));
        window.addEventListener('resize', function() {
            options1.title = "B동 온습도 현황";
            chart1.draw(data1, options1);
        }, false); //화면 크기에 따라 그래프 크기 변경
        chart1.draw(data1, options1);

    }


    $(document).ready(function() {})
</script>

</html>