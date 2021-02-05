<html>

<head></head>
<script>
    var text = null;
    window.onload(httpGet());

    function httpGet() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", "http://localhost/ok.php", false); // false for synchronous request
        xmlHttp.send(null);
        // console.log(xmlHttp);
        // console.log(xmlHttp.responseText);
        if (xmlHttp.status == 200) {
            text = xmlHttp.response;
        }
        // return xmlHttp.responseText;
    }
</script>

<body (onload)="httpGet()">
    <div>
        <script>
            document.write(text);
        </script>
    </div>

</body>

</html>