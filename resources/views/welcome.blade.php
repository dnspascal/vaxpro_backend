<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @vite('resources/js/app.js')

    <h1>User id is {{Auth::id()}}</h1>
</body>
<script>
   
    //  setTimeout(() => {
    //         window.Echo.channel('testChannel')
    //             .listen('testingEvent', (e) => {
    //                 console.log(e);
    //             })
    //     }, 200);

    setTimeout(() => {
            window.Echo.private('private.{{Auth::id()}}')
                .listen('PrivateEvent', (e) => {
                    console.log(e);
                })
        }, 200);
</script>
</html>