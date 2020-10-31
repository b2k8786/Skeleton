<!docType HTML>
<html>
    <head>
        <title>Welcome</title>
        <style>
            body
            {
                margin: 0px;
            }
            * {
                font-family: arial;
            }
            .error
            {
                margin: 60px 0 0;
                text-align: center;
                font-size: 50px;
                color: #444;
                font-weight: bold;
            }
            .message
            {
                margin: 0;
                color:#444;
                font-size: 50px;
                text-align: center;
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
            <img class="logo" src="<?=BASE_URL?>_pages/logo.svg"/><span class="title">Skeleton</span>
        </header>
        <section>
            <h1 class='message'>Welcome to Skeleton</h1>
        </section>
        <footer>

        </footer>
    </body>
</html>