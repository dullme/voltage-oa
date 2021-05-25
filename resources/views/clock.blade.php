<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Voltage-Clock</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body>
    <body class="antialiased" style="background-color: #f7fafc;padding: 10px">
        <div style="display: flex;flex-wrap: wrap;justify-content: center">
            @foreach($clocks as $clock)
            <div style="margin:10px 5px;background-color: #ffffff;border-radius: .5rem;box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06);min-width: 448px">
                <div class="p-6">
                    <div class="flex items-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>

                        <div class="ml-4 text-lg leading-7 font-semibold" style="width:90%;display: flex;justify-content: space-between">
                            <div class="text-gray-900 dark:text-white" title="{{ $clock->name }}">{{ mb_strlen($clock->name) > 15 ? mb_strcut($clock->name, 0, 15) . '...' : $clock->name }}</div>
                            <span class="text-gray-900 dark:text-white"></span>
                            <div class="text-gray-900 dark:text-white date" style="font-size: 14px">2021-05-14</div>
                        </div>
                    </div>
                    <div class="ml-12">
                        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm" style="max-width: 322px">
                            <div style="font-size: 12px;background-color: #e2e8f0; border-radius: 2px;padding: 0px 4px; display: unset">{{ $clock->display }}, UTC {{ $clock->time_zone > 0 ? '+'.$clock->time_zone : $clock->time_zone   }}</div>
                            {{ $clock->remark }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

{{--        <div style="display: flex;justify-content: center">--}}
{{--            <div style="margin:10px 5px;background-color: #ffffff;border-radius: .5rem;box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06);">--}}
{{--                <a href="##" style="font-size: 73px;color: #a0aec0;padding: 0 35px">+</a>--}}
{{--            </div>--}}
{{--        </div>--}}
    </body>
        <script>
            //获取到span
            var spans = document.getElementsByTagName("span");
            var dates = document.getElementsByClassName("date");
            //定义星期数组
            var arr = ["Sun","Mon","Tue","Wed","Thur","Fri","Sat"];
            //有名函数
            function fn(){
                var date = new Date();
                //获得中时区的时间（毫秒）
                var UTCTime = Date.now() + date.getTimezoneOffset()*60*1000;

                var spec1_default = '{{ $clocks->pluck('time_zone') }}'
                spec1_default = spec1_default.substr(0, spec1_default.length -1);
                spec1_default = spec1_default.substr(1);
                spec1_default = spec1_default.split(",");

                var timeArr = new Array()
                var timeArrDate = new Array()
                for( var i = 0;i<spec1_default.length;i++ ){
                    let time = 60*60*1000
                    if(spec1_default[i] != 0){
                        time = time * spec1_default[i]
                    }
                    timeArr[i] = getTime(UTCTime + time)
                    timeArrDate[i] = getDate(UTCTime + time)
                }

                // //定义数组
                // var timeArr = [
                //     getTime(UTCTime + 60*60*1000),     //伦敦
                //     getTime(date),   //北京
                //     getTime(UTCTime + 11*60*60*1000),  //悉尼
                //     getTime(UTCTime - 7*60*60*1000),   //洛杉矶
                //     getTime(UTCTime + 6*60*60*1000),   //印度
                //     getTime(UTCTime + 4*60*60*1000),   //迪拜
                //     getTime(UTCTime + 2*60*60*1000)    //西班牙
                // ];
                //
                // var timeArrDate = [
                //     getDate(UTCTime + 60*60*1000),     //伦敦
                //     getDate(date),   //北京
                //     getDate(UTCTime + 11*60*60*1000),  //悉尼
                //     getDate(UTCTime - 7*60*60*1000),   //洛杉矶
                //     getDate(UTCTime + 6*60*60*1000),   //印度
                //     getDate(UTCTime + 4*60*60*1000),   //迪拜
                //     getDate(UTCTime + 2*60*60*1000)    //西班牙
                // ];
                //打印到控制台
                // console.log(timeArr);
                //添加数据
                for( var i = 0;i<spans.length;i++ ){
                    spans[i].innerHTML = timeArr[i];
                    dates[i].innerHTML = timeArrDate[i];
                }
            }
            //执行
            fn();
            //多次执行的定时器
            setInterval(fn,1000);
            //传入形参  计算年月日....
            function getTime(d){
                var date = new Date(d);
                var hh = addZero(date.getHours());
                var mm = addZero(date.getMinutes());
                var ss = addZero(date.getSeconds());
                return  hh + ":" + mm + ":" + ss
            }

            function getDate(d){
                var date = new Date(d);
                var YY = date.getFullYear();
                var MM = date.getMonth() + 1 ;
                var DD = addZero(date.getDate());
                return  YY + "-" + MM + "-" + DD
            }

            function getDay(d){
                var date = new Date(d);
                var Day = date.getDay(); //星期三？？？
                return  arr[Day]
            }
            //使输出的格式为双数
            function addZero ( n ){
                return n < 10 ? "0" + n : n + "";
            }
        </script>
    </body>
</html>
