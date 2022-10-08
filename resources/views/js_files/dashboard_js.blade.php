  <script src="assets/bundles/echart/echarts.js"></script>
  <!-- Page Specific JS File -->
  {{-- <script src="assets/js/page/chart-echarts.js"></script> --}}
<script>

    //year_1 year_2_holder year_2 month_ day_ main_select

    main_select_element = document.querySelector("#main_select");
    main_select_element.addEventListener('change', async function(){
        if(main_select_element.value === '{{ $filter_by_year }}'){
            year_2_holder.removeAttribute('hidden');
        }else{
            year_2_holder.setAttribute('hidden', true);
        }
    })

    const filterButton = document.querySelector("#filter_by_values");

    function validateChartDatas({main_select, day_, month_, year_1}){
        let formData = {main_select, day_, month_, year_1};

        let rules = {
            main_select: 'required|string|min:3',
            day_: 'required',
            month_: 'required',
            year_1: 'required'
        };

        let validation = new Validator(formData, rules);
        return validation;
    }
    
    function validateYear2({year_2}){
        let formData = {year_2};

        let rules = {
            year_2: 'required'
        };

        let validation = new Validator(formData, rules);
        return validation;
    }

    filterButton.addEventListener('click', function(){

        const main_select = document.querySelector("#main_select");
        const day_ = document.querySelector("#day_");
        const month_ = document.querySelector("#month_");
        const year_1 = document.querySelector("#year_1");
        const year_2_holder = document.querySelector("#year_2_holder");
        let year_2 = '';
        let url = '';
        getChartData({main_select:main_select.value, day_:day_.value, month_:month_.value, year_1:year_1.value, year_2_holder:year_2_holder.value, year_2:year_2, url:url})

    })

    window.onload = function(){

        const main_select = '{{ $filter_by_day }}';
        const day_ = moment().format("DD");
        const month_ = moment().format("MM");
        const year_1 = moment().format("YYYY");
        const year_2_holder = document.querySelector("#year_2_holder");
        let year_2 = '';
        let url = '';
        getChartData({main_select:main_select, day_:day_, month_:month_, year_1:year_1, year_2_holder:year_2_holder.value, year_2:year_2_holder, url:url})
    }
    
    async function getChartData({main_select, day_, month_, year_1, year_2_holder, year_2, url}){

        const validation = validateChartDatas({main_select, day_, month_, year_1});
        if(validation.fails()){ return validateModule.handleErrorStatement(validation.errors.errors, '../login', 'on'); }

        if(main_select === '{{ $filter_by_year }}'){
            year_2 = document.querySelector("#year_2");
            const validation2 = validateYear2({year_2:year_2.value});
            if(validation2.fails()){ return validateModule.handleErrorStatement(validation2.errors.errors, '../login', 'on'); }

            url = `{{ URL::to('/') }}/chart-data/${main_select}/${year_1}/${year_2.value}`
        }else{
            url = `{{ URL::to('/') }}/chart-data/${main_select}/${year_1+'-'+month_+'-'+day_}`
        }

        const mainText = updateButtonStatus(filterButton, 'get_main_text_and_set_loading');//update the status of the button to loading
        try{
            //set the main tet of the button back to the maintext
            const get_coin_details = await theGetRequest(url);
            const {status, message, data} = get_coin_details;
            updateButtonStatus(filterButton, 'set_main_text', mainText);
            if(status === true){
                //swal("Success!", message, "success");
                const {withdrawal, deposit, x_axis} = data;
                initializeChat({withdrawal, deposit, x_axis})

            }
            if(status === false){
                validateModule.handleErrorStatement(message, '../login', 'on');
            }

        }catch(e){
            updateButtonStatus(filterButton, 'set_main_text', mainText);//set the main tet of the button back to the maintext
            validateModule.handleErrorStatement({general_error:[e]}, '../login', 'on');
        }
    }
//['withdrawal'=>$withdrawalArray, 'deposit'=>$depositArray]
    function initializeChat({withdrawal, deposit, x_axis}){
        /* Chart data*///deposits and with
        var chartdata = [
            {
                name: 'deposits',
                type: 'bar',
                data: deposit//[11, 14, 8, 16, 11, 13]
            },
            // {
            //     name: 'profit',
            //     type: 'line',
            //     smooth: true,
            //     data: [10, 7, 17, 11, 15],
            //     symbolSize: 10,
            // },
            {
                name: 'withdrawals',
                type: 'bar',
                data: withdrawal//[10, 14, 10, 15, 9, 25]
            }
        ];

        /* Bar chart echartopt1*/
        var chart = document.getElementById('echart_bar_line');
        var barChart = echarts.init(chart);

        var option = {
            grid: {
                top: '6',
                right: '0',
                bottom: '17',
                left: '25',
            },
            xAxis: {
                data: x_axis,//['2014', '2015', '2016', '2017', '2018','2019'],
                axisLine: {
                    lineStyle: {
                        color: '#eaeaea'
                    }
                },
                axisLabel: {
                    fontSize: 10,
                    color: '#9aa0ac'
                }
            },
            tooltip: {
                show: true,
                showContent: true,
                alwaysShowContent: false,
                triggerOn: 'mousemove',
                trigger: 'axis',
                axisPointer:
                {
                    label: {
                        show: false,
                    }
                }

            },
            yAxis: {
                splitLine: {
                    lineStyle: {
                        color: '#eaeaea'
                    }
                },
                axisLine: {
                    lineStyle: {
                        color: '#eaeaea'
                    }
                },
                axisLabel: {
                    fontSize: 10,
                    color: '#9aa0ac'
                }
            },
            series: chartdata,
            color: ['#9f78ff', '#fa626b', '#32cafe',]
        };

        barChart.setOption(option);
    }
</script>