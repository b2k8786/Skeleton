<!docType HTML>
<html>
    <head>
        <title>Error</title>
        <style>
            body
            {
                margin: 0px;
            }
            * {
                font-family: arial;
            }
            section {
                padding: 0px 31px;
            }
            .error
            {
                margin: 60px 0 0;
                /*text-align: center;*/
                font-size: 50px;
                color: #444;
                font-weight: bold;
            }
            .message
            {
                margin: 0;
                color:#444;
                font-size: 28px;
                /*text-align: center;*/
            }
            
            .logo {
                width: 64px;                
            }
            header * {
                display: inline-block;
                vertical-align: middle;
            }
            header {
                padding: 0px 30px;
                border-bottom: 1px dashed #8E8E8E;
                margin-bottom: 50px;
            }
            header .title {
                margin-left: 22px;
            }
        </style>
    </head>
    <body>
        <header>
            <img class="logo" src="_pages/logo.svg"/><span class="title">Skeleton</span>
        </header>
        <section>            
            <h3 class="error">Fatal Error</h3>          
            <p class='message' class='message'><?php echo @$error["message"]; ?></p>
            <p class='text'><?php echo @$error["file"]; ?> at <?php echo @$error["line"]; ?></p>            
        </section>
        <footer>

        </footer>
    </body>
</html>