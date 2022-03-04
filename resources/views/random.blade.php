<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>Drawing sector</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <style>
        #turntable{
            position: relative;
        }
        #canvas{
            /* border: 1px solid #000; */
            -webkit-transform: all 6s ease;
            transition: all 6s ease;
        }
        #btn{
            position: absolute;
            left: 120px;
            top: 120px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #fff;
            line-height: 60px;
            text-align: center;
        }
        #btn:after{
            position: absolute;
            display: block;
            content: '';
            left: 10px;
            top: -32px;
            width: 0;
            height: 0;
            overflow: hidden;
            border-width: 20px;
            border-style: solid;
            border-color: transparent;
            border-bottom-color: #fff;
        }
    </style>
</head>
<body>
<div style="margin-top: 50px; margin-left: 30px">
    <div id="turntable">
        <canvas id="canvas" width="300" height="300">抱歉！浏览器不支持。</canvas>
        <a id="btn" href="javascript:;" style="text-decoration: unset;color: #0b0b0b">开饭</a>
    </div>
</div>

    <script>
        let data = [
            '好吃的鱼',
            '好吃的饭',
            '海南鸡',
            '西北楼',
            '海鲜面',
            '米鱼计',
            '扬城',
            '大食堂',
        ]

        window.addEventListener('load', function(){
            var num = data.length;   // 奖品数量
            var canvas = document.getElementById('canvas');
            var btn = document.getElementById('btn');
            if(!canvas.getContext){
                alert('抱歉！浏览器不支持。');
                return;
            }
            // 获取绘图上下文
            var ctx = canvas.getContext('2d');
            for (var i = 0; i < num; i++) {
                // 保存当前状态
                ctx.save();
                // 开始一条新路径
                ctx.beginPath();
                // 位移到圆心，下面需要围绕圆心旋转
                ctx.translate(150, 150);
                // 从(0, 0)坐标开始定义一条新的子路径
                ctx.moveTo(0, 0);
                // 旋转弧度,需将角度转换为弧度,使用 degrees * Math.PI/180 公式进行计算。
                ctx.rotate((360 / num * i + 360 / num / 2) * Math.PI/180);
                // 绘制圆弧
                ctx.arc(0, 0, 150, 0,  2 * Math.PI / num, false);
                if (i % 2 == 0) {
                    ctx.fillStyle = '#ffb820';
                }else{
                    ctx.fillStyle = '#ffcb3f';
                }
                // 填充扇形
                ctx.fill();
                // 绘制边框
                ctx.lineWidth = 0.5;
                ctx.strokeStyle = '#f48d24';
                ctx.stroke();

                // 文字
                ctx.fillStyle = '#fff';
                ctx.font="16px sans-serif";
                ctx.fillText(data[i], 60, 35);

                // 恢复前一个状态
                ctx.restore();
            }
            var currentRotate = 0;
            var nextRotate = 0;
// 抽奖
            btn.onclick = function(){
                currentRotate = nextRotate;
                let random_data = [
                    Math.random()*45-22.5,//6
                    Math.random()*45+22.5,//5
                    Math.random()*45+67.5,//4
                    Math.random()*45+112.5,//3
                    Math.random()*45+157.5,//3
                    Math.random()*45+202.5,//1
                    Math.random()*45+247.5,//8
                    Math.random()*45+292.5,//7
                ]

                var h_num = 360 - random_data[Math.floor((Math.random()*data.length)+1)];
                currentRotate += 360*5+num;
                nextRotate = currentRotate + h_num;
                canvas.style.transform = 'rotate('+currentRotate+'deg)';
            }
        }, false);
    </script>
</body>
</html>
