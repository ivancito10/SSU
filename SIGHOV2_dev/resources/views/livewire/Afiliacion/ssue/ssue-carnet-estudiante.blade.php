<!DOCTYPE html>
<html>
<head>
    <title> SSUE Carnet Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            background-color: #f2f2f2;
            font-family: "Ubuntu",sans-serif;
            
            }
            .profile-card-1{
                width: 300px;
                height: 460px;
                background: white;
                margin: 0 auto;
                border-radius: 10px;
                text-align: center;
                box-shadow: 4px 4px 10px #999;
                position: relative;
                box-sizing: border-box;
                overflow: hidden;
            }
            .profile-card-1 .img img{
                width: 160px;
                height: 160px;
                padding: 3px;
                border-radius: 50%;
                border: 3px solid rgba(255,255,255,0.6);
                position: absolute;
                left: calc(50% - 84px);
                top: 26px;
            }
            .profile-card-1 .img {
                height: 130px;
                width: 100%;
                background-image: linear-gradient(rgba(100, 144, 211, 0.8), rgba(5, 34, 78, 0.97)), url("bac.jpg");
                padding: 20px;
                box-sizing: border-box;
                position: relative;
            }

            .profile-card-1 .mid-section{
                position: absolute;
                height: 200px;
                width: 100%;
                top: 200px;
                left: 0;
                padding: 10px 20px 0;
                box-sizing: border-box;
                background: white;
            }
            .profile-card-1 .mid-section .name{
                color: #333333;
                font-size: 1.4em;
                padding-top: 5px;
                background: rgba(255,255,255,0.1);
                font-weight: bold;
            }
            .profile-card-1 .mid-section .description{
                color: black;
                text-decoration: none; 
                font-size: 14px;
            }
            .profile-card-1 .mid-section .datos{
                color: black ;
                text-decoration: none; 
                font-size: 14px;
                text-align:left;
            }

            .profile-card-1 .mid-section .description a{
                color: inherit;
                text-decoration: none;
                font-weight: bold;
            }
            .profile-card-1 .mid-section .line{
                background: #1a5276;
                width: 80%;
                height: 2px;
                margin: 5px auto -3px;
            }
            .profile-card-1 .mid-section .stats{
                display: flex;
                position: absolute;
                left: 10%;
                padding-top: 10px;
                width: 80%;
                justify-content: space-around;
            }
            .profile-card-1 .mid-section .stats .stat{
                font-size: 1.3em;
                color: #333333;
                padding: 5px;
                font-weight: bold;
            }
            .profile-card-1 .mid-section .stats .stat .subtext{
                color: gray;
                font-size: 0.6em;
                font-weight: normal;
            }

            .profile-card-1 .links{
                width: 100%;
                height: 46px;
                background: #f2f2f2;
                border-top: 1px solid white;
                margin-top: 35px;
                border-radius: 5px;
                color: white;
                font-size: 13px; 
                position: absolute;
                bottom: 0;
                text-align:center;
                
            }
            .profile-card-1 .links .fb{
                position: absolute;
                left: 0;
                width: 50%;
                height: 100%;

                background-color: #3b5998;
                
            }
            .profile-card-1 .links .fb:hover{
                background-color: #2162db;
                
            }
            .profile-card-1 .links p{
                color: white;
            }
            
            .profile-card-1 .links .follow {
                position: absolute;
                left: 50%;
                width: 50%;
                height: 100%;

                background-color: #cb4335;
            }
            .profile-card-1 .links .follow:hover{
                background-color: #e74c3c ;
            }
            /* image after effect */
            .profile-card-1 .img::after{
                content: "";
                position: absolute;
                width: 180px;
                height: 180px;
                border-radius: 50%;
                left:calc(50% - 91.5px);
                top: 20px;
                border: 3px solid rgba(255,255,255,0.4);
            }
            /* image before effect */
            .profile-card-1 .img::before{
                content: "";
                position: absolute;
                width: 190px;
                height: 190px;
                border-radius: 50%;
                left: calc(50% - 95px);
                top: 15.5px;
                border: 2px solid rgba(255,255,255,0.2);
            }
            .profile-card-1 .view-more{
                position: absolute;
                top: calc(50% - 0.5em);
                left: calc(50% - 0.5em);
                z-index: 2;
                font-size: 2em;
                color: #2ecc71;
            }
            .profile-card-1 .view-more .fa-plus-circle{
                position: absolute;
                background: white;
                border-radius: 50%;
                width: 1.004em;
            }
        .popup-container {
            display: flex; /* Visible cuando $mostrarCarnet es true */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="popup-container" id="carnetPopup">
 
        <div class="profile-card-1">
            <div class="img">
                <img src="data:image/png;base64,{{ $foto_estudiante }}" >
            </div>
            <!-- <a class="view-more" href="#">
                <i class="fa fa-plus-circle"></i>
            </a> -->
            <div class="mid-section">
                <div class="name">
                    {{$matricula}} -  {{$ci}} 
                </div>
                <div class="description">
                    <em>{{$nombres}} {{$p_apellido}} {{$s_apellido}} </em><br>
                    <span>  <b>{{$edad}}</b> Años </span>
                </div>
                <div class="line"></div>
                <br>
                <div class="datos"> 
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <td>
                                    <b>R.U: </b>
                                </td>
                                <td class="px-3">{{$registro_universitario}} </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>FACULTAD:</b>
                                </td>
                                <td class="px-3">{{ $facultad }} </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>CARRERA: </b>
                                </td>
                                <td class="px-3">{{ $carrera }} </td>
                            </tr>
                            
                        </table>
                    </div>

                </div>
                

            </div>
            <div class="links">
                <p class="fb" href="" >
                    <span>Fecha Afiliación:</span><br>
                    <span>{{$fecha_afiliacion}}</span>
                </p>
                
                <p class="follow" href="">
                    <span>Fecha Vencimiento: </span><br>
                    <span>{{$fecha_vencimiento}}</span>
                </p>

            </div>
    
        </div>
    </div>
</body>
</html>
