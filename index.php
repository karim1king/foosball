<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript" src="validReg.js"> </script>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="ajax.js"> </script>
    <script type="text/javascript" src="date.js"> </script>

    <script type="text/javascript">
        Date.prototype.shortFormat = function() {
            return this.getDate()  + "/" + (this.getMonth() + 1) + "/" + this.getFullYear();
        }
        function Blog(body, date, image) {
            // Assign the properties
            this.body = body;
            this.date = date;
            this.image = image;
        }

            Blog.prototype.toString = function() {
            return "[" + this.date.shortFormat() + "] " + this.body;
        };

        Blog.prototype.toHTML = function(highlight) {
            var blogHTML = "";
            blogHTML += highlight ? "<div style='background-color:rgba(211, 211, 211, 0.56);margin-top: 5px; padding: 7px'>":"<div style='background-color:rgba(173, 255, 109, 0.73);margin-top: 5px; padding: 7px'>";
            if (this.image) {
                blogHTML += "<strong>" + this.date.shortFormat() + "</strong><hr><table><tr><td><img width='150px' height='auto'  src='" +
                this.image + "'/></td><td style='vertical-align:top'>" + this.body + "</td></tr></table><em>" +
                this.signature + "</em></div>";
            }
            else {
                blogHTML += "<strong>" + this.date.shortFormat() + "</strong><hr><br />" + this.body +
                "<br /><em>" + this.signature + "</em></div>";
            }
            return blogHTML;
        };


        Blog.prototype.containsText = function(text) {
            return (this.body.toLowerCase().indexOf(text.toLowerCase()) != -1);
        };

        Blog.prototype.signature = "by admin Karim";

        Blog.blogSorter = function(blog2, blog1)
        {
            return blog1.date - blog2.date;
        };

        var blog = new Array();

        var ajaxReq = new AjaxRequest();

        function loadBlog() {
            document.getElementById("blog").innerHTML = "<img src='wait.gif' alt='Loading...' />";
            ajaxReq.send("GET", "news/blog.xml", handleRequest);
        }

        function handleRequest() {
            if (ajaxReq.getReadyState() == 4 && ajaxReq.getStatus() == 200) {
                var xmlData = ajaxReq.getResponseXML().getElementsByTagName("blog")[0];
                Blog.prototype.signature = "by " + getText(xmlData.getElementsByTagName("author")[0]);

                var entries = xmlData.getElementsByTagName("entry");
                for (var i = 0; i < entries.length; i++) {
                    var list = getText(entries[i].getElementsByTagName("date")[0]).split("/");
                    blog.push(new Blog(getText(entries[i].getElementsByTagName("body")[0]),
                        new Date(list[2],list[1]-1,list[0]),
                        getText(entries[i].getElementsByTagName("image")[0])));
                }

                document.getElementById("showall").disabled = false;

                showBlog(5);
            }
        }

        function showBlog(numEntries) {
            blog.sort(Blog.blogSorter);

            if (!numEntries)
                numEntries = blog.length;

            // Show the blog entries
            var i = 0, blogListHTML = "";
            while (i < blog.length && i < numEntries) {
                blogListHTML += blog[i].toHTML(i % 2 == 0);
                i++;
            }
            document.getElementById("blog").innerHTML = decodeURIComponent(blogListHTML);
        }

        function getText(elem) {
            var text = "";
            if (elem) {
                if (elem.childNodes) {
                    for (var i = 0; i < elem.childNodes.length; i++) {
                        var child = elem.childNodes[i];
                        if (child.nodeValue)
                            text += child.nodeValue;
                        else {
                            if (child.childNodes[0])
                                if (child.childNodes[0].nodeValue)
                                    text += child.childNodes[0].nodeValue;
                        }
                    }
                }
            }
            return text;
        }
    </script>
</head>


<body onload="loadBlog();">
<div>
    <div id="mainUser">
        <?php
        include 'menu.php';
        ?>
        <div class="title">
            <p>Чемпионаты по настольному футболу</p>
        </div>
        <hr/>
         <div id="news">
             <p class="legfilter">Новости</p>
             <div id="blog"></div>
             <input type="button" id="showall" class="button" value="Все новости" disabled="disabled" onclick="showBlog();" />
         </div>
    </div>
</div>
</body>
</html>