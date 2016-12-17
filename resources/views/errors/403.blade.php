<!DOCTYPE html>
<html>
    <head>
        <title>403-Forbidden.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
				@include('main.head')
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
          <div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card-panel red z-depth-1">
                <h2 class="valign white-text">ไม่สามารถเข้าถึงข้อมูลนี้ได้</h2>
                <h4 class="valign white-text">เนื่องจากคุณไม่ได้รับสิทธิ์ในการเข้าถึงข้อมูล</h4>
                
                <a class="waves-effect waves-light btn blue" href="{{ url('index') }}">พาฉันกลับไป</a>

            </div>
          </div>
        </div>
    </body>
</html>
