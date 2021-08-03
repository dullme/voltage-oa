<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voltage-Software</title>
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
        .box{
            display: flex;
            padding: 15px 30px;
        }

        .box img{
            width: 32px;
            height: 32px;
        }
    </style>
</head>
<body>
<div style="margin: 0 auto;text-align: center;padding-top: 80px;font-size: 40px;color: #4b4b52">Download</div>
<div style="width: 80%;display: flex;flex-wrap:wrap;margin: 10px auto;padding: 20px">
    <a href="https://down.qq.com/qqweb/PCQQ/PCQQ_EXE/PCQQ2021.exe" target="_blank" title="https://im.qq.com">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/qq.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">QQ</div>
        </div>
    </a>

    <a href="https://dldir1.qq.com/weixin/Windows/WeChatSetup.exe" target="_blank" title="https://weixin.qq.com">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/wechat.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">微信</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/dVhWEZSLt" target="_blank" title="https://www.dingtalk.com">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/dingtalk.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">钉钉</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/pK5Dit3fu" target="_blank" title="https://www.office.com">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/office.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">Office 365</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/6SGFPqLDT" target="_blank" title="https://www.microsoft.com/zh-cn/microsoft-teams/group-chat-software">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/teams.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">Microsoft Teams</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/RGdL32Zku" target="_blank" title="https://www.google.com/chrome">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/chrome.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">Google Chrome</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/S5vSQ0PKJ" target="_blank" title="https://www.synology.cn">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/drive.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">Synology Drive</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/58blRYr2Z" target="_blank" title="https://www.raidrive.com">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/raidrive.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">RaiDrive</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/ACrInX9bJ" target="_blank" title="https://www.wps.cn">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/wps.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">WPS Office</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/XAEdxdTkb" target="_blank" title="https://pinyin.sogou.com">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/sougou.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">搜狗输入法</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/lUr9JXRSu" target="_blank" title="https://www.rarlab.com">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/winrar.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">WinRAR</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/MmSEGHwXE" target="_blank" title="https://www.adobe.com/cn/acrobat/pdf-reader.html">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/reader.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">Adobe Acrobat Reader DC</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/2pxLelnXo" target="_blank" title="https://www.canon.com.cn/supports/download/sims/search/index">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/c3120l.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">Canon iR C3120L</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/byHGDNiYI" target="_blank" title="https://support.hp.com/cn-zh/drivers/selfservice/hp-laserjet-5200-printer-series/1137934">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/hp5200.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">HP LaserJet 5200</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/0I4z0idBE" target="_blank" title="https://support-fb.fujifilm.com/setupDriverForm.do?ctry_code=CN&lang_code=zh_CN&d_lang=zh_CN&pid=DPM118FW">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/m118w.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">DocuPrint M118w</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/t0P8egsPJ" target="_blank" title="https://www.brady.com.cn/service/download.htm?keys=i7100">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/brady.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">BRADY i7100</div>
        </div>
    </a>

    <a href="https://pv20140610.synology.me:5001/sharing/DMrA083Jk" target="_blank" title="https://www.nbdeli.com/services/download.html">
        <div class="box">
            <div style="display: flex;align-items: center"><img src="{{ asset('/images/deli1515.png') }}"></div>
            <div style="margin-left: 10px;display: flex;align-items: center">Deli 15155</div>
        </div>
    </a>

</div>

</body>
</html>
